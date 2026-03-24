<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { MapPin, ChevronRight, Compass, Globe, ArrowRight } from 'lucide-vue-next';
import { computed } from 'vue';

defineProps<{
    destinations: any[];
}>();

const page = usePage<any>();
const layout = computed(() => page.url.startsWith('/dashboard') ? AppHeaderLayout : GuestLayout);
</script>

<template>
    <Head title="Destinations - Big Simba Safaris" />

    <component :is="layout" :full-bleed="true">
        <main class="py-44 px-8 md:px-20 max-w-[1600px] mx-auto">
            <header class="mb-32 space-y-12">
                <div class="space-y-4">
                     <p class="text-[10px] font-black tracking-[0.5em] uppercase text-safari-gold mb-4">- Explore Kenya's Soul //</p>
                     <h1 class="text-6xl md:text-[8rem] font-black uppercase tracking-tighter leading-[0.85]">The Sacred <br/> <span class="text-safari-gold/20">Grounds.</span></h1>
                </div>
                <p class="mt-10 text-xl md:text-2xl font-light opacity-50 max-w-2xl leading-relaxed italic">"From the vast savanna of the Maasai Mara to the turquoise waters of the Indian Ocean, discover the diverse beauty of Kenya through our curated lens."</p>
            </header>

            <!-- Destination Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <Link 
                    v-for="dest in destinations" 
                    :key="dest.id"
                    :href="route(page.url.startsWith('/dashboard') ? 'user.destinations.show' : 'destinations.show', dest.slug)"
                    class="group relative aspect-[16/10] overflow-hidden rounded-sm border border-white/5 shadow-2xl bg-white/5"
                >
                    <img 
                        :src="dest.image" 
                        :alt="dest.name"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-[2000ms] group-hover:scale-110 grayscale-[0.4] group-hover:grayscale-0 opacity-70"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80"></div>
                    
                    <div class="absolute inset-x-0 bottom-0 p-16 space-y-8">
                        <div class="flex items-end justify-between border-b border-white/10 pb-10">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3 text-safari-gold">
                                     <MapPin class="w-4 h-4" />
                                     <span class="text-[10px] font-black uppercase tracking-widest">{{ dest.best_time_to_visit || 'Year Round' }}</span>
                                </div>
                                <h3 class="text-4xl md:text-5xl font-black uppercase tracking-tighter group-hover:text-safari-gold transition-colors leading-none">{{ dest.name }}</h3>
                            </div>
                            <div class="h-16 w-16 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-safari-gold group-hover:text-black group-hover:border-safari-gold transition-all duration-500 shadow-2xl bg-black/40 backdrop-blur-md">
                                <ArrowRight class="w-6 h-6 group-hover:translate-x-1 transition-transform" />
                            </div>
                        </div>
                        <p class="text-xs font-light opacity-40 uppercase tracking-widest line-clamp-1 italic group-hover:opacity-100 transition-opacity">Discover the essence of {{ dest.name }} and its wild inhabitants.</p>
                    </div>
                </Link>
            </div>

            <!-- Philosophy Callout -->
            <section class="mt-44 grid grid-cols-1 md:grid-cols-3 gap-16 py-32 border-y border-white/5">
                 <div class="flex gap-8 group">
                      <div class="h-14 w-14 rounded-full border border-white/5 flex items-center justify-center bg-white/[0.02] group-hover:border-safari-gold/40 transition-all">
                           <Globe class="w-6 h-6 text-safari-gold opacity-40 group-hover:opacity-100 transition-opacity" />
                      </div>
                      <div class="space-y-4">
                           <h4 class="text-lg font-black uppercase tracking-tight">Diverse Ecosystems</h4>
                           <p class="text-xs font-light opacity-40 leading-relaxed uppercase tracking-widest italic">From alpine peaks to coastal reefs, experience the full spectrum of nature.</p>
                      </div>
                 </div>
                 <div class="flex gap-8 group">
                      <div class="h-14 w-14 rounded-full border border-white/5 flex items-center justify-center bg-white/[0.02] group-hover:border-safari-gold/40 transition-all">
                           <Compass class="w-6 h-6 text-safari-gold opacity-40 group-hover:opacity-100 transition-opacity" />
                      </div>
                      <div class="space-y-4">
                           <h4 class="text-lg font-black uppercase tracking-tight">Expert Navigation</h4>
                           <p class="text-xs font-light opacity-40 leading-relaxed uppercase tracking-widest italic">Guided by local mastery, we unlock the secrets of every terrain.</p>
                      </div>
                 </div>
                 <div class="flex gap-8 group">
                      <div class="h-14 w-14 rounded-full border border-white/5 flex items-center justify-center bg-white/[0.02] group-hover:border-safari-gold/40 transition-all">
                           <MapPin class="w-6 h-6 text-safari-gold opacity-40 group-hover:opacity-100 transition-opacity" />
                      </div>
                      <div class="space-y-4">
                           <h4 class="text-lg font-black uppercase tracking-tight">Sacred Locales</h4>
                           <p class="text-xs font-light opacity-40 leading-relaxed uppercase tracking-widest italic">We venture where others don't, respecting the sanctity of the wild.</p>
                      </div>
                 </div>
            </section>
        </main>
    </component>
</template>
