<script setup lang="ts">
import { useToast } from '@/composables/useToast';
import { X, CheckCircle, AlertCircle, Info } from 'lucide-vue-next';

const { toasts, dismiss } = useToast();

const iconFor = (type: string) => {
    if (type === 'success') return CheckCircle;
    if (type === 'error') return AlertCircle;
    return Info;
};

const colorFor = (type: string) => {
    return 'border-safari-gold/40 bg-black/95 backdrop-blur-3xl text-safari-gold shadow-[0_0_50px_rgba(202,138,4,0.15)]';
};
</script>

<template>
    <Teleport to="body">
        <div class="fixed top-24 left-1/2 -translate-x-1/2 z-[9999] flex flex-col items-center gap-4 max-w-lg w-full px-6 pointer-events-none">
            <TransitionGroup name="toast" tag="div" class="flex flex-col items-center gap-4 w-full">
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    :class="['pointer-events-auto flex items-start gap-5 p-6 border rounded-sm shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] backdrop-blur-2xl bg-black/90 w-full', colorFor(toast.type)]"
                >
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-safari-gold/10 border border-safari-gold/20">
                        <component :is="iconFor(toast.type)" class="w-5 h-5 shrink-0" />
                    </div>
                    <div class="flex-1 pt-1">
                        <p class="text-[13px] font-black uppercase tracking-[0.2em] leading-relaxed text-white">{{ toast.message }}</p>
                    </div>
                    <button @click="dismiss(toast.id)" class="opacity-40 hover:opacity-100 transition-opacity p-1">
                        <X class="w-4 h-4" />
                    </button>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}
.toast-enter-from {
    opacity: 0;
    transform: translateY(-40px) scale(0.95);
}
.toast-leave-to {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
}
</style>

