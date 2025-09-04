import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createNotivue } from 'notivue';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
// Initiate Notivue
const notivue = createNotivue({
    position: 'top-right',
    pauseOnHover: true,
    limit: 5,
});
createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(notivue)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#ddbb7c',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
