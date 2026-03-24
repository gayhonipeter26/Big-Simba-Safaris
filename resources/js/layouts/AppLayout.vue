<script setup lang="ts">
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage<any>();
const isAdminSection = computed(() => page.url.startsWith('/admin'));

// Map layout based purely on routing context to ensure absolute separation
const layout = computed(() => isAdminSection.value ? AppSidebarLayout : AppHeaderLayout);
</script>

<template>
    <component :is="layout" :breadcrumbs="breadcrumbs" :key="isAdminSection ? 'admin' : 'public'">
        <slot />
    </component>
</template>
