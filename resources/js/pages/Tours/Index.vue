<script setup lang="ts">
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Clock, MapPin, Search, ArrowRight, ArrowUpRight, ArrowLeft, Users, Gauge, Zap, UserCheck, Target, X, ShieldCheck, ChevronLeft, ChevronRight, Phone, Lock, CreditCard } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { useToast } from '@/composables/useToast';
import { useCurrency } from '@/composables/useCurrency';

const props = defineProps<{
    tours: any[];
    fleet: any[];
    filters: any;
    services: any[];
}>();

const page = usePage<any>();
const auth = computed(() => page.props.auth);
const layout = computed(() => (page.props.auth?.user || page.url.startsWith('/dashboard')) ? AppHeaderLayout : GuestLayout);

const searchQuery = ref(props.filters?.search || '');
const isHighlightActive = ref(props.filters?.highlight === 'true');

const { formatPrice } = useCurrency();

const filteredTours = computed(() => {
    if (!searchQuery.value) return props.tours;
    return props.tours.filter(tour =>
        tour.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        tour.destination?.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// ── Tour booking modal ──────────────────────────────────────────────────────
const selectedTour = ref<any>(null);
const modalStep = ref(1);

// Fleet carousel state
const fleetIndex = ref(0);
const selectedFleetId = ref<string | null>(null);

const currentFleet = computed(() => props.fleet[fleetIndex.value] ?? null);
const fleetPrev = () => { fleetIndex.value = (fleetIndex.value - 1 + props.fleet.length) % props.fleet.length; };
const fleetNext = () => { fleetIndex.value = (fleetIndex.value + 1) % props.fleet.length; };
const toggleFleet = (id: string) => { selectedFleetId.value = selectedFleetId.value === id ? null : id; };

// Bottom fleet carousel state
const bottomFleetIndex = ref(0);
const visibleCount = 3;
const bottomFleetPrev = () => { bottomFleetIndex.value = Math.max(0, bottomFleetIndex.value - 1); };
const bottomFleetNext = () => { bottomFleetIndex.value = Math.min(props.fleet.length - visibleCount, bottomFleetIndex.value + 1); };
const visibleFleet = computed(() => props.fleet.slice(bottomFleetIndex.value, bottomFleetIndex.value + visibleCount));
const canGoPrev = computed(() => bottomFleetIndex.value > 0);
const canGoNext = computed(() => bottomFleetIndex.value + visibleCount < props.fleet.length);

const form = useForm({
    tour_id: '',
    fleet_id: '',
    name: (page.props as any).auth?.user?.name || '',
    email: (page.props as any).auth?.user?.email || '',
    phone: '',
    start_date: '',
    end_date: '',
    guests: '1',
    message: '',
    payment_method: 'mpesa',
    fleet_pricing_model: '',
});

const { show: showToast } = useToast();

const calculatedDeposit = computed(() => {
    let tourDeposit = (selectedTour.value ? parseFloat(selectedTour.value.price) : 0) * (parseInt(form.guests) || 1) * 0.1;
    let fleetDeposit = 0;
    if (selectedFleetId.value) {
        const sf = props.fleet.find(f => f.id === selectedFleetId.value);
        if (sf && form.fleet_pricing_model) {
            if (form.fleet_pricing_model === 'hourly') fleetDeposit += parseFloat(sf.price_per_hour);
            if (form.fleet_pricing_model === 'daily') fleetDeposit += parseFloat(sf.price_per_day);
            if (form.fleet_pricing_model === 'safari') fleetDeposit += parseFloat(sf.price_per_safari_drive);
        }
    }
    return tourDeposit + fleetDeposit;
});

const openModal = (tour: any) => {
    selectedTour.value = tour;
    form.tour_id = tour.id;
    selectedFleetId.value = null;
    fleetIndex.value = 0;
    modalStep.value = 1;
};

const closeModal = () => {
    selectedTour.value = null;
    form.reset();
    modalStep.value = 1;
};

const submit = () => {
    form.fleet_id = selectedFleetId.value || '';
    form.post(route('tour-enquiries.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            showToast(
                'Expedition request submitted! You will receive a confirmation email once approved by our ops team.',
                'success',
                7000
            );
        },
    });
};

const handleEnquireCheckout = (service: any) => {
    if (!page.props.auth?.user) {
        showToast('Authentication Required: Please login or register to checkout services.', 'error', 5000);
        return;
    }
    router.get(route('services.index'), { service: service.id });
};
</script>

<template>
    <Head title="Expedition Registry - Premium Kenyan Safaris">
        <meta name="description" content="Explore the full registry of Big Simba Safaris expeditions. From Maasai Mara migrations to Amboseli elephant treks, browse our elite 4x4 safari packages." />
        <meta property="og:title" content="Expedition Registry - Big Simba Safaris" />
        <meta property="og:description" content="Select your legend from our curated collection of Kenyan safari expeditions. Tactical 4x4 mobility and expert scouting included." />
        <meta name="keywords" content="Kenya Safari Packages, Maasai Mara Tours, Amboseli Safari, Big Simba Expeditions, African Wildlife Tours" />
        <component :is="'script'" type="application/ld+json" v-html="JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'ItemList',
          'itemListElement': tours.map((t, i) => ({
            '@type': 'ListItem',
            'position': i + 1,
            'name': t.name,
            'url': route('tours.show', t.slug)
          }))
        })"></component>
    </Head>

    <component :is="layout" :full-bleed="true">
        <div class="bg-[#050505] min-h-screen">
            <!-- Cinematic Expedition Hero -->
            <div class="relative h-[40vh] md:h-[55vh] w-full overflow-hidden bg-black z-10 rounded-b-[2rem] md:rounded-b-[4rem] shadow-[0_30px_100px_rgba(0,0,0,0.8)] border-b border-white/5">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2000" class="h-full w-full object-cover scale-110 opacity-60 brightness-75 transition-all duration-[10000ms]" alt="Safari Reconnaissance" />
                    <div class="absolute inset-x-0 bottom-0 h-full bg-gradient-to-t from-[#050505] via-[#050505]/20 to-transparent"></div>
                </div>
                
                <div class="relative h-full flex flex-col items-center justify-center px-6 md:px-20 pt-16 text-center">
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-1000 max-w-4xl">
                         <Link :href="page.props.auth?.user ? route('dashboard') : route('home')" class="inline-flex items-center gap-2 text-[9px] md:text-xs font-black uppercase tracking-[0.4em] text-safari-gold hover:text-white transition-all mb-8 group/back" aria-label="Return to Command Center">
                              <ArrowLeft class="w-3 h-3 group-hover/back:-translate-x-2 transition-transform" /> {{ page.props.auth?.user ? 'Command Center' : 'Return Home' }}
                         </Link>
                         <p class="text-[9px] md:text-[11px] font-black tracking-[0.6em] uppercase text-safari-gold/80 mb-4 tracking-[0.8em]">// REGISTRY PORTAL : ALPHA</p>
                         <h1 class="font-display text-4xl md:text-8xl font-black uppercase tracking-tighter leading-none text-white mb-6">Expedition <span class="text-safari-gold">Registry.</span></h1>
                         <p class="text-[10px] md:text-base font-light italic opacity-70 text-white/80 max-w-2xl mx-auto border-x border-safari-gold/20 px-6">
                            "The savanna does not wait. Choose your legend and deploy."
                         </p>
                    </div>
                </div>
            </div>

            <main class="max-w-[1850px] mx-auto px-6 md:px-20 py-20 md:py-44 -mt-8 md:-mt-12 relative z-20">
                <!-- Tactical Mission Bar -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-12 md:gap-20 mb-20 md:mb-32">
                    <div class="space-y-4 md:space-y-6 flex-1">
                         <div class="flex items-center gap-4">
                              <div class="w-10 h-[2px] bg-safari-gold"></div>
                              <p class="text-[10px] md:text-xs font-black tracking-[0.5em] uppercase text-safari-gold">SAFARI COLLECTION 2026</p>
                         </div>
                         <h2 class="font-display text-3xl md:text-5xl font-black uppercase tracking-tighter text-white">Select Your Legend.</h2>
                    </div>
                    
                    <div class="relative w-full md:w-[450px] group">
                         <Search class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-safari-gold opacity-50 group-focus-within:opacity-100 transition-opacity" />
                         <input 
                              v-model="searchQuery"
                              type="text" 
                              placeholder="SEARCH TACTICAL SECTORS..." 
                              class="w-full bg-[#111] border border-white/5 rounded-sm pl-16 pr-8 py-5 md:py-6 text-[10px] md:text-xs font-black uppercase tracking-[0.3em] focus:border-safari-gold transition-all outline-none text-white placeholder:opacity-20 shadow-2xl" 
                         />
                    </div>
                </div>

            <!-- Tour Grid -->
            <div v-if="filteredTours.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-16 md:gap-y-24">
                <div 
                    v-for="tour in filteredTours" 
                    :key="tour.id"
                    class="group relative space-y-6 transition-all duration-700"
                    :class="{'mission-highlight': isHighlightActive && searchQuery && tour.destination?.name.toLowerCase().includes(searchQuery.toLowerCase())}"
                >
                    <Link :href="route(page.url.startsWith('/dashboard') ? 'user.tours.show' : 'tours.show', tour.slug)" class="block" :aria-label="`View details for ${tour.name}`">
                         <div class="aspect-[4/3.8] md:aspect-[4/4.8] overflow-hidden rounded-sm border border-white/5 relative bg-white/5 shadow-2xl">
                              <img 
                                   :src="tour.images?.[0] || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072'" 
                                   :alt="tour.name"
                                   class="absolute inset-0 h-full w-full object-cover grayscale-[0.3] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-1000"
                                   loading="lazy"
                              />
                              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent opacity-90"></div>
                              
                              <div class="absolute top-8 right-8 bg-black/60 backdrop-blur-md border border-white/10 px-5 py-2.5 rounded-sm">
                                   <p class="text-sm font-black text-safari-gold tracking-[0.2em]">{{ formatPrice(tour.price) }}</p>
                              </div>
                         </div>
                    </Link>
                    
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                             <div class="flex items-center gap-3 text-safari-gold/60">
                                  <MapPin class="w-3 h-3" aria-hidden="true" />
                                  <span class="text-[11px] md:text-sm font-black tracking-[0.3em] uppercase">{{ tour.destination?.name || 'KENYA' }}</span>
                             </div>
                             <div class="flex items-center gap-2 text-[11px] md:text-sm font-bold opacity-70 uppercase tracking-[0.3em] text-white">
                                  <Clock class="w-3 h-3" aria-hidden="true" /> {{ tour.duration }}
                             </div>
                        </div>
                        
                        <Link :href="route(page.url.startsWith('/dashboard') ? 'user.tours.show' : 'tours.show', tour.slug)" class="group/title block" :aria-label="`View details for ${tour.name}`">
                             <h3 class="font-display text-2xl md:text-3xl font-black uppercase tracking-tight group-hover/title:text-safari-gold transition-colors underline decoration-transparent group-hover/title:decoration-safari-gold underline-offset-8 decoration-2 text-white">
                                  {{ tour.name }}
                             </h3>
                        </Link>
                        
                        <p class="text-[11px] font-normal text-gray-300 leading-relaxed line-clamp-2 italic text-white/80">
                             {{ tour.description }}
                        </p>

                        <div class="pt-6 flex items-center justify-between border-t border-white/5">
                             <Link :href="route(page.url.startsWith('/dashboard') ? 'user.tours.show' : 'tours.show', tour.slug)" class="text-[11px] md:text-sm font-black uppercase tracking-widest flex items-center gap-3 group/btn text-white/60 hover:text-safari-gold transition-colors" aria-label="View Intelligence">
                                  View Intel <ArrowUpRight class="w-3 h-3 group-hover/btn:-translate-y-0.5 group-hover/btn:translate-x-0.5 transition-transform" aria-hidden="true" />
                             </Link>
                             <!-- Reserve Button -->
                             <button @click="openModal(tour)" class="text-[10px] md:text-xs font-black uppercase tracking-[0.3em] text-black bg-safari-gold px-4 py-2 hover:bg-white transition-all">
                                  Reserve
                             </button>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Strategic Operations Terminal (Services Acquisition) -->
             <section v-if="auth.user && services && services.length > 0" class="mt-24 md:mt-44 pt-24 md:pt-44 border-t border-white/5 relative overflow-hidden">
                 <div class="absolute -left-20 top-20 text-[10rem] md:text-[15rem] font-black opacity-[0.01] pointer-events-none select-none uppercase tracking-tighter">Mission Ops</div>
                 
                 <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 md:gap-8 mb-16 md:mb-24 px-2 md:px-0">
                     <div class="space-y-3 md:space-y-4">
                          <p class="text-[9px] md:text-xs font-black tracking-[0.5em] uppercase text-safari-gold opacity-90">- STRATEGIC OPERATIONS TERMINAL //</p>
                          <h2 class="font-display text-3xl md:text-5xl font-black uppercase tracking-tighter text-white">Extended Support Services.</h2>
                     </div>
                 </div>

                 <!-- Support Services Sub-Section -->
                 <div class="mb-32">
                      <div class="flex items-center gap-4 mb-16 px-4 py-2 bg-white/[0.02] border-l-2 border-safari-gold w-fit">
                           <span class="text-xs font-black uppercase tracking-[0.4em] text-white/60">Operational Support Requisition</span>
                      </div>
                      <div class="flex overflow-x-auto md:grid md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10 relative z-10 no-scrollbar snap-x snap-mandatory px-4 md:px-0">
                          <div v-for="service in services" :key="service.id" class="flex-shrink-0 w-[280px] xs:w-[320px] md:w-auto snap-start group bg-white/[0.02] border border-white/5 hover:border-safari-gold/20 transition-all duration-700 flex flex-col shadow-2xl rounded-sm overflow-hidden">
                               <div class="relative h-56 md:h-64 w-full shrink-0 overflow-hidden">
                                    <img :src="service.image" :alt="service.name" class="h-full w-full object-cover transition-transform duration-[2000ms] group-hover:scale-[1.10]" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                                    <div class="absolute bottom-4 left-4 z-10 flex items-center gap-3">
                                         <div class="w-10 h-10 bg-black/50 backdrop-blur-md border border-safari-gold/20 flex items-center justify-center rounded-sm transition-transform duration-500 group-hover:scale-110">
                                              <Zap class="w-4 h-4 text-safari-gold" aria-hidden="true" />
                                         </div>
                                         <h3 class="font-display text-base md:text-lg font-black uppercase tracking-tight text-white group-hover:text-safari-gold transition-colors line-clamp-1">{{ service.name }}</h3>
                                    </div>
                               </div>
                               
                               <div class="p-6 md:p-8 flex flex-col flex-grow">
                                    <p class="text-[11px] md:text-xs font-normal text-gray-400 leading-relaxed line-clamp-3 mb-6 md:mb-8 italic uppercase tracking-widest opacity-80">{{ service.description }}</p>

                                    <div class="space-y-5 md:space-y-6 pt-5 md:pt-6 border-t border-white/5 mt-auto">
                                         <div class="flex items-end justify-between">
                                              <div class="space-y-1">
                                                   <div class="flex items-center gap-2">
                                                        <Target class="w-2 h-2 text-safari-gold opacity-80" aria-hidden="true" />
                                                        <span class="text-[9px] md:text-[10px] font-black uppercase tracking-widest opacity-80 text-white">{{ service.pricing_tier || 'Base Rate' }}</span>
                                                   </div>
                                                   <div class="text-lg md:text-xl font-black text-safari-gold uppercase tracking-tighter">{{ formatPrice(service.starting_price) }}</div>
                                              </div>
                                         </div>

                                         <button 
                                              type="button"
                                              @click="handleEnquireCheckout(service)" 
                                              class="w-full py-4 text-[10px] font-black uppercase tracking-[0.3em] bg-white/[0.03] border border-white/10 text-white hover:bg-safari-gold hover:text-black hover:border-safari-gold transition-all duration-500 flex items-center justify-center gap-3 group/btn"
                                         >
                                              Enquire & Checkout <ArrowRight class="w-3.5 h-3.5 group-hover/btn:translate-x-1.5 transition-transform" aria-hidden="true" />
                                         </button>
                                    </div>
                               </div>
                          </div>
                      </div>
                 </div>
             </section>

            <!-- Bottom Fleet Carousel -->
            <section v-if="fleet && fleet.length > 0" class="mt-24 md:mt-44 pt-24 md:pt-44 border-t border-white/5 space-y-12 md:space-y-16">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 md:gap-8 px-2 md:px-0">
                    <div class="space-y-3 md:space-y-4">
                        <p class="text-[9px] md:text-xs font-black tracking-[0.5em] uppercase text-safari-gold opacity-90">- LOGISTICS PULSE //</p>
                        <h2 class="font-display text-3xl md:text-5xl font-black uppercase tracking-tighter text-white">Active Fleet Recon.</h2>
                    </div>
                    <div class="hidden md:flex items-center gap-4">
                        <button 
                            @click="bottomFleetPrev"
                            :disabled="!canGoPrev"
                            class="w-12 h-12 border border-white/10 rounded-full flex items-center justify-center text-white hover:border-safari-gold hover:text-safari-gold transition-all disabled:opacity-20 disabled:cursor-not-allowed"
                            aria-label="Previous vehicles"
                        >
                            <ChevronLeft class="w-5 h-5" aria-hidden="true" />
                        </button>
                        <span class="text-xs font-black uppercase tracking-widest opacity-70 text-white">{{ bottomFleetIndex + 1 }} — {{ Math.min(bottomFleetIndex + visibleCount, fleet.length) }} / {{ fleet.length }}</span>
                        <button 
                            @click="bottomFleetNext"
                            :disabled="!canGoNext"
                            class="w-12 h-12 border border-white/10 rounded-full flex items-center justify-center text-white hover:border-safari-gold hover:text-safari-gold transition-all disabled:opacity-20 disabled:cursor-not-allowed"
                            aria-label="Next vehicles"
                        >
                            <ChevronRight class="w-5 h-5" aria-hidden="true" />
                        </button>
                    </div>
                </div>

                <!-- Desktop Grid (Paginated) -->
                <div class="hidden md:grid md:grid-cols-3 gap-10 text-white">
                    <div 
                        v-for="vehicle in visibleFleet" 
                        :key="'desk-'+vehicle.id" 
                        class="group relative aspect-[4/5] bg-white/[0.02] border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/30 transition-all duration-700 shadow-2xl"
                    >
                        <img 
                            v-if="vehicle.image" 
                            :src="vehicle.image" 
                            class="absolute inset-0 h-full w-full object-cover grayscale opacity-70 group-hover:opacity-100 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-1000"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-8 space-y-6 z-10 transition-transform duration-500 transform group-hover:-translate-y-2">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3 text-safari-gold">
                                    <Zap class="w-4 h-4" aria-hidden="true" />
                                    <span class="text-[10px] font-black tracking-[0.3em] uppercase">{{ vehicle.drive_type }}</span>
                                </div>
                                <h4 class="font-display text-2xl font-black uppercase tracking-tight text-white group-hover:text-safari-gold transition-colors">{{ vehicle.name }}</h4>
                            </div>

                            <div class="space-y-4 border-t border-white/10 pt-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest opacity-80 text-white">
                                        <Users class="w-3.5 h-3.5 text-safari-gold/40" aria-hidden="true" />
                                        {{ vehicle.capacity?.split(' ')[0] }} Seats
                                    </div>
                                    <span class="text-xs font-black text-safari-gold tracking-widest uppercase">{{ formatPrice(vehicle.price_per_safari_drive || vehicle.price_per_day) }}/OP</span>
                                </div>
                                
                                <Link 
                                    :href="route('fleet-hire.index') + '?unit=' + vehicle.id"
                                    class="w-full py-4 text-[10px] font-black uppercase tracking-[0.3em] bg-safari-gold text-black hover:bg-white transition-all duration-500 flex items-center justify-center gap-2 group/btn"
                                >
                                    Request Unit <ArrowRight class="w-3.5 h-3.5 group-hover/btn:translate-x-1.5 transition-transform" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Carousel (All Assets Available) -->
                <div class="flex md:hidden gap-6 overflow-x-auto pb-12 no-scrollbar snap-x snap-mandatory px-4 text-white">
                    <div 
                        v-for="vehicle in fleet" 
                        :key="'mob-'+vehicle.id" 
                        class="flex-shrink-0 w-[280px] xs:w-[320px] snap-start group relative aspect-[4/4.8] bg-white/[0.02] border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/30 transition-all duration-700 shadow-2xl"
                    >
                        <img 
                            v-if="vehicle.image" 
                            :src="vehicle.image" 
                            class="absolute inset-0 h-full w-full object-cover grayscale opacity-70 group-hover:opacity-100 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-1000"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-6 xs:p-8 space-y-5 z-10 transition-transform duration-500 transform group-hover:-translate-y-2">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-safari-gold">
                                    <Zap class="w-3.5 h-3.5" aria-hidden="true" />
                                    <span class="text-[9px] font-black tracking-[0.3em] uppercase">{{ vehicle.drive_type }}</span>
                                </div>
                                <h4 class="font-display text-xl xs:text-2xl font-black uppercase tracking-tight text-white group-hover:text-safari-gold transition-colors">{{ vehicle.name }}</h4>
                            </div>

                            <div class="space-y-4 border-t border-white/10 pt-5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3 text-[9px] font-black uppercase tracking-widest opacity-80 text-white">
                                        <Users class="w-3 h-3 text-safari-gold/40" aria-hidden="true" />
                                        {{ vehicle.capacity?.split(' ')[0] }} Seats
                                    </div>
                                    <span class="text-[11px] font-black text-safari-gold tracking-widest uppercase">{{ formatPrice(vehicle.price_per_safari_drive || vehicle.price_per_day) }}/OP</span>
                                </div>
                                
                                <Link 
                                    :href="route('fleet-hire.index') + '?unit=' + vehicle.id"
                                    class="w-full py-4 text-[9px] xs:text-[10px] font-black uppercase tracking-[0.3em] bg-safari-gold text-black hover:bg-white transition-all duration-500 flex items-center justify-center gap-2 group/btn"
                                >
                                    Request Unit <ArrowRight class="w-3.5 h-3.5 group-hover/btn:translate-x-1.5 transition-transform" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Empty State -->
            <div v-if="filteredTours.length === 0 && !fleet.length" class="py-44 border border-dashed border-white/5 rounded-sm flex flex-col items-center text-center bg-white/[0.01]">
                 <Search class="w-16 h-16 opacity-10 mb-10 text-safari-gold" />
                 <h2 class="font-display text-4xl font-black uppercase tracking-widest mb-6 text-white">Archives Silent</h2>
                 <p class="text-xs opacity-80 max-w-sm font-light uppercase tracking-widest italic text-white/60 leading-loose">
                      "The savanna is vast, but we couldn't find a match for this scout report."
                 </p>
            </div>
        </main>

        <!-- Expedition Reserve Modal -->
        <Transition enter-active-class="transition duration-500 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-300 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
             <div v-if="selectedTour" class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-8 bg-black/95 backdrop-blur-sm">
                  <div class="bg-[#0a0a0a] border border-white/10 w-full max-w-6xl rounded-sm overflow-hidden flex flex-col lg:flex-row shadow-[0_0_100px_rgba(202,138,4,0.08)] max-h-[95vh]">

                       <!-- Left panel: Tour image + details -->
                       <div class="lg:w-[38%] relative bg-black shrink-0">
                            <img 
                                :src="selectedTour.images?.[0] || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072'"
                                class="absolute inset-0 w-full h-full object-cover opacity-90"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-black/30"></div>
                            
                            <!-- close btn -->
                            <button @click="closeModal" class="absolute top-6 right-6 p-2.5 border border-white/10 bg-black/40 backdrop-blur-sm rounded-full hover:bg-white/10 transition-all text-white z-10">
                                 <X class="w-4 h-4" />
                            </button>

                            <div class="absolute bottom-0 left-0 right-0 p-10 space-y-6">
                                 <p class="text-xs font-black tracking-[0.5em] text-safari-gold uppercase">— Reserve Expedition</p>
                                 <h2 class="font-display text-4xl font-black uppercase tracking-tighter text-white leading-tight">{{ selectedTour.name }}</h2>

                                 <div class="space-y-3 pt-4 border-t border-white/10">
                                      <div class="flex items-center justify-between">
                                           <span class="text-xs font-black uppercase tracking-widest opacity-80 text-white">Destination</span>
                                           <span class="text-sm font-black text-safari-gold uppercase tracking-widest">{{ selectedTour.destination?.name || '—' }}</span>
                                      </div>
                                      <div class="flex items-center justify-between">
                                           <span class="text-xs font-black uppercase tracking-widest opacity-80 text-white">Duration</span>
                                           <span class="text-sm font-black text-white uppercase tracking-widest">{{ selectedTour.duration }}</span>
                                      </div>
                                      <div class="flex items-center justify-between">
                                           <span class="text-xs font-black uppercase tracking-widest opacity-80 text-white">Base Rate</span>
                                           <span class="text-xl font-black text-safari-gold tracking-tight">{{ formatPrice(selectedTour.price) }}</span>
                                      </div>
                                 </div>
                            </div>
                       </div>

                       <!-- Right panel: Content -->
                       <div v-if="auth.user" class="flex-1 overflow-y-auto p-8 md:p-12 space-y-10 text-white">
                            <!-- Step indicator -->
                            <div class="flex items-center gap-4">
                                 <button v-if="modalStep === 2" @click="modalStep = 1" class="p-2 border border-white/10 rounded-full hover:border-safari-gold hover:text-safari-gold transition-all text-white/40">
                                      <ArrowLeft class="w-4 h-4" />
                                 </button>
                                 <div class="space-y-1">
                                      <h3 class="font-display text-2xl font-black uppercase tracking-tighter text-white">
                                           {{ modalStep === 1 ? 'Select Fleet' : 'Operative Details' }}
                                      </h3>
                                      <p class="text-xs font-black uppercase tracking-[0.4em] opacity-70">Step {{ modalStep }} of 2</p>
                                 </div>
                                 <div class="ml-auto flex gap-2">
                                      <div class="w-8 h-1 rounded-full" :class="modalStep >= 1 ? 'bg-safari-gold' : 'bg-white/10'"></div>
                                      <div class="w-8 h-1 rounded-full" :class="modalStep >= 2 ? 'bg-safari-gold' : 'bg-white/10'"></div>
                                      <div class="w-8 h-1 rounded-full" :class="modalStep >= 3 ? 'bg-safari-gold' : 'bg-white/10'"></div>
                                 </div>
                            </div>

                            <!-- ── STEP 1: Fleet carousel selector ── -->
                            <div v-if="modalStep === 1" class="space-y-8 animate-in fade-in duration-500">
                                 <p class="text-sm font-normal text-gray-300 leading-relaxed italic text-white/60">
                                      Optionally select a fleet vehicle to accompany your expedition. You can skip this and choose later.
                                 </p>

                                 <div v-if="fleet.length > 0" class="space-y-6">
                                      <!-- Carousel card -->
                                      <div class="relative group">
                                           <div class="aspect-[16/9] rounded-sm overflow-hidden border relative"
                                                :class="selectedFleetId === currentFleet?.id ? 'border-safari-gold shadow-[0_0_30px_rgba(212,175,55,0.2)]' : 'border-white/10'">
                                                <img 
                                                     v-if="currentFleet?.image"
                                                     :src="currentFleet.image"
                                                     class="absolute inset-0 w-full h-full object-cover transition-all duration-700"
                                                     :class="selectedFleetId === currentFleet?.id ? 'grayscale-0 opacity-80' : 'grayscale-[0.8] opacity-90'"
                                                />
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>

                                                <!-- Selected badge -->
                                                <div v-if="selectedFleetId === currentFleet?.id" class="absolute top-4 left-4 bg-safari-gold text-black text-xs font-black uppercase tracking-widest px-3 py-1">
                                                     ✓ Selected
                                                </div>

                                                <!-- Vehicle info overlay -->
                                                <div class="absolute bottom-0 left-0 right-0 p-6 space-y-3">
                                                     <div class="flex items-center gap-2 text-safari-gold">
                                                          <Zap class="w-3.5 h-3.5" />
                                                          <span class="text-xs font-black uppercase tracking-widest">{{ currentFleet?.drive_type }}</span>
                                                     </div>
                                                     <h4 class="font-display text-2xl font-black uppercase tracking-tight text-white">{{ currentFleet?.name }}</h4>
                                                     <div class="flex items-center gap-6 text-xs font-black uppercase tracking-widest opacity-90">
                                                          <span class="flex items-center gap-1.5"><Users class="w-3 h-3 text-safari-gold" /> {{ currentFleet?.capacity?.split(' ')[0] }} Pax</span>
                                                          <span v-if="currentFleet?.price_per_day" class="text-safari-gold">{{ formatPrice(currentFleet.price_per_day) }}/day</span>
                                                     </div>
                                                </div>
                                           </div>

                                           <!-- Arrow controls -->
                                           <button @click="fleetPrev" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/70 border border-white/10 rounded-full flex items-center justify-center hover:border-safari-gold hover:text-safari-gold transition-all text-white">
                                                <ChevronLeft class="w-4 h-4" />
                                           </button>
                                           <button @click="fleetNext" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/70 border border-white/10 rounded-full flex items-center justify-center hover:border-safari-gold hover:text-safari-gold transition-all text-white">
                                                <ChevronRight class="w-4 h-4" />
                                           </button>
                                      </div>

                                      <!-- Pagination dots -->
                                      <div class="flex items-center justify-center gap-2">
                                           <div v-for="(_, i) in fleet" :key="i"
                                                class="rounded-full transition-all duration-300 cursor-pointer"
                                                :class="i === fleetIndex ? 'w-6 h-1.5 bg-safari-gold' : 'w-1.5 h-1.5 bg-white/20 hover:bg-white/40'"
                                                @click="fleetIndex = i"
                                           ></div>
                                      </div>

                                      <!-- Select / Deselect toggle -->
                                      <button 
                                           @click="toggleFleet(currentFleet?.id)"
                                           class="w-full py-4 text-sm font-black uppercase tracking-[0.3em] border transition-all rounded-sm"
                                           :class="selectedFleetId === currentFleet?.id 
                                                ? 'bg-safari-gold text-black border-safari-gold' 
                                                : 'bg-white/5 text-white border-white/10 hover:border-safari-gold'"
                                      >
                                           {{ selectedFleetId === currentFleet?.id ? '✓ Fleet Selected' : 'Select This Fleet' }}
                                      </button>
                                      
                                      
                                      <div v-if="selectedFleetId === currentFleet?.id" class="mt-8 space-y-4 animate-in fade-in zoom-in duration-300">
                                          <label class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold/80 ml-1">Deployment Pricing Strategy</label>
                                          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                               <button type="button" 
                                                   @click="form.fleet_pricing_model = 'hourly'"
                                                   :class="['group/opt flex flex-col items-center justify-center p-6 border rounded-sm transition-all duration-300', form.fleet_pricing_model === 'hourly' ? 'border-safari-gold bg-safari-gold/20 shadow-[0_0_30px_rgba(212,175,55,0.15)] ring-1 ring-safari-gold/30' : 'border-white/10 bg-white/[0.03] hover:border-white/30 hover:bg-white/[0.05]']"
                                               >
                                                   <span :class="['text-[11px] font-black uppercase tracking-[0.2em] transition-colors mb-2', form.fleet_pricing_model === 'hourly' ? 'text-safari-gold' : 'text-white/60 group-hover/opt:text-white']">Hourly Recon</span>
                                                   <span :class="['text-xl font-display font-black tracking-tight transition-colors', form.fleet_pricing_model === 'hourly' ? 'text-white' : 'text-safari-gold/80 group-hover/opt:text-safari-gold']" v-if="currentFleet?.price_per_hour">{{ formatPrice(currentFleet.price_per_hour) }}</span>
                                               </button>
                                               <button type="button" 
                                                   @click="form.fleet_pricing_model = 'daily'"
                                                   :class="['group/opt flex flex-col items-center justify-center p-6 border rounded-sm transition-all duration-300', form.fleet_pricing_model === 'daily' ? 'border-safari-gold bg-safari-gold/20 shadow-[0_0_30px_rgba(212,175,55,0.15)] ring-1 ring-safari-gold/30' : 'border-white/10 bg-white/[0.03] hover:border-white/30 hover:bg-white/[0.05]']"
                                               >
                                                   <span :class="['text-[11px] font-black uppercase tracking-[0.2em] transition-colors mb-2', form.fleet_pricing_model === 'daily' ? 'text-safari-gold' : 'text-white/60 group-hover/opt:text-white']">Daily Mission</span>
                                                   <span :class="['text-xl font-display font-black tracking-tight transition-colors', form.fleet_pricing_model === 'daily' ? 'text-white' : 'text-safari-gold/80 group-hover/opt:text-safari-gold']" v-if="currentFleet?.price_per_day">{{ formatPrice(currentFleet.price_per_day) }}</span>
                                               </button>
                                               <button type="button" 
                                                   @click="form.fleet_pricing_model = 'safari'"
                                                   :class="['group/opt flex flex-col items-center justify-center p-6 border rounded-sm transition-all duration-300', form.fleet_pricing_model === 'safari' ? 'border-safari-gold bg-safari-gold/20 shadow-[0_0_30px_rgba(212,175,55,0.15)] ring-1 ring-safari-gold/30' : 'border-white/10 bg-white/[0.03] hover:border-white/30 hover:bg-white/[0.05]']"
                                               >
                                                   <span :class="['text-[11px] font-black uppercase tracking-[0.2em] transition-colors mb-2', form.fleet_pricing_model === 'safari' ? 'text-safari-gold' : 'text-white/60 group-hover/opt:text-white']">Full Safari</span>
                                                   <span :class="['text-xl font-display font-black tracking-tight transition-colors', form.fleet_pricing_model === 'safari' ? 'text-white' : 'text-safari-gold/80 group-hover/opt:text-safari-gold']" v-if="currentFleet?.price_per_safari_drive">{{ formatPrice(currentFleet.price_per_safari_drive) }}</span>
                                               </button>
                                          </div>
                                          <input type="hidden" v-model="form.fleet_pricing_model" required />
                                     </div>
                                 </div>

                                 <!-- Action row -->
                                 <div class="flex gap-4 pt-4 border-t border-white/5">
                                      <button @click="selectedFleetId = null; modalStep = 2" class="flex-1 py-4 text-sm font-black uppercase tracking-[0.3em] border border-white/10 text-white/40 hover:text-white hover:border-white/30 transition-all rounded-sm">
                                           Skip Fleet
                                      </button>
                                      <button 
                                          @click="() => { if (selectedFleetId && !form.fleet_pricing_model) { showToast('STRATEGIC OMISSION: SELECT A FLEET PRICING PROTOCOL TO PROCEED', 'error'); return; } modalStep = 2; }" 
                                          class="flex-1 py-4 bg-safari-gold text-black text-sm font-black uppercase tracking-[0.3em] hover:bg-white transition-all flex items-center justify-center gap-3 rounded-sm"
                                      >
                                           Continue <ArrowRight class="w-4 h-4" />
                                      </button>
                                 </div>

                            </div>

                            <!-- ── STEP 2 & 3: Operative details & Payment ── -->
                            <div v-if="modalStep >= 2" class="animate-in fade-in duration-500">
                                 <!-- Selected fleet chip -->
                                 <div v-if="selectedFleetId" class="mb-8 flex items-center gap-4 p-4 bg-safari-gold/10 border border-safari-gold/20 rounded-sm">
                                      <Zap class="w-4 h-4 text-safari-gold shrink-0" />
                                      <div class="space-y-0.5">
                                           <p class="text-xs font-black uppercase tracking-widest opacity-90">Fleet Attached</p>
                                           <p class="text-sm font-black uppercase tracking-widest text-safari-gold">
                                                {{ fleet.find(f => f.id === selectedFleetId)?.name }}
                                           </p>
                                      </div>
                                      <button @click="selectedFleetId = null" class="ml-auto text-white/30 hover:text-white transition-colors"><X class="w-4 h-4" /></button>
                                 </div>

                                 <form @submit.prevent="modalStep === 2 ? modalStep = 3 : submit()" class="space-y-8">
                                      
                                      <div v-if="modalStep === 2" class="space-y-8 animate-in fade-in slide-in-from-bottom">
                                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-3">
                                                     <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80">Identity Name</label>
                                                     <input v-model="form.name" type="text" placeholder="Scout Identifier" class="w-full bg-white/5 border border-white/10 rounded-sm px-5 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" :required="modalStep === 2" />
                                                </div>
                                                <div class="space-y-3">
                                                     <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80">Communications Relay</label>
                                                     <input v-model="form.email" type="email" placeholder="Email Address" class="w-full bg-white/5 border border-white/10 rounded-sm px-5 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" :required="modalStep === 2" />
                                                </div>
                                           </div>

                                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-3">
                                                     <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80">Tactical Contact (Phone)</label>
                                                     <input v-model="form.phone" type="text" placeholder="+254..." class="w-full bg-white/5 border border-white/10 rounded-sm px-5 py-4 text-sm font-light tracking-[0.2em] outline-none focus:border-safari-gold transition-all text-white" :required="modalStep === 2" />
                                                </div>
                                                <div class="space-y-3">
                                                     <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80">Group Size</label>
                                                     <input v-model="form.guests" type="number" min="1" placeholder="Operatives" class="w-full bg-white/5 border border-white/10 rounded-sm px-5 py-4 text-sm font-light tracking-[0.2em] outline-none focus:border-safari-gold transition-all text-white" />
                                                </div>
                                           </div>

                                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-3">
                                                     <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80">Deployment Start</label>
                                                     <input v-model="form.start_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-sm px-5 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" :required="modalStep === 2" />
                                                </div>
                                                <div class="space-y-3">
                                                     <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80">End of Mission</label>
                                                     <input v-model="form.end_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-sm px-5 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" />
                                                </div>
                                           </div>

                                           <div class="space-y-3">
                                                <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80">Mission Briefing</label>
                                                <textarea v-model="form.message" rows="4" placeholder="SPECIFY YOUR MISSION REQUIREMENTS..." class="w-full bg-white/5 border border-white/10 rounded-sm p-5 text-sm font-light outline-none focus:border-safari-gold transition-all leading-relaxed uppercase placeholder:opacity-10 text-white"></textarea>
                                           </div>

                                           <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-6 border-t border-white/10">
                                                <div class="flex items-center gap-3 opacity-70">
                                                     <ShieldCheck class="w-5 h-5 text-safari-gold" />
                                                     <span class="text-xs font-black uppercase tracking-widest text-white">Big Simba Network Secured</span>
                                                </div>
                                                <div class="flex gap-4 w-full md:w-auto">
                                                     <button @click="modalStep = 1" type="button" class="w-full md:w-auto px-8 py-5 text-sm font-black uppercase tracking-[0.3em] text-white/40 hover:text-white transition-all bg-white/5 rounded-sm">
                                                          BACK
                                                     </button>
                                                     <button type="submit" class="w-full md:w-auto px-12 py-5 bg-safari-gold text-black text-sm font-black uppercase tracking-[0.4em] hover:bg-white transition-all shadow-xl rounded-sm">
                                                          PROCEED TO PAYMENT
                                                     </button>
                                                </div>
                                           </div>
                                      </div>

                                      <!-- Step 3: Payment Integration -->
                                      <div v-if="modalStep === 3" class="space-y-8 animate-in fade-in slide-in-from-right">
                                           <div class="p-6 bg-white/5 border border-safari-gold/20 rounded-sm space-y-2">
                                                <p class="text-xs uppercase tracking-[0.3em] text-safari-gold font-black flex items-center gap-2"><Lock class="w-3 h-3"/> Secure Checkout</p>
                                                <h4 class="font-display text-xl text-white font-black tracking-tighter uppercase">Clearance Authorization</h4>
                                                <p class="text-sm text-white/40 uppercase tracking-widest">Required Upfront Deposit (10% Tour + Full Fleet)</p>
                                                <p class="text-3xl font-black text-white tracking-tighter mt-2">{{ formatPrice(calculatedDeposit) }}</p>
                                           </div>

                                           <div class="grid grid-cols-2 gap-4">
                                                <label :class="`p-6 border rounded-sm cursor-pointer transition-all flex flex-col items-center gap-4 ${form.payment_method === 'mpesa' ? 'border-safari-gold bg-safari-gold/5' : 'border-white/10 hover:border-white/30 bg-white/[0.02]'}`">
                                                     <input type="radio" v-model="form.payment_method" value="mpesa" class="hidden" />
                                                     <div class="w-12 h-12 bg-green-500/10 rounded-full flex items-center justify-center text-green-500">
                                                          <Phone class="w-5 h-5" />
                                                     </div>
                                                     <span class="text-xs font-black uppercase tracking-widest text-white">M-Pesa</span>
                                                </label>
                                                
                                                <label :class="`p-6 border rounded-sm cursor-pointer transition-all flex flex-col items-center gap-4 ${form.payment_method === 'visa' ? 'border-safari-gold bg-safari-gold/5' : 'border-white/10 hover:border-white/30 bg-white/[0.02]'}`">
                                                     <input type="radio" v-model="form.payment_method" value="visa" class="hidden" />
                                                     <div class="w-12 h-12 bg-blue-500/10 rounded-full flex items-center justify-center text-blue-500">
                                                          <CreditCard class="w-5 h-5" />
                                                     </div>
                                                     <span class="text-xs font-black uppercase tracking-widest text-white">Visa / Card</span>
                                                </label>
                                           </div>

                                           <div v-if="form.payment_method === 'mpesa'" class="space-y-4 p-8 bg-black/40 border border-white/5 rounded-sm">
                                                <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">M-Pesa Number</label>
                                                <input v-model="form.phone" type="text" placeholder="2547XXXXXXXX" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-5 text-sm font-light tracking-[0.2em] outline-none focus:border-green-500 transition-all text-white placeholder:text-white/20" :required="modalStep === 3" />
                                                <p class="text-xs text-green-500/60 uppercase tracking-widest font-black mt-2">A payment prompt will be sent to your phone.</p>
                                                <p v-if="form.errors.phone" class="text-xs text-red-400 uppercase tracking-widest font-black mt-2">{{ form.errors.phone }}</p>
                                                <p v-if="form.errors.payment_method" class="text-xs text-red-500 uppercase tracking-widest font-black mt-2">{{ form.errors.payment_method }}</p>
                                           </div>

                                           <div v-if="form.payment_method === 'visa'" class="space-y-4 p-8 bg-black/40 border border-white/5 rounded-sm">
                                                <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">Card Number</label>
                                                <div class="relative">
                                                     <input type="text" placeholder="XXXX XXXX XXXX XXXX" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-5 text-sm font-light tracking-[0.2em] outline-none focus:border-blue-500 transition-all text-white placeholder:text-white/20" :required="modalStep === 3" />
                                                </div>
                                                <div class="grid grid-cols-2 gap-4 mt-4">
                                                     <div>
                                                          <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">Expiry</label>
                                                          <input type="text" placeholder="MM/YY" class="w-full mt-2 bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-light tracking-[0.2em] outline-none focus:border-blue-500 transition-all text-white placeholder:text-white/20" :required="modalStep === 3" />
                                                     </div>
                                                     <div>
                                                          <label class="text-xs font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">CVC</label>
                                                          <input type="text" placeholder="123" class="w-full mt-2 bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-light tracking-[0.2em] outline-none focus:border-blue-500 transition-all text-white placeholder:text-white/20" :required="modalStep === 3" />
                                                     </div>
                                                </div>
                                           </div>

                                           <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-6 border-t border-white/10">
                                                <div class="flex items-center gap-3 opacity-70">
                                                     <ShieldCheck class="w-5 h-5 text-safari-gold" />
                                                     <span class="text-xs font-black uppercase tracking-widest text-white">Encrypted Payment Gateway</span>
                                                </div>
                                                <div class="flex gap-4 w-full md:w-auto">
                                                     <button @click="modalStep = 2" type="button" class="w-full md:w-auto px-8 py-5 text-sm font-black uppercase tracking-[0.3em] text-white/40 hover:text-white transition-all bg-white/5 rounded-sm">
                                                          BACK
                                                     </button>
                                                     <button type="submit" :disabled="form.processing" class="w-full md:w-auto px-12 py-5 bg-safari-gold text-black text-sm font-black uppercase tracking-[0.4em] hover:bg-white transition-all shadow-xl rounded-sm">
                                                          {{ form.processing ? 'PINGING DARAJA...' : 'AUTHORIZE PAYMENT' }}
                                                     </button>
                                                </div>
                                           </div>
                                      </div>
                                 </form>
                            </div>
                        </div>

                       <!-- Right panel: Guest View -->
                       <div v-else class="flex-1 p-8 md:p-20 space-y-12 flex flex-col justify-center text-white relative overflow-hidden bg-[#050505]">
                            <div class="absolute -right-20 -top-20 w-80 h-80 bg-safari-gold/5 rounded-full blur-3xl opacity-30"></div>
                            
                            <div class="space-y-12 relative z-10">
                                 <div class="space-y-6">
                                      <p class="text-sm font-black tracking-[0.6em] text-safari-gold uppercase opacity-90">// EXPEDITION INTEL</p>
                                      <div class="flex items-baseline gap-4">
                                           <h3 class="font-display text-7xl font-black uppercase tracking-tighter text-white">{{ formatPrice(selectedTour.price) }}</h3>
                                           <span class="text-sm font-black opacity-40 uppercase tracking-[0.4em] italic">/ Person</span>
                                      </div>
                                 </div>
                                 
                                 <div class="space-y-8 prose prose-invert">
                                      <p class="text-lg font-light text-gray-400 leading-relaxed uppercase tracking-[0.1em] max-w-lg italic border-l-4 border-safari-gold pl-10 py-2">
                                           "SECURE AUTHENTICATION IS MANDATORY TO ACCESS TACTICAL LOGISTICS, SELECT FLEET UNITS, AND AUTHORIZE EXPEDITION DEPLOYMENTS."
                                      </p>
                                      <p class="text-[11px] font-black uppercase tracking-[0.3em] text-white/30 ml-12">Authorized by Big Simba Command</p>
                                 </div>

                                 <div class="h-[1px] w-64 bg-white/10"></div>

                                 <button 
                                     type="button" 
                                     @click="showToast('AUTHENTICATION REQUIRED: Please login or register to continue with your reservation protocol.', 'error', 5000)" 
                                     class="w-full max-w-md bg-safari-gold text-black py-8 text-sm font-black uppercase tracking-[0.5em] hover:bg-white transition-all transform hover:-translate-y-2 shadow-[0_20px_50px_rgba(212,155,62,0.15)] rounded-sm active:scale-95 group/btn"
                                 >
                                      PROCEED TO CHECKOUT
                                 </button>
                            </div>
                            
                            <div class="absolute bottom-12 left-12 flex items-center gap-6 opacity-30">
                                 <ShieldCheck class="w-6 h-6 text-safari-gold" />
                                 <span class="text-[11px] font-black uppercase tracking-[0.5em] text-white">Tactical Encryption Active // PSC-09</span>
                            </div>
                       </div>
                   </div>
              </div>
         </Transition>
        </div>
    </component>
</template>

<style scoped>
main { font-family: 'Outfit', sans-serif; }
@keyframes pulse-gold {
  0% { box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.4); }
  70% { box-shadow: 0 0 0 20px rgba(234, 179, 8, 0); }
  100% { box-shadow: 0 0 0 0 rgba(234, 179, 8, 0); }
}
.mission-highlight {
  animation: pulse-gold 2s infinite;
  border-color: #eab308 !important;
  transform: scale(1.02);
  z-index: 10;
  border-width: 2px !important;
}
</style>
