import './bootstrap';
import Alpine from 'alpinejs';
import setupEvaluationsBadges from './evaluations';

window.Alpine = Alpine;
Alpine.start();

// Run after DOM is ready
document.addEventListener("DOMContentLoaded", () => {
	setupEvaluationsBadges();
});
