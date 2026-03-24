<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar" class="bg-[#0a0a0a]">
        <AppSidebar class="bg-[#121212] border-r border-white/5" />
        <AppContent variant="sidebar" class="bg-[#121212]">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" class="bg-[#121212] border-b border-white/5" />
            <div class="flex flex-1 flex-col gap-4 p-4 pt-0 bg-[#0a0a0a] min-h-screen">
                <slot />
            </div>
        </AppContent>
    </AppShell>
</template>
