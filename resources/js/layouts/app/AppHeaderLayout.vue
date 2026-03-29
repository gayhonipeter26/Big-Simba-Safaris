<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue';
import AppShell from '@/components/AppShell.vue';
import SimbaFooter from '@/components/SimbaFooter.vue';
import Toast from '@/components/Toast.vue';
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
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[1001] focus:bg-safari-gold focus:text-black focus:px-6 focus:py-3 focus:font-black focus:uppercase focus:tracking-widest focus:rounded-sm shadow-2xl">
            Skip to Mission Content
        </a>
        <AppHeader :breadcrumbs="breadcrumbs" />
        <main id="main-content" :class="[
            'flex-1 flex flex-col w-full',
            fullBleed ? '' : 'mx-auto max-w-[1600px] px-4 md:px-8 py-10'
        ]">
            <slot />
        </main>
        <SimbaFooter />
    </AppShell>
    <Toast />
</template>
