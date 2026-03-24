<script setup lang="ts">
import { SidebarProvider } from '@/components/ui/sidebar';
import FloatingActions from '@/components/FloatingActions.vue';
import { onMounted, ref, type HTMLAttributes } from 'vue';

interface Props {
    variant?: 'header' | 'sidebar';
    class?: HTMLAttributes['class'];
}

defineProps<Props>();

const isOpen = ref(true);

onMounted(() => {
    isOpen.value = localStorage.getItem('sidebar') !== 'false';
});

const handleSidebarChange = (open: boolean) => {
    isOpen.value = open;
    localStorage.setItem('sidebar', String(open));
};
</script>

<template>
    <div v-if="variant === 'header'" class="flex min-h-screen w-full flex-col relative" :class="$props.class">
        <slot />
        <FloatingActions />
    </div>
    <SidebarProvider v-else :default-open="isOpen" :open="isOpen" :class="$props.class" @update:open="handleSidebarChange">
        <slot />
        <FloatingActions />
    </SidebarProvider>
</template>
