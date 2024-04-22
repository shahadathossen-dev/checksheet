import './bootstrap';
import '../css/app.css';
import '../scss/app.scss';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist';

// Import assets from resource folder
import.meta.glob([
    '../images/**',
    '../fonts/**',
]);
// Vuex Store
import store from './store';
// Global Mixins
import mixins from './mixins';
// Vue Sweet Alert 2
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// Global Components
import components from '@/components.js';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(store)
            .use(plugin)
            .use(Ziggy)
            .use(components)
            .use(VueSweetalert2)
            .mixin(mixins)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
