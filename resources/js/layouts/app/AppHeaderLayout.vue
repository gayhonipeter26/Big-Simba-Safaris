<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue';
import AppShell from '@/components/AppShell.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    fullBleed?: boolean;
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    fullBleed: false,
});
</script>

<template>
    <AppShell class="flex-col bg-[#050505] min-h-screen">
        <AppHeader :breadcrumbs="breadcrumbs" />
        <main :class="[
            'flex-1 flex flex-col w-full',
            fullBleed ? '' : 'mx-auto max-w-[1600px] px-4 md:px-8 py-10'
        ]">
            <slot />
        </main>
    </AppShell>
</template>
