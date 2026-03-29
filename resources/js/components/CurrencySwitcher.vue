<script setup lang="ts">
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Globe } from 'lucide-vue-next';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';

const page = usePage<any>();
const currentCurrency = computed(() => page.props.currency?.current || 'KES');

const currencies = [
  { code: 'KES', name: 'Kenyan Shilling', symbol: 'Ksh' },
  { code: 'USD', name: 'US Dollar', symbol: '$' },
  { code: 'EUR', name: 'Euro', symbol: '€' },
  { code: 'GBP', name: 'British Pound', symbol: '£' },
];
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button 
        variant="ghost" 
        class="h-7 md:h-8 px-2 md:px-3 text-[10px] md:text-xs font-black uppercase tracking-[0.1em] md:tracking-[0.2em] border border-white/5 bg-white/[0.02] hover:bg-safari-gold hover:text-black transition-all rounded-full flex items-center gap-1.5 md:gap-2 group overflow-hidden"
      >
        <Globe class="w-2.5 h-2.5 md:w-3 md:h-3 opacity-40 group-hover:opacity-100 transition-opacity" />
        <span class="opacity-90 group-hover:opacity-100">{{ currentCurrency }}</span>
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="w-48 bg-black/95 backdrop-blur-3xl border-white/10 text-white shadow-2xl p-2">
      <DropdownMenuItem v-for="currency in currencies" :key="currency.code" as-child>
        <Link 
          :href="route('currency.switch', { code: currency.code })" 
          class="flex items-center justify-between px-4 py-3 rounded-sm text-xs font-black uppercase tracking-widest hover:bg-safari-gold hover:text-black transition-all cursor-pointer group"
          :class="{ 'text-safari-gold': currentCurrency === currency.code }"
        >
          <span>{{ currency.name }}</span>
          <span class="opacity-40 group-hover:opacity-100">{{ currency.symbol }}</span>
        </Link>
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
