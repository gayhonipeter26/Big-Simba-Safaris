import { onMounted, ref } from 'vue';

type Appearance = 'light' | 'dark' | 'system';

export function updateTheme(value?: Appearance) {
    document.documentElement.classList.add('dark');
    document.documentElement.style.colorScheme = 'dark';
}

export function initializeTheme() {
    // Lock in dark mode unconditionally
    localStorage.setItem('appearance', 'dark');
    updateTheme('dark');
}

export function useAppearance() {
    const appearance = ref<Appearance>('dark');

    onMounted(() => {
        initializeTheme();
    });

    function updateAppearance(value: Appearance) {
        // Ignore any attempts to change it, maintain dark mode lock
        appearance.value = 'dark';
        initializeTheme();
    }

    return {
        appearance,
        updateAppearance,
    };
}
