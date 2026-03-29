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
          'rounded-full overflow-hidden transition-all duration-500 group-hover:scale-110 shadow-lg border border-white/10 shrink-0 aspect-square',
          size === 'lg' ? 'h-20 w-20' : size === 'md' ? 'h-14 w-14' : 'h-10 w-10'
        ]"
      >
        <img src="/images/logo.png" alt="Big Simba Safaris" class="w-full h-full object-cover" />
      </div>
      <div v-if="showText !== false">
        <p :class="[
          'font-black uppercase tracking-[0.4em] opacity-90 text-white leading-tight',
          size === 'lg' ? 'text-xl' : size === 'md' ? 'text-base' : 'text-[10px]'
        ]">Big Simba <span class="text-safari-gold">Safaris</span></p>
      </div>
  </div>
</template>
