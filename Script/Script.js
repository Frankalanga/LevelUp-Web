document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('consulta-form');

    formulario.addEventListener('submit', function (event) {
        // Evita que el formulario se envíe de inmediato
        event.preventDefault();

        // Obtén el valor del campo de teléfono
        const telefono = document.getElementById('telefono').value;

        // Limpia el número de teléfono (elimina todo excepto dígitos)
        const telefonoLimpio = telefono.replace(/\D/g, '');

        // Validación del número de teléfono (debe tener 9 dígitos)
        if (telefonoLimpio.length !== 9) {
            alert('El número de teléfono debe tener 9 dígitos.');
            return; // Detiene el envío del formulario
        }

        // Muestra una alerta de éxito
        alert('Gracias por tu consulta. Nos pondremos en contacto contigo pronto.');

        // Envía el formulario
        formulario.submit();
    });
});