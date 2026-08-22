import { createIcons, icons } from 'lucide';

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

window.createIcons = createIcons;
window.lucideIcons = icons;

