<script setup lang="ts">
import SimbaLogo from '@/components/SimbaLogo.vue';

interface Props {
    class?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="flex items-center gap-3" :class="$props.class">
        <SimbaLogo :show-text="false" class="scale-75 origin-left" />
        <div class="grid flex-1 text-left text-sm">
            <span class="mb-0.5 truncate font-black uppercase tracking-tighter leading-none text-safari-gold">Big Simba</span>
            <span class="truncate text-sm font-bold uppercase tracking-[0.2em] opacity-50">Safaris</span>
        </div>
    </div>
</template>
