<script setup lang="ts">
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Clock, MapPin, Share2, Heart, CheckCircle2, ChevronRight, Users, ShieldCheck, Star, ChevronLeft, X, Globe, ChevronDown, ChevronUp } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { useToast } from '@/composables/useToast';
import { router } from '@inertiajs/vue3';

const { show: showToast } = useToast();

const props = defineProps<{
    tour: any;
}>();

const page = usePage<any>();
const auth = computed(() => page.props.auth);
const layout = computed(() => (page.props.auth?.user || page.url.startsWith('/dashboard')) ? AppHeaderLayout : GuestLayout);

const activeImage = ref(props.tour.images?.[0] || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072');
const selectedPeople = ref(1);

const formatPrice = (price: string | number) => {
    return new Intl.NumberFormat('en-KE', { style: 'currency', currency: 'KES', maximumFractionDigits: 0 }).format(parseFloat(price.toString()));
};

const goBack = () => {
    window.history.back();
};
const step = ref(1);

const bookingForm = useForm({
    tour_id: props.tour.id,
    start_date: '',
    guests: 1,
    name: (page.props as any).auth?.user?.name || '',
    email: (page.props as any).auth?.user?.email || '',
    phone: '',
    payment_method: 'mpesa',
    fleet_id: '',
    fleet_pricing_model: ''
});

const totalInvestment = computed(() => {
    let base = parseFloat(props.tour.price) * bookingForm.guests;
    if (bookingForm.fleet_id) {
        const selectedFleet = (page.props.fleet as any[])?.find(f => f.id == bookingForm.fleet_id);
        if (selectedFleet && bookingForm.fleet_pricing_model) {
            if (bookingForm.fleet_pricing_model === 'hourly') base += parseFloat(selectedFleet.price_per_hour);
            if (bookingForm.fleet_pricing_model === 'daily') base += parseFloat(selectedFleet.price_per_day);
            if (bookingForm.fleet_pricing_model === 'safari') base += parseFloat(selectedFleet.price_per_safari_drive);
        }
    }
    return base;
});

const calculatedDeposit = computed(() => {
    let tourDeposit = (parseFloat(props.tour.price) * bookingForm.guests) * 0.1;
    let fleetDeposit = 0;
    if (bookingForm.fleet_id) {
        const selectedFleet = (page.props.fleet as any[])?.find(f => f.id == bookingForm.fleet_id);
        if (selectedFleet && bookingForm.fleet_pricing_model) {
            if (bookingForm.fleet_pricing_model === 'hourly') fleetDeposit += parseFloat(selectedFleet.price_per_hour);
            if (bookingForm.fleet_pricing_model === 'daily') fleetDeposit += parseFloat(selectedFleet.price_per_day);
            if (bookingForm.fleet_pricing_model === 'safari') fleetDeposit += parseFloat(selectedFleet.price_per_safari_drive);
        }
    }
    return tourDeposit + fleetDeposit;
});

const submitBooking = () => {
    if (!page.props.auth?.user) {
        showToast('MISSION INTERCEPTED: PLEASE LOGIN OR REGISTER TO AUTHORIZE EXPEDITION DEPOSIT', 'error');
        return;
    }
    bookingForm.post(route('tour-enquiries.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showToast('Expedition clearance requested! Check your communications.', 'success');
            step.value = 1;
            bookingForm.reset();
        }
    });
};

const isItineraryExpanded = ref(false);
const toggleItinerary = () => { isItineraryExpanded.value = !isItineraryExpanded.value; };

</script>

<template>
    <Head :title="`${tour.name} - Big Simba Safaris`" />

    <component :is="layout" :full-bleed="true">
        <main class="w-full max-w-[1800px] mx-auto px-4 md:px-20 py-16 md:py-44 min-h-screen bg-[#050505] overflow-x-hidden">
            <!-- Functional Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 md:gap-12 mb-16 md:mb-24 pt-24 lg:pt-0 px-2 md:px-0">
                 <div class="space-y-6 md:space-y-8 flex-1">
                      <Link :href="page.props.auth?.user ? route('dashboard') : route('home')" class="flex items-center gap-2 text-[10px] md:text-sm font-black uppercase tracking-[0.4em] text-safari-gold/60 hover:text-safari-gold transition-colors group">
                           <ChevronLeft class="w-3.5 h-3.5 group-hover:-translate-x-1 transition-transform" /> {{ page.props.auth?.user ? 'Back to Dashboard' : 'Back to Wilderness' }}
                      </Link>
                      <div class="space-y-4 md:space-y-6">
                           <div class="flex flex-wrap items-center gap-4 md:gap-6">
                                <span class="px-4 md:px-5 py-1.5 md:py-2 bg-safari-gold/5 border border-safari-gold/20 text-safari-gold text-[9px] md:text-xs font-black uppercase tracking-[0.3em] rounded-full">
                                     {{ tour.destination?.name || 'CENTRAL KENYA' }}
                                </span>
                                <div class="flex items-center gap-2 md:gap-3 text-[10px] md:text-sm font-black opacity-80 uppercase tracking-[0.4em] text-white">
                                     <Clock class="w-3.5 h-3.5 md:w-4 md:h-4 text-safari-gold" /> {{ tour.duration }}
                                </div>
                                <div class="flex items-center gap-2 md:gap-3 text-[10px] md:text-sm font-black opacity-80 uppercase tracking-[0.4em] text-white">
                                     <Users class="w-3.5 h-3.5 md:w-4 md:h-4 text-safari-gold" /> MAX: 12
                                </div>
                           </div>
                           <h1 class="font-display text-4xl sm:text-5xl md:text-[8rem] font-black uppercase tracking-tighter leading-[0.9] md:leading-[0.85] text-white break-words">{{ tour.name }}</h1>
                      </div>
                 </div>
                 <div class="hidden xl:flex items-center gap-10 bg-white/[0.02] border border-white/5 p-10 rounded-sm shadow-2xl">
                      <div class="text-right space-y-2">
                           <p class="text-sm font-black uppercase tracking-[0.5em] opacity-70 text-white">Starting Investment</p>
                           <p class="text-5xl font-black uppercase tracking-tighter text-safari-gold">{{ formatPrice(tour.price) }}</p>
                      </div>
                      <button class="h-16 w-16 border border-white/10 hover:border-red-500/50 hover:bg-red-500/5 transition-all text-white/20 hover:text-red-500 rounded-full flex items-center justify-center">
                           <Heart class="w-6 h-6" />
                      </button>
                 </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-24 border-b border-white/5 pb-32 mb-32">
                 <!-- Left: Story & Details -->
                 <div class="lg:col-span-2 space-y-32">
                      <!-- Immersive Gallery -->
                      <section class="space-y-8">
                           <div class="aspect-[21/10] rounded-sm overflow-hidden border border-white/5 relative group shadow-2xl">
                                <img :src="activeImage" class="w-full h-full object-cover grayscale-[0.2] transition-transform duration-[3000ms] group-hover:scale-105" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80"></div>
                           </div>
                           <div v-if="tour.images?.length > 1" class="flex gap-6 overflow-x-auto pb-6 scrollbar-hide px-2">
                                <button v-for="(img, i) in tour.images" :key="i" @click="activeImage = img" 
                                     :class="['w-32 h-32 rounded-sm overflow-hidden flex-shrink-0 border-2 transition-all shadow-lg', activeImage === img ? 'border-safari-gold scale-95 translate-y-1' : 'border-transparent opacity-80 hover:opacity-100']">
                                     <img :src="img" class="w-full h-full object-cover" />
                                </button>
                           </div>
                      </section>

                      <!-- Description -->
                      <section class="space-y-10 prose prose-invert max-w-none">
                           <div class="flex items-center gap-8 mb-12">
                                <p class="text-sm font-black tracking-[0.6em] uppercase text-safari-gold whitespace-nowrap">The Narrative //</p>
                                <div class="h-[1px] w-full bg-white/5"></div>
                           </div>
                           <p class="text-2xl md:text-3xl font-light leading-relaxed opacity-90 italic max-w-4xl text-white">
                                {{ tour.description }}
                           </p>
                      </section>

                      <!-- Itinerary Architecture -->
                       <section class="space-y-12 md:space-y-20">
                            <div class="flex items-center justify-between border-l-4 border-safari-gold pl-6 md:pl-12">
                                 <div class="space-y-3 md:space-y-4">
                                      <h2 class="font-display text-3xl md:text-5xl font-black uppercase tracking-tighter text-white">Expedition Roadmap</h2>
                                      <p class="text-[9px] md:text-sm opacity-80 uppercase tracking-[0.5em] font-black text-white">- DAILY SEQUENCE OF EVENTS //</p>
                                 </div>
                                 <button @click="toggleItinerary" class="flex md:hidden items-center gap-2 px-4 py-2 border border-white/10 rounded-sm text-[10px] font-black uppercase tracking-widest text-safari-gold">
                                      {{ isItineraryExpanded ? 'Minimize' : 'Expand' }}
                                      <ChevronUp v-if="isItineraryExpanded" class="w-3 h-3" />
                                      <ChevronDown v-else class="w-3 h-3" />
                                 </button>
                            </div>
 
                            <div :class="['space-y-16 relative transition-all duration-700 overflow-hidden', !isItineraryExpanded ? 'max-h-0 md:max-h-none opacity-0 md:opacity-100' : 'max-h-[5000px] opacity-100 pt-8 md:pt-0']">
                                 <!-- Connecting Line -->
                                 <div class="absolute left-7 top-4 bottom-4 w-[1px] bg-white/10 border-l border-dashed border-white/20"></div>
 
                                 <div v-for="(item, index) in tour.itinerary" :key="index" class="relative pl-20 md:pl-32 group">
                                      <!-- Day Anchor -->
                                      <div class="absolute left-0 top-0 w-14 h-14 rounded-full border border-white/10 bg-black flex items-center justify-center group-hover:border-safari-gold group-hover:bg-safari-gold/10 transition-all z-10 shadow-xl">
                                           <span class="text-sm font-black text-safari-gold group-hover:scale-125 transition-transform">{{ (item.day as any) || (index as number) + 1 }}</span>
                                      </div>
                                      <div class="space-y-6 bg-white/[0.01] border border-white/5 p-8 md:p-10 rounded-sm hover:border-safari-gold/20 transition-all duration-500 shadow-2xl">
                                           <h3 class="font-display text-xl md:text-2xl font-black uppercase tracking-tight group-hover:text-safari-gold transition-colors text-white">{{ item.title || `Phase ${(index as number) + 1}` }}</h3>
                                           <p class="text-sm md:text-lg font-normal text-gray-300 leading-relaxed max-w-3xl italic text-white/70">"{{ item.description }}"</p>
                                      </div>
                                 </div>
 
                                 <!-- Expanded trigger for mobile -->
                                 <div v-if="!isItineraryExpanded" class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[#050505] to-transparent flex items-end justify-center pb-4 md:hidden">
                                      <button @click="toggleItinerary" class="px-8 py-3 bg-safari-gold text-black text-[10px] font-black uppercase tracking-[0.3em] shadow-2xl">
                                           Unlock Full Roadmap
                                      </button>
                                 </div>
                            </div>
                       </section>

                       <!-- Mobile-Only Compact Reservation Terminal -->
                       <section class="block lg:hidden space-y-8 animate-in fade-in duration-700">
                            <div class="bg-[#111009] border border-white/10 rounded-sm p-5 space-y-8 shadow-2xl relative overflow-hidden group">
                                 <div class="absolute -right-10 -top-10 w-32 h-32 bg-safari-gold/5 rounded-full blur-2xl"></div>
                                 
                                 <div class="relative z-10 space-y-6">
                                      <div class="flex items-center justify-between border-b border-white/5 pb-4">
                                           <p class="text-[9px] font-black uppercase tracking-[0.4em] text-safari-gold opacity-90">// INITIAL RESERVATION</p>
                                           <div class="flex items-baseline gap-2">
                                                <span class="text-xl font-black text-safari-gold">{{ formatPrice(tour.price) }}</span>
                                                <span class="text-[8px] font-black opacity-40 uppercase tracking-widest text-white">/ Pax</span>
                                           </div>
                                      </div>

                                      <form @submit.prevent="step === 1 ? step = 2 : submitBooking()" class="space-y-6">
                                           <div v-if="step === 1" class="space-y-6">
                                                <template v-if="auth.user">
                                                     <div class="grid grid-cols-2 gap-3">
                                                          <div class="space-y-1.5">
                                                               <label class="text-[8px] font-black uppercase tracking-widest opacity-60 text-white">Recon Date</label>
                                                               <input type="date" v-model="bookingForm.start_date" class="w-full bg-white/5 border border-white/10 rounded-sm p-3 text-[10px] focus:border-safari-gold transition-all text-white outline-none appearance-none" required />
                                                          </div>
                                                          <div class="space-y-1.5">
                                                               <label class="text-[8px] font-black uppercase tracking-widest opacity-60 text-white">Operatives</label>
                                                               <input type="number" v-model="bookingForm.guests" min="1" max="15" class="w-full bg-white/5 border border-white/10 rounded-sm p-3 text-[10px] focus:border-safari-gold transition-all text-white outline-none" required />
                                                          </div>
                                                     </div>

                                                     <div class="space-y-4">
                                                          <button type="submit" class="w-full bg-safari-gold text-black py-4 text-[10px] font-black uppercase tracking-[0.3em] hover:bg-white transition-all transform active:scale-95 shadow-2xl rounded-sm">
                                                               INITIATE DEPLOYMENT
                                                          </button>
                                                          <p class="text-[8px] text-center opacity-30 uppercase tracking-[0.4em] text-white italic">"Authored by Big Simba Command"</p>
                                                     </div>
                                                </template>
                                                <template v-else>
                                                     <button 
                                                          type="button" 
                                                          @click="showToast('AUTHENTICATION REQUIRED: Please login or register to authorize this checkout protocol.', 'error')" 
                                                          class="w-full bg-white/5 border border-white/10 text-white py-4 text-[10px] font-black uppercase tracking-[0.3em] hover:border-safari-gold transition-all rounded-sm"
                                                     >
                                                          PROCEED TO CHECKOUT
                                                     </button>
                                                </template>
                                           </div>

                                           <!-- Step 2 Payment (Compact) -->
                                           <div v-if="step === 2" class="space-y-6 animate-in slide-in-from-right duration-500">
                                                <div class="bg-safari-gold/10 p-4 border border-safari-gold/20 rounded-sm space-y-1">
                                                     <p class="text-[8px] font-black uppercase tracking-widest opacity-80 text-safari-gold">Required Deposit</p>
                                                     <p class="text-xl font-black text-white tracking-tighter">{{ formatPrice(calculatedDeposit) }}</p>
                                                </div>
                                                
                                                <div class="space-y-4">
                                                     <select v-model="bookingForm.payment_method" class="w-full bg-black/40 border border-white/10 rounded-sm p-4 text-[10px] font-black uppercase tracking-widest text-white outline-none appearance-none">
                                                          <option value="mpesa">Lipa na M-Pesa</option>
                                                          <option value="visa">Visa / Card</option>
                                                     </select>
                                                     
                                                     <div v-if="bookingForm.payment_method === 'mpesa'" class="space-y-2">
                                                          <input type="text" v-model="bookingForm.phone" placeholder="2547XXXXXXXX" class="w-full bg-white/5 border border-white/10 rounded-sm p-4 text-[10px] tracking-widest text-white outline-none focus:border-green-500" required />
                                                     </div>

                                                     <div class="flex gap-2">
                                                          <button @click="step = 1" type="button" class="px-4 py-4 bg-white/5 border border-white/10 text-[8px] font-black uppercase text-white/40">BACK</button>
                                                          <button type="submit" :disabled="bookingForm.processing" class="flex-1 bg-safari-gold text-black py-4 text-[10px] font-black uppercase tracking-[0.3em] hover:bg-white transition-all shadow-xl rounded-sm">
                                                               {{ bookingForm.processing ? 'PINGING...' : 'AUTHORIZE' }}
                                                          </button>
                                                     </div>
                                                </div>
                                           </div>
                                      </form>
                                 </div>
                            </div>
                       </section>

                       <!-- Expedition Logistics -->
                       <section class="space-y-12 md:space-y-16">
                            <div class="border-l-4 border-safari-gold pl-6 md:pl-12 space-y-3 md:space-y-4">
                                 <h2 class="font-display text-3xl md:text-5xl font-black uppercase tracking-tighter text-white">Expedition Logistics</h2>
                                 <p class="text-[9px] md:text-sm opacity-80 uppercase tracking-[0.5em] font-black text-white">- TACTICAL MOBILITY & SUPPORT HUB //</p>
                            </div>

                            <div class="flex md:grid md:grid-cols-2 gap-6 md:gap-10 overflow-x-auto md:overflow-hidden pb-8 md:pb-0 no-scrollbar snap-x snap-mandatory">
                                 <div v-for="vehicle in (page.props.fleet as any[])" :key="vehicle.id" class="flex-shrink-0 w-[285px] md:w-auto snap-start group bg-white/[0.01] border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-700 shadow-2xl flex flex-col sm:flex-row">
                                      <div class="sm:w-1/2 aspect-video sm:aspect-auto overflow-hidden bg-black relative">
                                           <img :src="vehicle.image" class="w-full h-full object-cover transition-transform duration-[2000ms] group-hover:scale-110 opacity-70" />
                                           <div class="absolute inset-x-0 bottom-0 p-4 md:p-6 bg-gradient-to-t from-black to-transparent">
                                                <p class="text-[9px] md:text-xs font-black uppercase tracking-widest text-safari-gold">{{ vehicle.drive_type }}</p>
                                                <h4 class="font-display text-xs md:text-sm font-black uppercase tracking-tight text-white">{{ vehicle.name }}</h4>
                                           </div>
                                      </div>
                                      <div class="sm:w-1/2 p-6 md:p-8 space-y-4 md:space-y-6 flex flex-col justify-between italic">
                                           <div class="space-y-3 md:space-y-4">
                                                <div class="flex items-center gap-2 opacity-80">
                                                     <Users class="w-3 md:w-4 h-3 md:h-4 text-safari-gold" />
                                                     <span class="text-[9px] md:text-sm font-black uppercase tracking-widest text-white">{{ vehicle.capacity }} SEATS</span>
                                                </div>
                                                <p class="text-[9px] md:text-sm font-normal text-gray-300 leading-relaxed uppercase tracking-widest">Deployable Asset</p>
                                           </div>
                                           <Link :href="route('fleet-hire.index')" class="flex items-center gap-2 text-[9px] md:text-xs font-black uppercase tracking-widest text-safari-gold/60 hover:text-safari-gold transition-colors group/btn">
                                                Documentation <ChevronRight class="w-3 h-3 group-hover/btn:translate-x-1 transition-transform" />
                                           </Link>
                                      </div>
                                 </div>
                            </div>
                       </section>

                       <!-- Provisions -->
                       <section class="grid grid-cols-1 md:grid-cols-2 gap-20 py-24 border-y border-white/5">
                           <div class="space-y-12">
                                <div class="space-y-2">
                                     <h3 class="font-display text-sm font-black uppercase tracking-[0.5em] text-safari-gold">Included In Pride</h3>
                                     <p class="text-xs opacity-20 uppercase tracking-widest font-bold italic text-white">Standard Big Simba Provisions</p>
                                </div>
                                <ul class="space-y-6">
                                     <li v-for="item in tour.whats_included" :key="item" class="flex items-center gap-6 text-sm font-black uppercase tracking-[0.3em] opacity-90 hover:opacity-100 transition-opacity text-white">
                                          <div class="w-8 h-8 rounded-full bg-safari-gold/5 flex items-center justify-center flex-shrink-0 border border-safari-gold/20 shadow-lg">
                                               <CheckCircle2 class="w-4 h-4 text-safari-gold" />
                                          </div>
                                          {{ item }}
                                     </li>
                                </ul>
                           </div>
                           <div class="space-y-12">
                                <div class="space-y-2">
                                     <h3 class="font-display text-sm font-black uppercase tracking-[0.5em] opacity-80 text-white">Personal Provisions</h3>
                                     <p class="text-xs opacity-20 uppercase tracking-widest font-bold italic text-white">Items Not Under Our Banner</p>
                                </div>
                                <ul class="space-y-6">
                                     <li v-for="item in tour.whats_excluded" :key="item" class="flex items-center gap-6 text-sm font-black uppercase tracking-[0.3em] opacity-20 hover:opacity-80 transition-opacity text-white">
                                          <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center flex-shrink-0 border border-white/10">
                                               <X class="w-4 h-4 text-white/40" />
                                          </div>
                                          {{ item }}
                                     </li>
                                </ul>
                           </div>
                      </section>
                 </div>

                 <!-- Right: Action Center -->
                 <div class="hidden lg:block lg:sticky lg:top-40 h-fit space-y-12">
                      <div class="bg-[#111009] border border-white/10 rounded-sm p-6 md:p-12 space-y-12 md:space-y-16 shadow-2xl relative overflow-hidden group">
                           <div class="absolute -right-20 -top-20 w-64 h-64 bg-safari-gold/5 rounded-full blur-3xl group-hover:bg-safari-gold/10 transition-all duration-1000"></div>
                           
                           
                           <form @submit.prevent="step === 1 ? step = 2 : submitBooking()" class="relative z-10">
                                 
                                 <div v-if="step === 1" class="space-y-6">
                                      <div class="space-y-4">
                                           <p class="text-sm font-black uppercase tracking-[0.5em] opacity-80 text-white">Initial Reservation</p>
                                           <div class="flex items-baseline gap-3">
                                                <h2 class="font-display text-2xl md:text-4xl font-black uppercase tracking-tighter text-safari-gold">{{ formatPrice(tour.price) }}</h2>
                                                <span class="text-xs font-black opacity-70 uppercase tracking-[0.2em] italic text-white">/ Person</span>
                                           </div>
                                      </div>

                                      <template v-if="auth.user">
                                           <div class="grid grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                     <label class="text-[10px] md:text-xs font-black uppercase tracking-widest opacity-80 text-white">Recon Date</label>
                                                     <input type="date" v-model="bookingForm.start_date" class="w-full bg-white/5 border border-white/10 rounded-sm p-3.5 md:p-4 text-[10px] focus:border-safari-gold transition-all text-white outline-none appearance-none" required />
                                                </div>
                                                <div class="space-y-2">
                                                     <label class="text-[10px] md:text-xs font-black uppercase tracking-widest opacity-80 text-white">Party Max</label>
                                                     <input type="number" v-model="bookingForm.guests" min="1" max="15" class="w-full bg-white/5 border border-white/10 rounded-sm p-3.5 md:p-4 text-[10px] focus:border-safari-gold transition-all text-white outline-none" required />
                                                </div>
                                           </div>

                                           <div class="grid grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                     <label class="text-[10px] md:text-xs font-black uppercase tracking-widest opacity-80 text-white">Scout Name</label>
                                                     <input type="text" v-model="bookingForm.name" placeholder="John Doe" class="w-full bg-white/5 border border-white/10 rounded-sm p-3.5 md:p-4 text-[10px] focus:border-safari-gold transition-all text-white outline-none" required />
                                                </div>
                                                <div class="space-y-2">
                                                     <label class="text-[10px] md:text-xs font-black uppercase tracking-widest opacity-80 text-white">Relay Comm</label>
                                                     <input type="email" v-model="bookingForm.email" placeholder="john@example.com" class="w-full bg-white/5 border border-white/10 rounded-sm p-3.5 md:p-4 text-[10px] focus:border-safari-gold transition-all text-white outline-none" required />
                                                </div>
                                           </div>

                                           <div class="space-y-2">
                                                <label class="text-xs font-black uppercase tracking-widest opacity-80 text-white">Optional: Attach Fleet Unit</label>
                                                <select v-model="bookingForm.fleet_id" class="w-full bg-black/40 border border-white/10 rounded-sm p-5 text-sm font-black uppercase tracking-widest focus:border-safari-gold transition-all text-white outline-none appearance-none shadow-inner">
                                                     <option value="" disabled>Deploy Without Vehicle</option>
                                                     <option :value="''">None (Self-Deployed)</option>
                                                     <option v-for="vehicle in (page.props.fleet as any[])" :key="vehicle.id" :value="vehicle.id">
                                                          {{ vehicle.name }} ({{ vehicle.capacity }} seats)
                                                     </option>
                                                </select>
                                           </div>

                                           <div v-if="bookingForm.fleet_id" class="space-y-4 animate-in fade-in zoom-in duration-300">
                                                <label class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold/80 ml-1">Fleet Deployment Protocol</label>
                                                <div class="space-y-3">
                                                     <button type="button" 
                                                         @click="bookingForm.fleet_pricing_model = 'hourly'"
                                                         :class="['group/opt w-full flex items-center justify-between p-6 border rounded-sm transition-all duration-300', bookingForm.fleet_pricing_model === 'hourly' ? 'border-safari-gold bg-safari-gold/20 shadow-[0_0_30px_rgba(212,175,55,0.15)] ring-1 ring-safari-gold/30' : 'border-white/10 bg-white/[0.03] hover:border-white/30 hover:bg-white/[0.05]']"
                                                     >
                                                         <span :class="['text-[11px] font-black uppercase tracking-[0.2em] transition-colors', bookingForm.fleet_pricing_model === 'hourly' ? 'text-safari-gold' : 'text-white/60 group-hover/opt:text-white']">Hourly Recon</span>
                                                         <span :class="['text-xl font-display font-black tracking-tight transition-colors', bookingForm.fleet_pricing_model === 'hourly' ? 'text-white' : 'text-safari-gold/80 group-hover/opt:text-safari-gold']">{{ formatPrice((page.props.fleet as any[]).find(f => f.id === bookingForm.fleet_id)?.price_per_hour || 0) }}</span>
                                                     </button>
                                                     <button type="button" 
                                                         @click="bookingForm.fleet_pricing_model = 'daily'"
                                                         :class="['group/opt w-full flex items-center justify-between p-6 border rounded-sm transition-all duration-300', bookingForm.fleet_pricing_model === 'daily' ? 'border-safari-gold bg-safari-gold/20 shadow-[0_0_30px_rgba(212,175,55,0.15)] ring-1 ring-safari-gold/30' : 'border-white/10 bg-white/[0.03] hover:border-white/30 hover:bg-white/[0.05]']"
                                                     >
                                                         <span :class="['text-[11px] font-black uppercase tracking-[0.2em] transition-colors', bookingForm.fleet_pricing_model === 'daily' ? 'text-safari-gold' : 'text-white/60 group-hover/opt:text-white']">Daily Mission</span>
                                                         <span :class="['text-xl font-display font-black tracking-tight transition-colors', bookingForm.fleet_pricing_model === 'daily' ? 'text-white' : 'text-safari-gold/80 group-hover/opt:text-safari-gold']">{{ formatPrice((page.props.fleet as any[]).find(f => f.id === bookingForm.fleet_id)?.price_per_day || 0) }}</span>
                                                     </button>
                                                     <button type="button" 
                                                         @click="bookingForm.fleet_pricing_model = 'safari'"
                                                         :class="['group/opt w-full flex items-center justify-between p-6 border rounded-sm transition-all duration-300', bookingForm.fleet_pricing_model === 'safari' ? 'border-safari-gold bg-safari-gold/20 shadow-[0_0_30px_rgba(212,175,55,0.15)] ring-1 ring-safari-gold/30' : 'border-white/10 bg-white/[0.03] hover:border-white/30 hover:bg-white/[0.05]']"
                                                     >
                                                         <span :class="['text-[11px] font-black uppercase tracking-[0.2em] transition-colors', bookingForm.fleet_pricing_model === 'safari' ? 'text-safari-gold' : 'text-white/60 group-hover/opt:text-white']">Full Safari</span>
                                                         <span :class="['text-xl font-display font-black tracking-tight transition-colors', bookingForm.fleet_pricing_model === 'safari' ? 'text-white' : 'text-safari-gold/80 group-hover/opt:text-safari-gold']">{{ formatPrice((page.props.fleet as any[]).find(f => f.id === bookingForm.fleet_id)?.price_per_safari_drive || 0) }}</span>
                                                     </button>
                                                </div>
                                                <input type="hidden" v-model="bookingForm.fleet_pricing_model" required />
                                           </div>

                                           <div class="pt-8 border-t border-white/10 space-y-4">
                                                <div class="flex items-center justify-between">
                                                     <div class="space-y-1">
                                                          <p class="text-xs font-black uppercase tracking-widest opacity-80 text-white">Est. Total Investment</p>
                                                          <p class="text-[10px] font-black uppercase tracking-widest text-safari-gold/50">Base + Fleet Selection</p>
                                                     </div>
                                                     <p class="text-2xl md:text-3xl font-display font-black text-safari-gold tracking-tighter">{{ formatPrice(totalInvestment) }}</p>
                                                </div>
                                                <button type="submit" class="w-full bg-safari-gold text-black py-4 md:py-6 text-[10px] md:text-sm font-black uppercase tracking-[0.4em] hover:bg-white transition-all transform hover:-translate-y-1 shadow-2xl rounded-sm">
                                                     PROCEED TO DEPOSIT
                                                </button>
                                           </div>
                                      </template>

                                      <template v-else>
                                           <div class="pt-8 border-t border-white/5 space-y-6 animate-in fade-in">
                                                <p class="text-xs font-black uppercase tracking-[0.25em] text-safari-gold/80 italic leading-relaxed">
                                                     MISSION INTEL: Authentication is required to authorize expedition deployments and secure your fleet logistics.
                                                </p>
                                                <div class="h-[1px] w-full bg-white/5"></div>
                                                <button 
                                                    type="button" 
                                                    @click="showToast('AUTHENTICATION REQUIRED: Please login or register to authorize this checkout protocol.', 'error')" 
                                                    class="w-full bg-safari-gold text-black py-6 text-sm font-black uppercase tracking-[0.4em] hover:bg-white transition-all transform hover:-translate-y-1 shadow-2xl rounded-sm"
                                                >
                                                     PROCEED TO CHECKOUT
                                                </button>
                                           </div>
                                      </template>
                                 </div>

                                <div v-if="step === 2" class="space-y-8 animate-in fade-in slide-in-from-right">

                                     <div class="p-6 bg-white/[0.03] border border-white/10 rounded-sm space-y-4">
                                          <div class="flex items-center justify-between opacity-60">
                                               <span class="text-[10px] font-black uppercase tracking-widest">Tour Deposit (10%)</span>
                                               <span class="text-xs font-black">{{ formatPrice((parseFloat(tour.price) * bookingForm.guests) * 0.1) }}</span>
                                          </div>
                                          <div v-if="bookingForm.fleet_id && bookingForm.fleet_pricing_model" class="flex items-center justify-between opacity-60">
                                               <span class="text-[10px] font-black uppercase tracking-widest">Fleet Protocol (100%)</span>
                                               <span class="text-xs font-black">{{ formatPrice(bookingForm.fleet_pricing_model === 'hourly' ? (page.props.fleet as any[]).find(f => f.id == bookingForm.fleet_id)?.price_per_hour : (bookingForm.fleet_pricing_model === 'daily' ? (page.props.fleet as any[]).find(f => f.id == bookingForm.fleet_id)?.price_per_day : (page.props.fleet as any[]).find(f => f.id == bookingForm.fleet_id)?.price_per_safari_drive)) }}</span>
                                          </div>
                                          <div class="h-[1px] bg-white/10 my-1"></div>
                                          <div class="flex items-center justify-between">
                                               <span class="text-xs font-black uppercase tracking-[0.3em] text-safari-gold">Grand Authorization</span>
                                               <span class="text-2xl font-display font-black text-white tracking-tighter">{{ formatPrice(calculatedDeposit) }}</span>
                                          </div>
                                     </div>
                                     <button @click="step = 1" type="button" class="text-xs font-black tracking-widest uppercase text-white/40 hover:text-white pb-4 border-b border-white/5 w-full text-left">← Return to Recon</button>

                                     <div class="bg-safari-gold/5 border border-safari-gold/20 p-6 rounded-sm space-y-4">
                                          <h4 class="text-sm font-black uppercase tracking-widest text-safari-gold">Required Upfront Deposit (10% Tour + Full Fleet)</h4>
                                          <p class="text-3xl font-black text-white tracking-tighter">{{ formatPrice(calculatedDeposit) }}</p>
                                     </div>

                                     <div class="space-y-2">
                                          <label class="text-xs font-black uppercase tracking-widest opacity-80 text-white">Payment Method</label>
                                          <select v-model="bookingForm.payment_method" class="w-full bg-black/40 border border-white/10 rounded-sm p-5 text-sm font-black uppercase tracking-widest focus:border-safari-gold transition-all text-white outline-none appearance-none shadow-inner">
                                               <option value="mpesa">Lipa na M-Pesa</option>
                                               <option value="visa">Credit / Visa Authorized</option>
                                          </select>
                                     </div>

                                     <div v-if="bookingForm.payment_method === 'mpesa'" class="space-y-2 animate-in fade-in">
                                          <label class="text-xs font-black uppercase tracking-widest opacity-80 text-white">Safaricom Terminal (Phone)</label>
                                          <input type="text" v-model="bookingForm.phone" placeholder="2547XXXXXXXX" class="w-full bg-white/5 border border-white/10 rounded-sm p-4 text-sm font-light tracking-[0.2em] text-white outline-none focus:border-green-500" required />
                                          <p v-if="bookingForm.errors.phone" class="text-xs text-red-400 uppercase tracking-widest font-black mt-2">{{ bookingForm.errors.phone }}</p>
                                          <p v-if="bookingForm.errors.payment_method" class="text-xs text-red-500 uppercase tracking-widest font-black mt-2">{{ bookingForm.errors.payment_method }}</p>
                                     </div>

                                     <button type="submit" :disabled="bookingForm.processing" class="w-full bg-safari-gold text-black py-6 mt-4 text-sm font-black uppercase tracking-[0.4em] hover:bg-white transition-all transform shadow-2xl rounded-sm">
                                          {{ bookingForm.processing ? 'PINGING DARAJA...' : 'AUTHORIZE PAYMENT' }}
                                     </button>
                                </div>

                           </form>
                           <div class="flex items-center justify-center gap-4 opacity-70 group cursor-default border-t border-white/5 mt-8 pt-8 relative z-10">
                                <ShieldCheck class="w-4 h-4 group-hover:text-green-500 transition-colors" />
                                <span class="text-xs font-black uppercase tracking-[0.3em] text-white">Encrypted Pulse Protocol Secured</span>
                           </div>
                      </div>

                      <!-- Heritage Guarantee -->
                      <div class="p-12 border border-white/5 bg-white/[0.02] rounded-sm space-y-8 relative overflow-hidden group hover:border-safari-gold/20 transition-all duration-700 shadow-xl">
                           <div class="flex items-center gap-6">
                                <div class="h-12 w-12 rounded-full border border-white/5 flex items-center justify-center bg-black shadow-inner">
                                     <Globe class="w-5 h-5 text-safari-gold opacity-80" />
                                </div>
                                <h3 class="font-display text-2xl font-black uppercase tracking-tighter text-white">Big Simba <br/>Pledge.</h3>
                           </div>
                           <p class="text-sm opacity-80 uppercase tracking-[0.2em] leading-relaxed italic font-light text-white/50">
                                "Your investment is secured by our heritage guarantee. Every expedition is fully vetted and operated by local masters who have traversed these trails for generations."
                           </p>
                      </div>
                 </div>
            </div>

            <!-- Reviews Section -->
            <section class="space-y-16 md:space-y-24 mb-24 md:mb-44">
                 <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-white/5 pb-12 md:pb-16 gap-8">
                      <div class="space-y-4">
                           <h2 class="font-display text-4xl md:text-6xl font-black uppercase tracking-tighter leading-none text-white">Witness <br/>Reports</h2>
                           <p class="text-[9px] md:text-sm opacity-80 uppercase tracking-[0.5em] font-black italic text-white/40">- VERIFIED FEEDBACK FROM PREVIOUS EXPLORERS //</p>
                      </div>
                      <div class="flex items-center gap-4 md:gap-8 bg-white/[0.02] p-5 md:p-8 border border-white/5 rounded-sm shadow-xl">
                           <div class="text-right">
                                <p class="text-[10px] md:text-sm font-black uppercase tracking-[0.4em] opacity-70 whitespace-nowrap text-white">Expedition Rank</p>
                                <div class="flex items-center gap-1 justify-end mt-2">
                                     <Star v-for="i in 5" :key="i" class="w-3 h-3 text-safari-gold fill-safari-gold shadow-gold-sm" />
                                </div>
                           </div>
                           <div class="h-10 md:h-12 w-[1px] bg-white/10"></div>
                           <div class="flex items-center gap-3 md:gap-4">
                                <span class="text-4xl md:text-6xl font-black text-safari-gold leading-none">{{ tour.reviews?.length || 0 }}</span>
                                <span class="text-[10px] md:text-sm font-black uppercase tracking-[0.2em] opacity-70 leading-tight text-white">Total <br class="hidden md:block"/> Voices</span>
                           </div>
                      </div>
                 </div>

                 <div v-if="tour.reviews?.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 md:gap-x-24 gap-y-12 md:gap-y-20">
                      <div v-for="review in tour.reviews" :key="review.id" class="space-y-6 md:space-y-8 group bg-white/[0.01] p-6 md:p-12 border border-transparent hover:border-white/5 rounded-sm transition-all duration-500 shadow-2xl">
                           <div class="flex items-center justify-between">
                                <div class="flex items-center gap-6">
                                     <div class="w-16 h-16 bg-black border border-white/5 rounded-full flex items-center justify-center group-hover:border-safari-gold/40 transition-all shadow-xl">
                                          <span class="text-[14px] font-black uppercase tracking-[0.2em] text-safari-gold">{{ review.user?.name.charAt(0) }}</span>
                                     </div>
                                     <div class="space-y-2">
                                          <p class="text-lg font-black uppercase tracking-tight text-white capitalize">{{ review.user?.name }}</p>
                                          <div class="flex items-center gap-1">
                                               <Star v-for="i in 5" :key="i" :class="['w-3 h-3', i <= review.rating ? 'text-safari-gold fill-safari-gold' : 'text-white/5']" />
                                          </div>
                                     </div>
                                </div>
                                <span class="text-xs font-bold opacity-20 uppercase tracking-[0.5em] hidden md:block text-white">Verified Explorer</span>
                           </div>
                           <p class="text-base md:text-lg font-normal text-gray-300 leading-relaxed border-l-2 border-white/5 pl-6 md:pl-10 group-hover:border-safari-gold/40 transition-all duration-700 text-white/70">"{{ review.comment }}"</p>
                      </div>
                 </div>

                 <div v-else class="py-32 text-center border border-dashed border-white/5 rounded-sm opacity-20">
                      <p class="text-base font-black uppercase tracking-[1em] text-white">The records are silent</p>
                 </div>


            </section>
        </main>
    </component>
</template>

<style scoped>
main {
    font-family: 'Outfit', sans-serif;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.shadow-gold-sm {
    filter: drop-shadow(0 0 5px rgba(202, 138, 4, 0.3));
}
.mask-gradient {
    mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
}
</style>

