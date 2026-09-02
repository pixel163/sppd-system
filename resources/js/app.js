import './bootstrap';
import { createIcons, icons } from 'lucide';

const profileToggle = document.getElementById('profileToggle');
const profileMenu = document.getElementById('profileMenu');

if (profileToggle && profileMenu) {
	profileToggle.addEventListener('click', (event) => {
		event.stopPropagation();
		const isHidden = profileMenu.classList.toggle('hidden');
		profileToggle.setAttribute('aria-expanded', String(!isHidden));
	});

	profileMenu.addEventListener('click', (event) => {
		event.stopPropagation();
	});

	document.addEventListener('click', () => {
		profileMenu.classList.add('hidden');
		profileToggle.setAttribute('aria-expanded', 'false');
	});
}

createIcons({
    icons,
});