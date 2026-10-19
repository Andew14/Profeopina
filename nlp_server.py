from fastapi import FastAPI
from pydantic import BaseModel
from transformers import AutoTokenizer, AutoModelForSequenceClassification
import torch
import os
import re

app = FastAPI()

# Carpetas donde deben extraerse los modelos descargados de Colab
MODEL_ASPECT_PATH = "./modelo_aspecto"
MODEL_SENTIMENT_PATH = "./modelo_sentimiento"

# Inicializar modelos como None para cargarlos bajo demanda
model_aspect = None
tokenizer_aspect = None
model_sent = None
tokenizer_sent = None

def load_models():
    global model_aspect, tokenizer_aspect, model_sent, tokenizer_sent
    if model_aspect is None:
        if not os.path.exists(MODEL_ASPECT_PATH) or not os.path.exists(MODEL_SENTIMENT_PATH):
            raise FileNotFoundError(
                "¡Error! No se encontraron las carpetas 'modelo_aspecto' o 'modelo_sentimiento' en la raíz. "
                "Asegúrate de descargar los archivos .zip de Colab, descomprimirlos y colocarlos aquí."
            )
        tokenizer_aspect = AutoTokenizer.from_pretrained(MODEL_ASPECT_PATH)
        model_aspect = AutoModelForSequenceClassification.from_pretrained(MODEL_ASPECT_PATH)
        tokenizer_sent = AutoTokenizer.from_pretrained(MODEL_SENTIMENT_PATH)
        model_sent = AutoModelForSequenceClassification.from_pretrained(MODEL_SENTIMENT_PATH)

def split_into_segments(text: str):
    """
    Divide el comentario en oraciones y proposiciones independientes según puntuación 
    y conectores de contraste, adición o separación (ej. 'pero', 'y', 'e', 'aunque', ',').
    """
    pattern = r'[.!?;\n]|,\s*|\b(?:pero|sin embargo|ademas|además|aunque|ya que|porque|y|e)\b'
    raw_segments = re.split(pattern, text, flags=re.IGNORECASE)
    
    clean_segments = []
    for seg in raw_segments:
        cleaned = seg.strip()
        if len(cleaned.split()) >= 1 and len(cleaned) >= 3:
            clean_segments.append(cleaned)
            
    return clean_segments if clean_segments else [text.strip()]

def is_meaningless_text(text: str) -> bool:
    """
    Detecta si una cadena carece de sentido (ej. 'aaaa', 'saa', 'qwerty', símbolos repetidos).
    """
    cleaned = text.strip().lower()
    if len(cleaned) < 3:
        return True
    if len(set(cleaned)) == 1: # 'aaaa', 'zzzz'
        return True
    # Para palabras cortas de <= 4 caracteres con letras repetidas (ej. 'saa', 'aba')
    if len(cleaned) <= 4:
        max_freq = max(cleaned.count(c) for c in set(cleaned))
        if max_freq / len(cleaned) >= 0.5: # 2/3 = 66% de repetidos
            valid_short_words = {'bien', 'buen', 'mala', 'malo', 'hola', 'prof', 'top', 'god', '10/10'}
            if cleaned not in valid_short_words:
                return True
    if not re.search(r'[aeiouáéíóúy]', cleaned):
        return True
    return False

def get_explicit_aspect_override(segment: str):
    """
    Refuerzo Léxico de 4 Dimensiones para respaldar predicciones del modelo BERT:
    0: Metodología -> claridad_comunicacion
    1: Materiales/Dominio -> dominio_materia
    2: Evaluación  -> justicia_evaluacion
    3: Actitud     -> empatia_relacion
    """
    seg_lower = segment.lower()
    if any(k in seg_lower for k in ['explic', 'fundamento', 'didact', 'clase', 'enseñ', 'entend', 'clar']):
        return 0
    if any(k in seg_lower for k in ['domin', 'sabe', 'conoc', 'expert', 'materia', 'tema', 'teori', 'teorí']):
        return 1
    if any(k in seg_lower for k in ['examen', 'evalu', 'nota', 'calific', 'criterio', 'parcial', 'final']) or re.search(r'\bia\b|inteligencia artificial', seg_lower):
        return 2
    if any(k in seg_lower for k in ['empat', 'comprens', 'tarde', 'tardanza', 'amable', 'trato', 'paciencia', 'asistencia', 'duda', 'dudas', 'consulta', 'apoyo', 'atencion', 'atención']):
        return 3
    return None

class ReviewRequest(BaseModel):
    text: str

@app.on_event("startup")
def startup_event():
    print("[INFO] Precargando modelos BERT en memoria RAM...")
    try:
        load_models()
        print("[INFO] Modelos BERT precargados exitosamente. Listo para recibir inferencias.")
    except Exception as e:
        print(f"[ADVERTENCIA] No se pudieron precargar los modelos al iniciar: {e}")

@app.post("/analizar")
def analizar_comentario(request: ReviewRequest):
    load_models()
    
    # 0. Si el texto completo carece de sentido (ej. 'aaaa'), retornar respuesta vacía
    if is_meaningless_text(request.text):
        return {
            "claridad_comunicacion": None,
            "justicia_evaluacion": None,
            "empatia_relacion": None,
            "dominio_materia": None
        }

    # 1. Segmentar comentario en frases/oraciones independientes
    segments = split_into_segments(request.text)
    
    dimension_scores = {
        "claridad_comunicacion": [],
        "justicia_evaluacion": [],
        "empatia_relacion": [],
        "dominio_materia": []
    }
    
    aspect_to_dim = {
        0: "claridad_comunicacion",
        1: "dominio_materia",
        2: "justicia_evaluacion",
        3: "empatia_relacion"
    }

    # 2. Analizar cada segmento con PyTorch BERT y Softmax
    for segment in segments:
        if is_meaningless_text(segment):
            continue

        inputs_aspect = tokenizer_aspect(segment, return_tensors="pt", truncation=True, max_length=128)
        with torch.no_grad():
            outputs_aspect = model_aspect(**inputs_aspect)
        
        # Calcular probabilidades con Softmax
        probs_aspect = torch.softmax(outputs_aspect.logits, dim=1)
        max_prob_aspect, aspect_idx_tensor = torch.max(probs_aspect, dim=1)
        aspect_idx = aspect_idx_tensor.item()
        
        # Si la confianza de BERT es menor a 35% y no hay anclaje léxico, ignorar segmento
        explicit_override = get_explicit_aspect_override(segment)
        if explicit_override is not None:
            aspect_idx = explicit_override
        elif max_prob_aspect.item() < 0.35:
            continue

        # Predecir Sentimiento del segmento
        inputs_sent = tokenizer_sent(segment, return_tensors="pt", truncation=True, max_length=128)
        with torch.no_grad():
            outputs_sent = model_sent(**inputs_sent)
        
        probs_sent = torch.softmax(outputs_sent.logits, dim=1)
        sent_idx = torch.argmax(probs_sent, dim=1).item()

        # Evaluación de sentimiento (0: Positivo = 5, 1: Negativo = 1, 2: Neutral = 3)
        score = 3
        if sent_idx == 0:
            score = 5
        elif sent_idx == 1:
            score = 1

        # Ajuste contextual si la frase contiene términos críticos o negaciones
        seg_lower = segment.lower()
        if any(k in seg_lower for k in ['no es empatico', 'no empatico', 'sin empatia', 'poco empatico', 'no comprende', 'no entiende', 'no es comprensible', 'incomprensible', 'intolerante', 'prepotente', 'grosero']):
            score = 1
        elif any(k in seg_lower for k in ['hechos con ia', 'ia\b', 'cuchilla', 'injust', 'dificil', 'excesiv']):
            score = 2
        elif any(k in seg_lower for k in ['comprensible', 'amable', 'excelente', 'muy bien', 'paciente', 'muy empatico']):
            score = 5

        dim_key = aspect_to_dim.get(aspect_idx)
        if dim_key:
            dimension_scores[dim_key].append(score)

    # 3. Consolidar resultados por dimensión
    resultado = {
        "claridad_comunicacion": None,
        "justicia_evaluacion": None,
        "empatia_relacion": None,
        "dominio_materia": None
    }

    for dim, scores in dimension_scores.items():
        if scores:
            resultado[dim] = int(round(sum(scores) / len(scores)))

    return resultado

if __name__ == "__main__":
    import uvicorn
    host = os.environ.get("NLP_HOST", "0.0.0.0")
    port = int(os.environ.get("NLP_PORT", 8001))
    uvicorn.run("nlp_server:app", host=host, port=port)
