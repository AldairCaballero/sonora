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
// Tema: alterna entre oscuro y claro y lo recuerda.
const botonTema = document.querySelector('[data-tema]');

if (botonTema) {
    const pintarTema = () => {
        botonTema.textContent = document.documentElement.dataset.theme === 'light' ? 'Tema oscuro' : 'Tema claro';
    };

    botonTema.addEventListener('click', () => {
        const nuevo = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';

        document.documentElement.dataset.theme = nuevo;

        try {
            localStorage.setItem('sonora:tema', nuevo);
        } catch {
            // Sin almacenamiento, el tema solo dura mientras la página siga abierta.
        }

        pintarTema();
    });

    pintarTema();
}