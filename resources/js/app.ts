import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { initializeTheme } from './composables/useAppearance';
import { i18nVue } from 'laravel-vue-i18n';

declare interface ImportMetaEnv {
    readonly VITE_APP_NAME: string;
    [key: string]: string | boolean | undefined;
}

declare interface ImportMeta {
    readonly env: ImportMetaEnv;
    readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // Custom Reveal Directive for Scroll Animations
        app.directive('reveal', {
            mounted(el, binding) {
                // Determine direction/intensity from binding, default is subtle bottom-up
                const delay = binding.value?.delay || 0;
                
                // Initial hidden state
                el.classList.add('opacity-0', 'translate-y-12', 'transition-all', 'duration-[1200ms]', 'ease-out');
                if (delay) el.style.transitionDelay = `${delay}ms`;

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            el.classList.remove('opacity-0', 'translate-y-12');
                            el.classList.add('opacity-100', 'translate-y-0');
                            observer.unobserve(el); // Only reveal once
                        }
                    });
                }, {
                    threshold: 0.1, // Trigger when 10% visible
                    rootMargin: '0px 0px -50px 0px'
                });
                
                observer.observe(el);
            }
        });

        app.use(plugin)
            .use(ZiggyVue)
            .use(i18nVue, { 
                lang: (props.initialPage.props as any).locale?.current || 'en',
                resolve: (lang: string) => import(`../../lang/${lang}.json`),
            })
            .mount(el);
    },
    progress: {
        color: '#ca8a04',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
