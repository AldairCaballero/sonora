// Reproducción simulada: no hay audio, solo estado y tiempo.
// El estado vive en localStorage para que la reproducción continúe al cambiar de pantalla.

const CLAVE = 'sonora:estado';
const catalogo = JSON.parse(document.getElementById('catalogo').textContent);
const canciones = new Map(catalogo.canciones.map((cancion) => [cancion.id, cancion]));

/** Mismo formato que App\Support\Tiempo: mm:ss, o h:mm:ss desde una hora. */
function formatear(total) {
    const segundos = Math.floor(total);
    const horas = Math.floor(segundos / 3600);
    const minutos = String(Math.floor((segundos % 3600) / 60)).padStart(2, '0');
    const resto = String(segundos % 60).padStart(2, '0');

    return horas > 0 ? `${horas}:${minutos}:${resto}` : `${minutos}:${resto}`;
}

/** Cambia un atributo solo si el valor es distinto, para no escribir en el DOM sin necesidad. */
function fijar(elemento, atributo, valor) {
    if (elemento.getAttribute(atributo) !== valor) {
        elemento.setAttribute(atributo, valor);
    }
}

// --- Estado -----------------------------------------------------------------
// cola: ids de las canciones. origen: nombre de la lista de la que viene la cola.
// indice: posición actual en la cola. base: segundos ya sonados.
// desde: marca de tiempo (ms) en que empezó a sonar, o null en pausa.
// volumen: 0 a 100. favoritos: ids. mezclar: si la cola está mezclada.
// ordenOriginal: la cola antes de mezclarla, para restaurarla.

function estadoInicial() {
    return {
        cola: catalogo.playlists[0].canciones,
        origen: catalogo.playlists[0].nombre,
        indice: 0,
        reproduciendo: false,
        base: 0,
        desde: null,
        volumen: 70,
        favoritos: [],
        mezclar: false,
        ordenOriginal: null,
    };
}

function leerEstado() {
    try {
        const guardado = JSON.parse(localStorage.getItem(CLAVE));
        const valido = guardado
            && Array.isArray(guardado.cola)
            && guardado.cola.length > 0
            && guardado.cola.every((id) => canciones.has(id))
            && guardado.indice >= 0
            && guardado.indice < guardado.cola.length;

        return valido ? { ...estadoInicial(), ...guardado } : estadoInicial();
    } catch {
        return estadoInicial();
    }
}

function guardarEstado() {
    try {
        localStorage.setItem(CLAVE, JSON.stringify(estado));
    } catch {
        // Sin almacenamiento disponible, la reproducción sigue funcionando en esta página.
    }
}

const estado = leerEstado();

const cancionActual = () => canciones.get(estado.cola[estado.indice]);

function segundosActuales() {
    const extra = estado.reproduciendo ? (Date.now() - estado.desde) / 1000 : 0;

    return estado.base + extra;
}

// --- Acciones ---------------------------------------------------------------

function ir(indice) {
    estado.indice = indice;
    estado.base = 0;
    estado.desde = estado.reproduciendo ? Date.now() : null;
}

function alternar() {
    if (estado.reproduciendo) {
        estado.base = segundosActuales();
        estado.desde = null;
        estado.reproduciendo = false;
    } else {
        estado.desde = Date.now();
        estado.reproduciendo = true;
    }

    cambiar();
}

function siguiente() {
    ir((estado.indice + 1) % estado.cola.length);
    cambiar();
}

function anterior() {
    // Pasados 3 segundos, "anterior" reinicia la canción; antes, vuelve a la anterior.
    ir(segundosActuales() > 3 || estado.indice === 0 ? estado.indice : estado.indice - 1);
    cambiar();
}

/** Mezcla una copia de los ids (Fisher-Yates). */
function barajar(ids) {
    const copia = [...ids];

    for (let i = copia.length - 1; i > 0; i -= 1) {
        const j = Math.floor(Math.random() * (i + 1));

        [copia[i], copia[j]] = [copia[j], copia[i]];
    }

    return copia;
}

/** La canción que suena queda primera y el resto se baraja; el tiempo no se interrumpe. */
function activarMezcla() {
    const actual = estado.cola[estado.indice];

    estado.ordenOriginal = [...estado.cola];
    estado.cola = [actual, ...barajar(estado.cola.filter((id) => id !== actual))];
    estado.indice = 0;
    estado.mezclar = true;
}

function desactivarMezcla() {
    const actual = estado.cola[estado.indice];

    if (estado.ordenOriginal && estado.ordenOriginal.includes(actual)) {
        estado.cola = estado.ordenOriginal;
        estado.indice = estado.cola.indexOf(actual);
    }

    estado.ordenOriginal = null;
    estado.mezclar = false;
}

function alternarMezcla() {
    if (estado.mezclar) {
        desactivarMezcla();
    } else {
        activarMezcla();
    }

    cambiar();
}

function alternarFavorito() {
    const id = cancionActual().id;

    estado.favoritos = estado.favoritos.includes(id)
        ? estado.favoritos.filter((cadaId) => cadaId !== id)
        : [...estado.favoritos, id];

    cambiar();
}

/** Salta a un punto de la canción actual, sin llegar al final para no cambiar de canción. */
function saltarA(segundos) {
    estado.base = Math.min(Math.max(segundos, 0), cancionActual().segundos - 1);
    estado.desde = estado.reproduciendo ? Date.now() : null;
    cambiar();
}

function fijarVolumen(porcentaje) {
    estado.volumen = Math.round(Math.min(Math.max(porcentaje, 0), 100));
    cambiar();
}

/**
 * Empieza a reproducir la canción `id` con la cola de la lista `lista` (un elemento con data-cola).
 * Si la mezcla estaba activa, se conserva; `mezclar` la fuerza.
 */
function reproducirDesde(lista, id, mezclar = false) {
    if (!lista) {
        return;
    }

    const cola = lista.dataset.cola.split(',').map(Number);
    const indice = cola.indexOf(id);

    if (indice === -1 || !cola.every((cadaId) => canciones.has(cadaId))) {
        return;
    }

    const conservarMezcla = estado.mezclar || mezclar;

    estado.cola = cola;
    estado.origen = lista.dataset.origen || null;
    estado.mezclar = false;
    estado.ordenOriginal = null;
    estado.reproduciendo = true;
    ir(indice);

    if (conservarMezcla) {
        activarMezcla();
    }

    cambiar();
}

/** Si el tiempo transcurrido supera la duración, pasa a las canciones siguientes. */
function ponerAlDia() {
    let cambio = false;

    while (estado.reproduciendo && segundosActuales() >= cancionActual().segundos) {
        const sobrante = segundosActuales() - cancionActual().segundos;

        if (estado.indice + 1 < estado.cola.length) {
            estado.indice += 1;
            estado.base = sobrante;
            estado.desde = Date.now();
        } else {
            // Terminó la cola: vuelve al inicio y se detiene.
            estado.indice = 0;
            estado.base = 0;
            estado.desde = null;
            estado.reproduciendo = false;
        }

        cambio = true;
    }

    return cambio;
}

function cambiar() {
    guardarEstado();
    pintar();
}

// --- Pintar -----------------------------------------------------------------

const iconos = document.getElementById('iconos-reproduccion');
const barra = document.querySelector('[data-rp="barra"]');
const escenario = document.querySelector('[data-rp="escenario"]');
const listaCola = document.querySelector('[data-rp="cola"]');
const plantillaFila = document.getElementById('plantilla-fila');
let clavePintada = null;

function pintarBotonReproducir(boton) {
    const modo = estado.reproduciendo ? 'pausa' : 'play';

    if (boton.dataset.modo === modo) {
        return;
    }

    boton.dataset.modo = modo;
    boton.setAttribute('aria-label', estado.reproduciendo ? 'Pausar' : 'Reproducir');
    boton.querySelector('svg').replaceWith(
        iconos.content.querySelector(`[data-icono="${estado.reproduciendo ? 'pause' : 'play'}"]`).cloneNode(true),
    );
}

function pintarProgreso(contenedor, segundos, total) {
    const porcentaje = (segundos / total) * 100;
    const control = contenedor.querySelector('[role]');

    contenedor.querySelector('[data-relleno]').style.width = `${porcentaje}%`;
    contenedor.querySelector('[data-actual]').textContent = formatear(segundos);
    contenedor.querySelector('[data-total]').textContent = formatear(total);
    fijar(control, 'aria-valuenow', String(Math.round(porcentaje)));
    fijar(control, 'aria-valuetext', `${formatear(segundos)} de ${formatear(total)}`);
}

function pintarVolumen(contenedor) {
    const control = contenedor.querySelector('[role]');

    contenedor.querySelector('[data-relleno]').style.width = `${estado.volumen}%`;
    fijar(control, 'aria-valuenow', String(estado.volumen));
    fijar(control, 'aria-valuetext', `${estado.volumen}%`);
}

/** Título, subtítulo y portada de una vista (la barra o la pantalla del Reproductor). */
function pintarDatos(vista, cancion, subtitulo) {
    vista.querySelector('[data-rp="titulo"]').textContent = cancion.titulo;
    vista.querySelector('[data-rp="artista"]').textContent = subtitulo;
    vista.querySelectorAll('[data-rp="portada"] i').forEach((marca, i) => {
        marca.style.height = `${cancion.barras[i]}%`;
    });
}

/** Dibuja la cola del Reproductor clonando la fila que deja Blade en la plantilla. */
function reconstruirCola() {
    listaCola.dataset.cola = estado.cola.join(',');
    listaCola.dataset.origen = estado.origen ?? '';

    listaCola.replaceChildren(
        ...estado.cola.map((id, posicion) => {
            const cancion = canciones.get(id);
            const fila = plantillaFila.content.firstElementChild.cloneNode(true);

            fila.querySelector('[data-cancion]').dataset.cancion = id;
            fila.querySelector('[data-numero]').textContent = String(posicion + 1).padStart(2, '0');
            fila.querySelector('[data-titulo]').textContent = cancion.titulo;
            fila.querySelector('[data-artista]').textContent = cancion.artista;
            fila.querySelector('[data-duracion]').textContent = formatear(cancion.segundos);

            return fila;
        }),
    );
}

/** Marca con aria-current todas las filas de la canción que suena, en cualquier lista. */
function marcarActual(id) {
    document.querySelectorAll('[data-cancion]').forEach((fila) => {
        if (Number(fila.dataset.cancion) === id) {
            fila.setAttribute('aria-current', 'true');
        } else {
            fila.removeAttribute('aria-current');
        }
    });
}

function pintar() {
    const cancion = cancionActual();
    const segundos = Math.min(segundosActuales(), cancion.segundos);
    const esFavorita = estado.favoritos.includes(cancion.id);

    document.querySelectorAll('[data-rp="reproducir"]').forEach(pintarBotonReproducir);

    document.querySelectorAll('[data-rp="mezclar"]').forEach((boton) => {
        fijar(boton, 'aria-pressed', String(estado.mezclar));
    });

    document.querySelectorAll('[data-rp="favorito"]').forEach((boton) => {
        fijar(boton, 'aria-pressed', String(esFavorita));
        fijar(boton, 'aria-label', esFavorita ? 'Quitar de favoritos' : 'Añadir a favoritos');
    });

    document.querySelectorAll('[data-rp="volumen"]').forEach(pintarVolumen);

    // Lo que solo cambia con la canción o la cola se repinta cuando cambia su clave.
    const clave = [cancion.id, estado.cola.join(','), estado.origen].join('|');

    if (clave !== clavePintada) {
        if (barra) {
            pintarDatos(barra, cancion, cancion.artista);
        }

        if (escenario) {
            pintarDatos(escenario, cancion, estado.origen ? `${cancion.artista} · ${estado.origen}` : cancion.artista);
        }

        if (listaCola) {
            reconstruirCola();
        }

        marcarActual(cancion.id);
        clavePintada = clave;
    }

    [barra, escenario].filter(Boolean).forEach((vista) => {
        pintarProgreso(vista.querySelector('[data-rp="progreso"]'), segundos, cancion.segundos);
    });
}

// --- Controles deslizantes --------------------------------------------------

/** Conecta un control con role="slider": clic, arrastre y teclado. */
function conectarDeslizador(control, alMover, teclas) {
    let arrastrando = false;

    const fraccion = (evento) => {
        const caja = control.getBoundingClientRect();

        return Math.min(Math.max((evento.clientX - caja.left) / caja.width, 0), 1);
    };

    control.addEventListener('pointerdown', (evento) => {
        arrastrando = true;
        control.setPointerCapture(evento.pointerId);
        alMover(fraccion(evento));
    });

    control.addEventListener('pointermove', (evento) => {
        if (arrastrando) {
            alMover(fraccion(evento));
        }
    });

    const soltar = () => {
        arrastrando = false;
    };

    control.addEventListener('pointerup', soltar);
    control.addEventListener('pointercancel', soltar);

    control.addEventListener('keydown', (evento) => {
        const tecla = teclas[evento.key];

        if (tecla) {
            evento.preventDefault();
            tecla();
        }
    });
}

const teclasProgreso = {
    ArrowRight: () => saltarA(segundosActuales() + 5),
    ArrowUp: () => saltarA(segundosActuales() + 5),
    ArrowLeft: () => saltarA(segundosActuales() - 5),
    ArrowDown: () => saltarA(segundosActuales() - 5),
    Home: () => saltarA(0),
    End: () => saltarA(cancionActual().segundos),
};

const teclasVolumen = {
    ArrowRight: () => fijarVolumen(estado.volumen + 5),
    ArrowUp: () => fijarVolumen(estado.volumen + 5),
    ArrowLeft: () => fijarVolumen(estado.volumen - 5),
    ArrowDown: () => fijarVolumen(estado.volumen - 5),
    Home: () => fijarVolumen(0),
    End: () => fijarVolumen(100),
};

document.querySelectorAll('[data-rp="progreso"] [role="slider"]').forEach((control) => {
    conectarDeslizador(control, (fraccion) => saltarA(fraccion * cancionActual().segundos), teclasProgreso);
});

document.querySelectorAll('[data-rp="volumen"] [role="slider"]').forEach((control) => {
    conectarDeslizador(control, (fraccion) => fijarVolumen(fraccion * 100), teclasVolumen);
});

// --- Eventos y arranque -----------------------------------------------------

const acciones = {
    reproducir: alternar,
    siguiente,
    anterior,
    mezclar: alternarMezcla,
    favorito: alternarFavorito,
    'reproducir-lista': () => {
        const lista = document.querySelector('[data-cola]');

        reproducirDesde(lista, Number(lista.dataset.cola.split(',')[0]));
    },
    'mezclar-lista': () => {
        const lista = document.querySelector('[data-cola]');
        const ids = lista.dataset.cola.split(',').map(Number);

        reproducirDesde(lista, ids[Math.floor(Math.random() * ids.length)], true);
    },
};

document.addEventListener('click', (evento) => {
    const fila = evento.target.closest('[data-cancion]');

    if (fila) {
        const id = Number(fila.dataset.cancion);

        // Pulsar la canción que ya está sonando alterna reproducir y pausa.
        if (id === cancionActual().id) {
            alternar();
        } else {
            reproducirDesde(fila.closest('[data-cola]'), id);
        }

        return;
    }

    const control = evento.target.closest('[data-rp]');
    const accion = control && acciones[control.dataset.rp];

    if (accion) {
        accion();
    }
});

// El tiempo real sale de la marca de tiempo; este intervalo solo repinta.
setInterval(() => {
    if (!estado.reproduciendo) {
        return;
    }

    if (ponerAlDia()) {
        guardarEstado();
    }

    pintar();
}, 250);

if (ponerAlDia()) {
    guardarEstado();
}

pintar();