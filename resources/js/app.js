// Halo de luz que sigue al cursor
const spotlight = document.querySelector('.spotlight');
if (spotlight && window.matchMedia('(pointer: fine)').matches) {
    window.addEventListener('pointermove', (e) => {
        spotlight.style.setProperty('--x', `${e.clientX}px`);
        spotlight.style.setProperty('--y', `${e.clientY}px`);
    });
}

// Barra de navegación: fondo al hacer scroll
const navbar = document.querySelector('[data-navbar]');
if (navbar) {
    const onScroll = () => navbar.classList.toggle('is-scrolled', window.scrollY > 20);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

// Menú móvil
const menuToggle = document.querySelector('[data-menu-toggle]');
const menu = document.querySelector('[data-menu]');
if (menuToggle && menu) {
    const setOpen = (open) => {
        menu.classList.toggle('hidden', !open);
        menuToggle.setAttribute('aria-expanded', String(open));
    };
    menuToggle.addEventListener('click', () => setOpen(menu.classList.contains('hidden')));
    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
}

// Resalta en la barra de navegación la sección visible
const navLinks = document.querySelectorAll('.nav-link[data-section]');
if (navLinks.length) {
    const byId = new Map([...navLinks].map((link) => [link.dataset.section, link]));
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                navLinks.forEach((link) => link.classList.remove('is-active'));
                byId.get(entry.target.id)?.classList.add('is-active');
            });
        },
        { rootMargin: '-40% 0px -55% 0px' },
    );
    byId.forEach((_, id) => {
        const section = document.getElementById(id);
        if (section) observer.observe(section);
    });
}

// Texto que se escribe y borra solo (línea de terminal del hero)
const typing = document.querySelector('[data-typing]');
if (typing && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const words = JSON.parse(typing.dataset.typing);
    let word = 0;
    let chars = typing.textContent.length;
    let deleting = true;
    const tick = () => {
        const current = words[word];
        chars += deleting ? -1 : 1;
        typing.textContent = current.slice(0, chars);
        let delay = deleting ? 45 : 90;
        if (!deleting && chars === current.length) {
            deleting = true;
            delay = 1800;
        } else if (deleting && chars === 0) {
            deleting = false;
            word = (word + 1) % words.length;
            delay = 300;
        }
        setTimeout(tick, delay);
    };
    setTimeout(tick, 2200);
}

// Aparición suave de bloques al entrar en pantalla
const revealObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.1 },
);
document.querySelectorAll('.reveal').forEach((el) => revealObserver.observe(el));

// Vista previa de imágenes en formularios del panel
document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
    input.addEventListener('change', () => {
        const target = document.getElementById(input.dataset.preview);
        const file = input.files?.[0];
        if (target && file) {
            target.src = URL.createObjectURL(file);
            target.classList.remove('hidden');
        }
    });
});
