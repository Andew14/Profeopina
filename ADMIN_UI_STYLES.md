# Admin UI - Estilos y Componentes

## 📋 Descripción General

Se han implementado estilos completos para la interfaz de administración de ProfeOpina. Todos los estilos están centralizados en `resources/css/admin-layout.css` y se cargan automáticamente en el layout `layouts/admin_base.blade.php`.

---

## 🎨 Componentes de Estilo

### 1. **Header del Admin**
```html
.admin-header - Encabezado horizontal con logo, navegación y menú usuario
├── .admin-header-left - Sección izquierda con logo
├── .admin-header-nav - Navegación principal
├── .admin-header-right - Sección derecha con selector de idioma y usuario
├── .admin-lang-selector - Selector de idioma (EN/ES)
├── .admin-user-menu - Menú del usuario
├── .admin-logout-btn - Botón de cierre de sesión
```

**Características:**
- Altura fija: 70px
- Diseño flex responsive
- Logo ProfeOpina a la izquierda
- Enlaces de navegación: Home, Gestionar Profesores, Gestionar Reseñas
- Selector de idioma con banderas
- Nombre del usuario y botón logout

---

### 2. **Formularios**
```html
.admin-container form - Formulario general
├── .admin-form-group - Grupo individual de formulario
├── .admin-form-actions - Botones de acción
```

**Elementos del formulario:**
- `input[type="text"]` - Campos de texto
- `input[type="email"]` - Campos de correo
- `textarea` - Áreas de texto
- `select` - Selectores
- `label` - Etiquetas

**Estilos aplicados:**
- Ancho completo (100%)
- Padding: 10px 12px
- Borde: 1px solid #ddd
- Focus color: #d9bc27 (amarillo ProfeOpina)
- Sombra en focus para mejor UX
- Fondo blanco con padding 30px
- Border-radius: 4px

---

### 3. **Botones**
```html
.btn-continue / .btn-save - Botón primario (amarillo ProfeOpina)
.btn-cancel - Botón secundario (gris)
.btn-danger - Botón destructivo (rojo)
.btn-success - Botón de éxito (verde)
.btn-primary - Botón azul alternativo
.btn-sm - Tamaño pequeño
.btn-edit - Botón de edición
.btn-delete - Botón de eliminación
.btn-toggle - Botón de cambio de estado
```

**Características:**
- Transiciones suaves (0.3s)
- Efecto hover con elevación (translateY -2px)
- Estados disabled y loading
- Padding y font-size adaptables

---

### 4. **Tablas**
```html
.table - Tabla principal
├── thead - Encabezado con fondo gris
├── tbody - Cuerpo de tabla
└── .action-buttons - Contenedor de botones en filas
```

**Estilos:**
- Ancho: 100%
- Fondo blanco
- Border-collapse: collapse
- Sombra suave
- Hover en filas: fondo #f9f9f9
- Filas alternas con mejor contraste

---

### 5. **Alertas**
```html
.admin-alert - Contenedor base
├── .admin-alert-success - Alerta verde (éxito)
├── .admin-alert-error - Alerta roja (error)
├── .admin-alert-warning - Alerta amarilla (advertencia)
└── .admin-alert-info - Alerta azul (información)
```

**Ejemplo de uso:**
```html
<div class="admin-alert admin-alert-success">
    Profesor creado exitosamente
</div>
```

---

### 6. **Badges**
```html
.badge - Badge base
├── .badge-success - Verde
├── .badge-danger - Rojo
├── .badge-warning - Amarillo
├── .badge-info - Azul
└── .badge-secondary - Gris
```

**Uso en tablas:**
```html
<span class="badge badge-success">Activo</span>
<span class="badge badge-danger">Inactivo</span>
```

---

### 7. **Grid de Acciones Rápidas**
```html
.admin-quick-actions
├── .actions-grid - Grid responsive
└── .action-card - Tarjetas de acciones
    ├── .action-title
    └── .action-description
```

**Características:**
- Responsivo: auto-fit con mín 250px
- Grid gap: 20px
- Hover: elevación y cambio de color
- Sombra suave

---

### 8. **Barra de Acciones**
```html
.admin-actions-bar - Barra con título y botones
├── .admin-actions-left - Sección izquierda
└── .admin-actions-right - Sección derecha (botones)
```

**Uso:**
```html
<div class="admin-actions-bar">
    <h1>Gestionar Profesores</h1>
    <div class="admin-actions-right">
        <a href="{{ route('admin.profesors.create') }}" class="btn-continue">
            + Agregar Profesor
        </a>
    </div>
</div>
```

---

### 9. **Contenedor Principal**
```html
.admin-container - Contenedor máx 1200px centrado
.admin-main-content - Área de contenido principal
```

---

## 🎯 Clases de Utilidad

### Opciones de Tamaño
- `.btn-sm` - Botones pequeños (6px 12px)

### Estados
- `.btn:disabled` - Botón deshabilitado
- `.btn.loading` - Botón con indicador de carga

### Spacing
- Padding formularios: 30px
- Padding tablas: 12px 15px
- Gap grids: 20px
- Margen inferior elementos: 20px-30px

---

## 📱 Diseño Responsivo

Breakpoint: **768px y menos**

**Cambios en mobile:**
- Header: dirección de columna
- Altura: auto (sin 70px fijo)
- Navegación: gap reducido
- Tablas: font-size reducido
- Botones: ancho 100%
- Grids: 1 columna

---

## 🌍 Idiomas Soportados

Todas las etiquetas y mensajes usan traducción dinámica:

```php
{{ __('messages.save') }}      // Guardar (ES) / Save (EN)
{{ __('messages.cancel') }}    // Cancelar (ES) / Cancel (EN)
{{ __('messages.error') }}     // Error (ambos)
{{ __('messages.yes') }}       // Sí (ES) / Yes (EN)
{{ __('messages.no') }}        // No (ambos)
```

---

## 🎬 Animaciones

### Entrada de elementos
- Duración: 0.3s
- Easing: ease-out
- Transforma desde derecha

### Carga de botones
- Spinner circular
- Duración: 0.6s
- Linear infinite

### Hover effects
- Transición: 0.3s
- Elevación: translateY(-2px)
- Box-shadow mejorada

---

## 🔗 Archivos Relacionados

- `resources/css/admin-layout.css` - Estilos principales
- `resources/views/layouts/admin_base.blade.php` - Layout base
- `resources/views/layouts/_admin_header.blade.php` - Header component
- `resources/views/admin/**/*.blade.php` - Vistas de admin

---

## 📝 Ejemplo de Vista Completa

```blade
@extends('layouts.admin_base')

@section('title', __('messages.manage_profesors'))

@section('content')
<div class="admin-container">
    {{-- Barra de acciones --}}
    <div class="admin-actions-bar">
        <h1>{{ __('messages.manage_profesors') }}</h1>
        <div class="admin-actions-right">
            <a href="{{ route('admin.profesors.create') }}" class="btn-continue">
                + {{ __('messages.add_profesor') }}
            </a>
        </div>
    </div>

    {{-- Alertas --}}
    @if (session('success'))
        <div class="admin-alert admin-alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabla --}}
    @if($profesors->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profesors as $profesor)
                    <tr>
                        <td>{{ $profesor->nombre }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.profesors.edit', $profesor) }}" 
                                   class="btn btn-edit btn-sm">
                                    ✏️ {{ __('messages.edit') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="admin-alert admin-alert-info">
            {{ __('messages.no_data_found') }}
        </div>
    @endif
</div>
@endsection
```

---

## ✅ Checklist de Implementación

- ✅ Header horizontal 70px
- ✅ Navegación con 3 links
- ✅ Selector de idioma
- ✅ Menú usuario con logout
- ✅ Formularios con estilos
- ✅ Tablas responsivas
- ✅ Botones con varios estilos
- ✅ Alertas coloreadas
- ✅ Badges de estado
- ✅ Grid de acciones rápidas
- ✅ Barra de acciones
- ✅ Soporte i18n (EN/ES)
- ✅ Responsive design
- ✅ Animaciones y transiciones
- ✅ Hover effects
- ✅ Estados de botones (disabled, loading)

---

## 🚀 Próximos Pasos

1. Agregar más iconos usando emojis o Font Awesome
2. Implementar notificaciones toast persistentes
3. Agregar modal de confirmación para acciones destructivas
4. Implementar paginación en tablas
5. Agregar búsqueda y filtrado en tablas
6. Implementar drag-drop para ordenar elementos

---

**Última actualización:** Enero 17, 2026
**Versión:** 1.0
**Estado:** ✅ Completo y Funcional
