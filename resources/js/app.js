import './bootstrap';
import '../css/app.css';

import { createApp, h, ref } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

createInertiaApp({
    title: (title) => `${title} — MA Tools`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const showPopup     = ref(false);
        const pendingAction = ref(null);

        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.provide('triggerAdPopup', (callback = null) => {
            pendingAction.value = callback;
            showPopup.value = true;
        });
        app.provide('adPopupState', { showPopup, pendingAction });
        return app.mount(el);
    },
    progress: {
        color: '#7c3aed',
        showSpinner: true,
    },
});

