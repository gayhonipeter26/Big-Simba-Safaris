<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { MapPin, ChevronRight, Compass, Globe, ArrowRight, ArrowLeft } from 'lucide-vue-next';
import { computed } from 'vue';

defineProps<{
    destinations: any[];
}>();

const page = usePage<any>();
const layout = computed(() => (page.props.auth?.user || page.url.startsWith('/dashboard')) ? AppHeaderLayout : GuestLayout);
</script>

<template>
    <Head title="Sacred Grounds - Explore Kenya's Most Elite Destinations">
        <meta name="description" content="Discover Kenya's most iconic and tactical safari locales. From the vast Maasai Mara to the peaks of Mt. Kenya, explore the sacred grounds of Big Simba Safaris." />
        <meta property="og:title" content="Sacred Grounds - Big Simba Safaris Destinations" />
        <meta property="og:description" content="Venture into the heart of the wild. Explore our curated collection of elite Kenyan safari destinations." />
        <meta name="keywords" content="Kenya Safari Destinations, Maasai Mara, Amboseli, Tsavo, Samburu, Big Simba Safaris Locales" />
        <component :is="'script'" type="application/ld+json" v-html="JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'ItemList',
          'itemListElement': destinations.map((d, i) => ({
            '@type': 'ListItem',
            'position': i + 1,
            'name': d.name,
            'url': route('destinations.show', d.slug)
          }))
        })"></component>
    </Head>

    <component :is="layout" :full-bleed="true">
        <main class="py-12 md:py-44 px-4 md:px-20 max-w-[1600px] mx-auto min-h-screen bg-[#050505]">
            <header class="mb-16 md:mb-32 space-y-8 md:space-y-12 pt-24 lg:pt-0 px-2 md:px-0">
                <div class="space-y-3 md:space-y-4">
                     <Link :href="page.props.auth?.user ? route('dashboard') : route('home')" class="inline-flex items-center gap-2 text-[10px] md:text-xs font-black uppercase tracking-[0.2em] text-safari-gold hover:text-white transition-all mb-2 md:mb-4 group/back" aria-label="Return to Command Center">
                          <ArrowLeft class="w-3 h-3 group-hover/back:-translate-x-1.5 transition-transform" aria-hidden="true" /> {{ page.props.auth?.user ? 'Back to Dashboard' : 'Back to Welcome' }}
                     </Link>
                     <p class="text-[9px] md:text-sm font-black tracking-[0.5em] uppercase text-safari-gold mb-2 md:mb-4 opacity-80">- EXPLORE KENYA'S SOUL //</p>
                     <h1 class="font-display text-4xl md:text-[8rem] font-black uppercase tracking-tighter leading-[0.85] text-white">The Sacred <br/> <span class="text-safari-gold/20">Grounds.</span></h1>
                </div>
                <p class="mt-6 md:mt-10 text-lg md:text-2xl font-light opacity-90 max-w-2xl leading-relaxed italic text-white/70">"From the vast savanna of the Maasai Mara to the turquoise waters of the Indian Ocean, discover the diverse beauty of Kenya through our curated lens."</p>
            </header>

            <!-- Destination Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <Link 
                    v-for="dest in destinations" 
                    :key="dest.id"
                    :href="route(page.url.startsWith('/dashboard') ? 'user.destinations.show' : 'destinations.show', dest.slug)"
                    class="group relative aspect-[16/10] overflow-hidden rounded-sm border border-white/5 shadow-2xl bg-white/5"
                    :aria-label="`In-depth reconnaissance: ${dest.name}`"
                >
                    <img 
                        :src="dest.image" 
                        :alt="dest.name"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-[2000ms] group-hover:scale-110 grayscale-[0.4] group-hover:grayscale-0 opacity-70"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80"></div>
                    
                    <div class="absolute inset-x-0 bottom-0 p-8 md:p-16 space-y-8">
                        <div class="flex items-end justify-between border-b border-white/10 pb-10">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3 text-safari-gold">
                                     <MapPin class="w-4 h-4" aria-hidden="true" />
                                     <span class="text-sm font-black uppercase tracking-widest">{{ dest.best_time_to_visit || 'Year Round' }}</span>
                                </div>
                                <h3 class="font-display text-4xl md:text-5xl font-black uppercase tracking-tighter group-hover:text-safari-gold transition-colors leading-none text-white">{{ dest.name }}</h3>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-safari-gold group-hover:text-white transition-all">
                                Details <ArrowRight class="w-3 h-3" aria-hidden="true" />
                            </div>
                        </div>
                        <p class="text-xs font-light opacity-80 uppercase tracking-widest line-clamp-1 italic group-hover:opacity-100 transition-opacity">Discover the essence of {{ dest.name }} and its wild inhabitants.</p>
                    </div>
                </Link>
            </div>

            <!-- Philosophy Callout -->
            <section class="mt-44 grid grid-cols-1 md:grid-cols-3 gap-16 py-32 border-y border-white/5">
                  <div class="flex gap-8 group">
                       <div class="h-14 w-14 rounded-full border border-white/5 flex items-center justify-center bg-white/[0.02] group-hover:border-safari-gold/40 transition-all">
                            <Globe class="w-6 h-6 text-safari-gold opacity-80 group-hover:opacity-100 transition-opacity" aria-hidden="true" />
                       </div>
                       <div class="space-y-4">
                            <h4 class="font-display text-lg font-black uppercase tracking-tight text-white">Diverse Ecosystems</h4>
                            <p class="text-xs font-normal text-gray-300 leading-relaxed uppercase tracking-widest italic">From alpine peaks to coastal reefs, experience the full spectrum of nature.</p>
                       </div>
                  </div>
                  <div class="flex gap-8 group">
                       <div class="h-14 w-14 rounded-full border border-white/5 flex items-center justify-center bg-white/[0.02] group-hover:border-safari-gold/40 transition-all">
                            <Compass class="w-6 h-6 text-safari-gold opacity-80 group-hover:opacity-100 transition-opacity" aria-hidden="true" />
                       </div>
                       <div class="space-y-4">
                            <h4 class="font-display text-lg font-black uppercase tracking-tight text-white">Expert Navigation</h4>
                            <p class="text-xs font-normal text-gray-300 leading-relaxed uppercase tracking-widest italic">Guided by local mastery, we unlock the secrets of every terrain.</p>
                       </div>
                  </div>
                  <div class="flex gap-8 group">
                       <div class="h-14 w-14 rounded-full border border-white/5 flex items-center justify-center bg-white/[0.02] group-hover:border-safari-gold/40 transition-all">
                            <MapPin class="w-6 h-6 text-safari-gold opacity-80 group-hover:opacity-100 transition-opacity" aria-hidden="true" />
                       </div>
                      <div class="space-y-4">
                           <h4 class="font-display text-lg font-black uppercase tracking-tight text-white">Sacred Locales</h4>
                           <p class="text-xs font-normal text-gray-300 leading-relaxed uppercase tracking-widest italic">We venture where others don't, respecting the sanctity of the wild.</p>
                      </div>
                 </div>
            </section>
        </main>
    </component>
</template>
