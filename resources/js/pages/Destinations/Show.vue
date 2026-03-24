<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Clock, MapPin, ChevronRight, CheckCircle2, ChevronLeft, ArrowRight, Globe, Compass } from 'lucide-vue-next';
import { computed } from 'vue';

defineProps<{
    destination: any;
}>();

const page = usePage<any>();
const layout = computed(() => page.url.startsWith('/dashboard') ? AppHeaderLayout : GuestLayout);

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Head :title="`${destination.name} - Big Simba Safaris`" />

    <component :is="layout" :full-bleed="true">
        <header class="relative h-[80vh] w-full overflow-hidden z-40 rounded-b-[6rem] -mb-32 shadow-[0_30px_100px_rgba(0,0,0,0.8)] border-b border-white/5 bg-black">
             <img 
                :src="destination.image" 
                :alt="destination.name"
                class="absolute inset-0 w-full h-full object-cover brightness-[0.3] grayscale-[0.2] scale-105"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
            
            <div class="relative h-full container max-w-[1600px] mx-auto px-8 md:px-20 flex flex-col items-center justify-center text-center pt-24">
                 <div class="animate-in fade-in slide-in-from-bottom-10 duration-[1500ms]">
                      <p class="text-[12px] md:text-[14px] font-black tracking-[0.5em] uppercase text-safari-gold mb-8 drop-shadow-[0_2px_15px_rgba(202,138,4,0.5)]">- Sacred Ground Registry //</p>
                      <h1 class="text-6xl md:text-[9.5rem] font-black uppercase tracking-tighter leading-[0.85] mb-8 drop-shadow-2xl">{{ destination.name }}</h1>
                      <div class="flex items-center justify-center gap-10 text-[11px] font-black uppercase tracking-[0.4em] opacity-40">
                           <div class="flex items-center gap-3">
                                <MapPin class="w-4 h-4 text-safari-gold" /> {{ destination.best_time_to_visit || 'Year Round' }}
                           </div>
                           <div class="h-1 w-1 rounded-full bg-safari-gold opacity-30"></div>
                           <div class="flex items-center gap-3">
                                <Globe class="w-4 h-4 text-safari-gold" /> Kenya Highlands
                           </div>
                      </div>
                 </div>
            </div>

            <div class="absolute bottom-20 left-1/2 -translate-x-1/2 flex items-center gap-3 text-[9px] font-black uppercase tracking-[0.5em] opacity-20 pointer-events-none">
                 <div class="w-12 h-[1px] bg-white"></div>
                 Experience Essence
                 <div class="w-12 h-[1px] bg-white"></div>
            </div>
        </header>

        <main class="py-44 container max-w-[1600px] mx-auto px-8 md:px-20">
             <!-- Functional Back Link -->
             <button 
                @click="goBack" 
                class="inline-flex items-center gap-4 text-[11px] font-black uppercase tracking-[0.4em] text-safari-gold mb-24 hover:text-white transition-all group border-b border-safari-gold/20 pb-2"
            >
                <ChevronLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
                Return to Directory
            </button>

             <div class="grid grid-cols-1 lg:grid-cols-3 gap-32">
                  <div class="lg:col-span-2 space-y-32">
                      <section class="space-y-12">
                          <div class="flex items-center gap-8">
                               <h2 class="text-xs font-black uppercase tracking-[0.6em] text-safari-gold whitespace-nowrap">The Landscape //</h2>
                               <div class="h-[1px] w-full bg-white/5"></div>
                          </div>
                          <p class="text-2xl md:text-4xl font-light leading-relaxed opacity-60 italic drop-shadow-sm">{{ destination.overview }}</p>
                      </section>

                      <section class="grid grid-cols-1 md:grid-cols-2 gap-20 py-24 border-y border-white/5">
                           <div class="space-y-12">
                                <div class="space-y-2">
                                     <h3 class="text-[11px] font-black uppercase tracking-[0.5em] text-safari-gold">Top Experiences</h3>
                                     <p class="text-[9px] opacity-20 uppercase tracking-widest font-bold italic">Curated by Big Simba Masters</p>
                                </div>
                                <ul class="space-y-8">
                                    <li v-for="item in destination.things_to_do" :key="item" class="flex items-center gap-6 group">
                                        <div class="h-10 w-10 bg-white/[0.02] border border-white/5 flex items-center justify-center rounded-full group-hover:border-safari-gold/40 transition-all duration-500">
                                             <CheckCircle2 class="w-4 h-4 text-safari-gold" />
                                        </div>
                                        <span class="text-xs font-black uppercase tracking-[0.3em] opacity-60 group-hover:opacity-100 transition-opacity">{{ item }}</span>
                                    </li>
                                </ul>
                           </div>
                           <div class="space-y-12">
                                 <div class="space-y-2">
                                     <h3 class="text-[11px] font-black uppercase tracking-[0.5em] opacity-40">Climate & Timing</h3>
                                     <p class="text-[9px] opacity-20 uppercase tracking-widest font-bold italic">Optimized Visiting Windows</p>
                                </div>
                                <div class="p-10 bg-white/[0.02] border border-white/5 rounded-sm space-y-6">
                                     <Compass class="w-10 h-10 text-safari-gold opacity-20" />
                                     <p class="text-sm font-black text-white uppercase tracking-widest border-l border-safari-gold/40 pl-6 py-2">
                                        {{ destination.best_time_to_visit }}
                                     </p>
                                     <p class="text-[10px] opacity-30 leading-relaxed uppercase tracking-widest italic font-light">The savanna's temperament varies. Plan your expedition to align with the rhythms of migration and seasonal rainfall for the most profound experience.</p>
                                </div>
                           </div>
                      </section>
                  </div>

                  <!-- Tours in this destination -->
                  <div class="lg:col-span-1">
                       <div class="bg-card border border-white/5 rounded-sm p-12 space-y-12 h-fit shadow-2xl relative overflow-hidden group">
                            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-safari-gold/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-[2000ms]"></div>
                            
                            <div class="space-y-2 relative z-10 border-b border-white/5 pb-8">
                                 <h3 class="text-[11px] font-black uppercase tracking-[0.5em] mb-4 text-safari-gold opacity-60">Signature Safaris</h3>
                                 <p class="text-[9px] opacity-20 uppercase tracking-widest font-bold italic">Available Expeditions In This Region</p>
                            </div>

                            <div class="space-y-10 relative z-10">
                                 <Link 
                                    v-for="tour in destination.tours" 
                                    :key="tour.id"
                                    :href="route(page.url.startsWith('/dashboard') ? 'user.tours.show' : 'tours.show', tour.slug)"
                                    class="flex flex-col gap-6 group/item"
                                >
                                    <div class="aspect-[16/7] rounded-sm overflow-hidden border border-white/5 grayscale group-hover/item:grayscale-0 transition-all duration-1000 shadow-xl relative bg-black/40">
                                         <img 
                                            :src="tour.images[0]" 
                                            :alt="tour.name"
                                            class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-[2000ms]"
                                        />
                                        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-lg font-black uppercase tracking-tight group-hover/item:text-safari-gold transition-colors leading-tight">{{ tour.name }}</p>
                                        <div class="flex items-center justify-between pt-2">
                                             <p class="text-[9px] opacity-40 uppercase tracking-widest font-bold italic">{{ tour.duration }} &bull; STARTING FROM ${{ parseFloat(tour.price).toLocaleString() }}</p>
                                             <ArrowRight class="w-3 h-3 text-safari-gold opacity-0 group-hover/item:opacity-100 group-hover/item:translate-x-1 transition-all" />
                                        </div>
                                    </div>
                                 </Link>
                                 <div v-if="!destination.tours?.length" class="text-[10px] opacity-20 uppercase tracking-widest text-center py-16 border border-dashed border-white/10 italic">
                                     No active expeditions registered <br/> for this sacred ground.
                                 </div>
                            </div>

                            <div class="pt-12 border-t border-white/5 relative z-10">
                                 <Link :href="route(page.url.startsWith('/dashboard') ? 'user.tours.index' : 'tours.index')" class="w-full h-16 border border-white/10 hover:border-safari-gold hover:text-safari-gold flex items-center justify-center text-[10px] font-black uppercase tracking-[0.4em] transition-all">
                                      View All Global Expeditions
                                 </Link>
                            </div>
                       </div>
                  </div>
             </div>
        </main>
    </component>
</template>
