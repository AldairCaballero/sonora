import './reproductor';

// Filtros de Inicio: el chip pulsado queda presionado y se muestran solo las secciones que le corresponden.
document.querySelectorAll('[data-filtros]').forEach((grupo) => {
    const chips = grupo.querySelectorAll('[data-filtro]');
    const secciones = document.querySelectorAll('[data-seccion]');

    chips.forEach((chip) => {
        chip.addEventListener('click', () => {
            const filtro = chip.dataset.filtro;

            chips.forEach((otro) => otro.setAttribute('aria-pressed', String(otro === chip)));

            secciones.forEach((seccion) => {
                seccion.hidden = filtro !== 'todo' && seccion.dataset.seccion !== filtro;
            });
        });
    });
});