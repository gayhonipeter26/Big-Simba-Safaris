import { ref } from 'vue';

interface Toast {
    id: number;
    message: string;
    type: 'success' | 'error' | 'info';
    duration?: number;
}

const toasts = ref<Toast[]>([]);
let counter = 0;

export function useToast() {
    const show = (message: string, type: Toast['type'] = 'success', duration = 4000) => {
        const id = ++counter;
        toasts.value.push({ id, message, type, duration });
        setTimeout(() => dismiss(id), duration);
    };

    const dismiss = (id: number) => {
        toasts.value = toasts.value.filter((t) => t.id !== id);
    };

    return { toasts, show, dismiss };
}
