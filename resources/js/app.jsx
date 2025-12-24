import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import AppLayout from './Layouts/AppLayout';

createInertiaApp({
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob('./Pages/**/*.jsx'),
        );

        return page.then((module) => {
            const Component = module.default;
            Component.layout = Component.layout || ((page) => <AppLayout>{page}</AppLayout>);
            return module;
        });
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});
