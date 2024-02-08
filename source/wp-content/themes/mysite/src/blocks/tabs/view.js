// TODO
document.addEventListener('alpine:init', () => {
	const { Alpine } = window;

	Alpine.data('tabs', () => ({
		activeTab: 0,
	}));
});
