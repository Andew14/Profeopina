
function cambiarImagen() {
    var iconoGuardar = document.getElementById("icono-guardar");
    if (iconoGuardar.src.includes("/imagenes/singuardar.png")) {
        iconoGuardar.src = "/imagenes/guardar.png";
    } else {
        iconoGuardar.src = "/imagenes/singuardar.png";
    }
}
