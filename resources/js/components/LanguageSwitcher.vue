<script setup lang="ts">
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Languages } from 'lucide-vue-next';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';

const page = usePage<any>();
const currentLocale = computed(() => page.props.locale?.current || 'en');

const languages = [
  { code: 'en', name: 'English', native: 'English' },
  { code: 'es', name: 'Spanish', native: 'Español' },
  { code: 'fr', name: 'French', native: 'Français' },
  { code: 'de', name: 'German', native: 'Deutsch' },
  { code: 'it', name: 'Italian', native: 'Italiano' },
];

const currentLanguage = computed(() => languages.find(l => l.code === currentLocale.value) || languages[0]);
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" class="h-9 px-3 text-sm font-black uppercase tracking-[0.2em] border border-white/5 bg-white/[0.02] hover:bg-safari-gold hover:text-black transition-all rounded-full flex items-center gap-2 group">
        <Languages class="w-3 h-3 opacity-40 group-hover:opacity-100" />
        {{ currentLocale.toUpperCase() }}
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="w-48 bg-black/95 backdrop-blur-3xl border-white/10 text-white shadow-2xl p-2">
      <DropdownMenuItem v-for="lang in languages" :key="lang.code" as-child>
        <Link 
          :href="route('language::back', { locale: lang.code })" 
          class="flex items-center justify-between px-4 py-3 rounded-sm text-xs font-black uppercase tracking-widest hover:bg-safari-gold hover:text-black transition-all cursor-pointer group"
          :class="{ 'text-safari-gold': currentLocale === lang.code }"
        >
          <span>{{ lang.native }}</span>
          <span class="opacity-40 group-hover:opacity-100 uppercase">{{ lang.code }}</span>
        </Link>
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
