<script setup lang="ts">
defineProps<{
  size?: 'sm' | 'md' | 'lg';
  showText?: boolean;
}>();
</script>

<template>
  <div class="flex items-center gap-4 group">
      <div 
        :class="[
          'rounded-full overflow-hidden transition-all duration-500 group-hover:scale-110 shadow-lg border border-white/10',
          size === 'lg' ? 'h-24 w-24' : size === 'md' ? 'h-16 w-16' : 'h-12 w-12'
        ]"
      >
        <img src="/images/logo.png" alt="Big Simba Safaris" class="w-full h-full object-cover" />
      </div>
      <div v-if="showText !== false">
        <p :class="[
          'font-black uppercase tracking-[0.4em] opacity-90 text-white leading-tight',
          size === 'lg' ? 'text-2xl' : size === 'md' ? 'text-lg' : 'text-xs'
        ]">Big Simba <span class="text-safari-gold">Safaris</span></p>
      </div>
  </div>
</template>
