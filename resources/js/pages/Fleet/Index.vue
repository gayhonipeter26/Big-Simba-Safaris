<script setup lang="ts">
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Car, Users, Settings, ShieldCheck, Mail, Phone, Calendar, MessageSquare, Briefcase, ChevronRight, X, Clock, UserCheck, Target, DollarSign, Lock, CreditCard, ArrowLeft } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { useToast } from '@/composables/useToast';
import { router } from '@inertiajs/vue3';

import { useCurrency } from '@/composables/useCurrency';
const { formatPrice } = useCurrency();
const props = defineProps<{
    fleet: any[];
}>();

const page = usePage<any>();
const layout = computed(() => (page.props.auth?.user || page.url.startsWith('/dashboard')) ? AppHeaderLayout : GuestLayout);

const selectedVehicle = ref<any>(null);

const form = useForm({
    fleet_id: '',
    name: (page.props as any).auth?.user?.name || '',
    email: (page.props as any).auth?.user?.email || '',
    phone: '',
    start_date: '',
    end_date: '',
    message: '',
    payment_method: 'mpesa',
    pricing_model: '',
});

const step = ref(1);

const { show: showToast } = useToast();

const openHireModal = (vehicle: any) => {
    if (!page.props.auth?.user) {
        showToast('Authentication Required: Please login or register to protocol logistics induction.', 'error', 5000);
        return;
    }
    selectedVehicle.value = vehicle;
    form.fleet_id = vehicle.id;
};

const closeHireModal = () => {
    selectedVehicle.value = null;
    form.reset();
    step.value = 1;
};

const nextStep = () => {
    if (!form.pricing_model) {
        showToast('STRATEGIC OMISSION: SELECT A PRICING PROTOCOL TO PROCEED', 'error');
        return;
    }
    step.value = 2;
};

const submit = () => {
    form.post(route('fleet-hire.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeHireModal();
            showToast(
                'Payment verified! Your deployment is authorized and an email has been sent to you.',
                'success',
                8000
            );
        }
    });
};
</script>

<template>
    <Head title="Tactical Mobility - Elite Safari Fleet">
        <meta name="description" content="Command the Kenyan savanna with Big Simba Safaris' elite fleet. Precision-engineered 4x4 Land Cruisers and tactical safari vehicles available for hire." />
        <meta property="og:title" content="Tactical Mobility - Big Simba Safari Fleet" />
        <meta property="og:description" content="Independent reconnaissance. Hire our elite 4x4 safari fleet for your next Kenyan mission." />
        <meta name="keywords" content="Safari Car Hire Kenya, 4x4 Land Cruiser Hire, Safari Fleet, Big Simba Logistics, Kenya Self Drive Safari" />
        <component :is="'script'" type="application/ld+json" v-html="JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'ItemList',
          'itemListElement': fleet.map((v, i) => ({
            '@type': 'ListItem',
            'position': i + 1,
            'name': v.name,
            'description': v.drive_type + ' safari vehicle'
          }))
        })"></component>
    </Head>

    <component :is="layout" :full-bleed="true">
        <main class="bg-[#050505] min-h-screen py-16 md:py-44 px-4 md:px-20 max-w-[1800px] mx-auto">
            <!-- Hero -->
            <div class="mb-16 md:mb-24 space-y-4 md:space-y-6 pt-24 lg:pt-0 px-2 md:px-0">
                <Link :href="page.props.auth?.user ? route('dashboard') : route('home')" class="inline-flex items-center gap-2 text-[10px] md:text-xs font-black uppercase tracking-[0.2em] text-safari-gold/60 hover:text-safari-gold transition-all mb-2 md:mb-4 group/back">
                     <ArrowLeft class="w-3 h-3 group-hover/back:-translate-x-1.5 transition-transform" /> {{ page.props.auth?.user ? 'Back to Dashboard' : 'Back to Welcome' }}
                </Link>
                <p class="text-[9px] md:text-sm font-black uppercase tracking-[0.5em] text-safari-gold animate-in fade-in slide-in-from-left duration-1000 opacity-80">- TACTICAL MOBILITY //</p>
                <h1 class="font-display text-4xl md:text-9xl font-black uppercase tracking-tighter leading-[0.75] text-white">Hire Our <br class="hidden md:block"/><span class="text-safari-gold/20">Fleet.</span></h1>
                <p class="text-lg md:text-xl font-light opacity-70 max-w-3xl leading-relaxed italic text-white/50">
                     Uncompromising logistics for the independent explorer. Command the wilderness with our precision-engineered safari fleet.
                </p>
            </div>

            <!-- Fleet Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 px-2 lg:px-0 text-white">
                 <div v-for="vehicle in fleet" :key="vehicle.id" class="group bg-white/[0.01] border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-1000 flex flex-col md:flex-row shadow-2xl">
                    <div class="md:w-[45%] h-[150px] md:h-[320px] lg:h-auto shrink-0 relative overflow-hidden bg-black">
                           <img :src="vehicle.image" class="w-full h-full object-cover transition-transform duration-[3000ms] group-hover:scale-110 opacity-70" />
                           <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent"></div>
                      </div>
                      <div class="md:w-[55%] p-4 md:p-10 lg:p-12 flex flex-col justify-between space-y-4 md:space-y-8 text-white">
                           <div class="space-y-3 md:space-y-6">
                                <div class="flex items-center justify-between">
                                     <span class="text-[9px] md:text-xs font-black uppercase tracking-[0.4em] text-safari-gold">{{ vehicle.drive_type }}</span>
                                     <div class="flex items-center gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                                          <Users class="w-3 h-3" />
                                          <span class="text-[10px] md:text-sm font-black uppercase tracking-widest">{{ vehicle.capacity }} SEATS</span>
                                     </div>
                                </div>
                                <h3 class="font-display text-xl md:text-3xl font-black uppercase tracking-tighter text-white">{{ vehicle.name }}</h3>
                                <div class="flex flex-wrap gap-1.5 md:gap-2 mb-4">
                                     <span v-for="feat in (vehicle.features?.split(',') || [])" :key="feat" class="px-1.5 py-0.5 md:px-2 md:py-1 bg-white/5 text-[9px] md:text-xs font-bold uppercase tracking-widest text-white/60 border border-white/5">
                                          {{ feat.trim() }}
                                     </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/5">
                                     <div v-if="vehicle.drivers" class="flex items-center gap-3">
                                          <UserCheck class="w-3.5 h-3.5 text-safari-gold opacity-90" />
                                          <span class="text-xs font-black uppercase tracking-widest opacity-80">{{ vehicle.drivers }}</span>
                                     </div>
                                     <div v-if="vehicle.availability" class="flex items-center gap-3">
                                          <Clock class="w-3.5 h-3.5 text-safari-gold opacity-90" />
                                          <span class="text-xs font-black uppercase tracking-widest opacity-80">{{ vehicle.availability }}</span>
                                     </div>
                                     <div v-if="vehicle.suited_for" class="flex items-center gap-3 col-span-2">
                                          <Target class="w-3.5 h-3.5 text-safari-gold opacity-90" />
                                          <span class="text-xs font-black uppercase tracking-widest opacity-80">Best for: {{ vehicle.suited_for }}</span>
                                     </div>
                                </div>
                           </div>

                           <div class="space-y-4 md:space-y-6">
                                <div class="grid grid-cols-3 gap-1 md:gap-2 border-y border-white/5 py-2 md:py-4">
                                     <div class="text-center">
                                          <p class="text-[6px] md:text-[7px] font-black opacity-70 uppercase tracking-widest mb-1">Per Hour</p>
                                          <p class="text-[9px] md:text-xs font-black text-safari-gold">Ksh {{ parseFloat(vehicle.price_per_hour).toLocaleString() }}</p>
                                     </div>
                                     <div class="text-center border-x border-white/5">
                                          <p class="text-[6px] md:text-[7px] font-black opacity-70 uppercase tracking-widest mb-1">Per Day</p>
                                          <p class="text-[9px] md:text-xs font-black text-safari-gold">Ksh {{ parseFloat(vehicle.price_per_day).toLocaleString() }}</p>
                                     </div>
                                     <div class="text-center">
                                          <p class="text-[6px] md:text-[7px] font-black opacity-70 uppercase tracking-widest mb-1">Safari</p>
                                          <p class="text-[9px] md:text-xs font-black text-safari-gold">Ksh {{ parseFloat(vehicle.price_per_safari_drive).toLocaleString() }}</p>
                                     </div>
                                </div>
                                <button @click="openHireModal(vehicle)" class="w-full py-2.5 md:py-4 border border-white/10 text-[9px] md:text-sm font-black uppercase tracking-[0.4em] hover:bg-safari-gold hover:text-black hover:border-safari-gold transition-all rounded-sm">
                                     REQUEST INDUCTION
                                </button>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Why Big Simba Logistics -->
            <section class="mt-24 md:mt-44 grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 py-24 md:py-32 border-y border-white/5">
                <div v-for="(item, i) in [
                     { icon: Settings, title: 'Precision Serviced', desc: 'Each machine undergoes a 48-point tactical inspection before every deployment.' },
                     { icon: ShieldCheck, title: 'Wild Insurance', desc: 'Comprehensive field coverage ensuring your mission remains uncompromised.' },
                     { icon: Briefcase, title: 'Logistics Support', desc: '24/7 communications relay for emergency extraction or mechanical guidance.' }
                ]" :key="i" class="space-y-6 p-8 bg-white/[0.01] border border-transparent hover:border-white/5 rounded-sm transition-all shadow-xl group">
                     <div class="w-14 h-14 rounded-full border border-white/5 flex items-center justify-center bg-black group-hover:border-safari-gold transition-all duration-700">
                          <component :is="item.icon" class="w-5 h-5 text-safari-gold opacity-80 group-hover:opacity-100 transition-opacity" />
                     </div>
                     <h4 class="font-display text-xl font-black uppercase tracking-tight text-white">{{ item.title }}</h4>
                     <p class="text-[13px] font-normal text-gray-300 leading-relaxed text-white/60">{{ item.desc }}</p>
                </div>
           </section>
        </main>

        <!-- Hire Modal -->
        <Transition enter-active-class="transition duration-500 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-300 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
             <div v-if="selectedVehicle" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-4 md:p-6 bg-black/95 backdrop-blur-sm">
                  <div class="bg-card border border-white/10 w-full max-h-[85vh] lg:max-h-[90vh] max-w-7xl rounded-md sm:rounded-sm overflow-hidden flex flex-col lg:flex-row shadow-[0_0_100px_rgba(202,138,4,0.1)]">
                       <!-- Side Panel -->
                       <div class="lg:w-2/5 shrink-0 relative h-52 md:h-80 lg:h-auto bg-black">
                            <img :src="selectedVehicle.image" class="absolute inset-0 w-full h-full object-cover opacity-90" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                            <div class="absolute bottom-3 left-4 right-4 md:bottom-12 md:left-12 md:right-12 space-y-1 md:space-y-6">
                                 <p class="text-[7px] md:text-sm font-black uppercase tracking-[0.5em] text-safari-gold flex items-center gap-2"><Lock class="w-3 h-3 hidden md:block"/> Selected Unit</p>
                                 <h2 class="font-display text-xl md:text-5xl font-black uppercase tracking-tighter text-white leading-none">{{ selectedVehicle.name }}</h2>
                                  <div class="hidden lg:block space-y-6">
                                       <div class="grid grid-cols-1 gap-4 opacity-70">
                                            <div v-if="selectedVehicle.drivers" class="flex items-center gap-4">
                                                 <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center border border-white/10 text-safari-gold">
                                                      <UserCheck class="w-3.5 h-3.5" />
                                                 </div>
                                                 <div class="flex flex-col">
                                                      <span class="text-xs font-black uppercase tracking-widest opacity-80">Personnel</span>
                                                      <span class="text-sm font-black uppercase tracking-widest text-white">{{ selectedVehicle.drivers }}</span>
                                                 </div>
                                            </div>
                                            <div v-if="selectedVehicle.suited_for" class="flex items-center gap-4">
                                                 <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center border border-white/10 text-safari-gold">
                                                      <Target class="w-3.5 h-3.5" />
                                                 </div>
                                                 <div class="flex flex-col">
                                                      <span class="text-xs font-black uppercase tracking-widest opacity-80">Optimized For</span>
                                                      <span class="text-sm font-black uppercase tracking-widest text-white">{{ selectedVehicle.suited_for }}</span>
                                                 </div>
                                            </div>
                                            <div v-if="selectedVehicle.availability" class="flex items-center gap-4">
                                                 <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center border border-white/10 text-safari-gold">
                                                      <Clock class="w-3.5 h-3.5" />
                                                 </div>
                                                 <div class="flex flex-col">
                                                      <span class="text-xs font-black uppercase tracking-widest opacity-80">Deployment Window</span>
                                                      <span class="text-sm font-black uppercase tracking-widest text-white">{{ selectedVehicle.availability }}</span>
                                                 </div>
                                            </div>
                                       </div>

                                       <div class="pt-8 border-t border-white/5">
                                            <p class="text-sm font-black uppercase tracking-[0.5em] text-safari-gold mb-4">Induction Rates</p>
                                            <div class="grid grid-cols-1 gap-3">
                                                 <div class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-sm">
                                                      <span class="text-xs font-black uppercase tracking-widest opacity-80">Hourly Recon</span>
                                                      <span class="text-xs font-black text-safari-gold">{{ formatPrice(selectedVehicle.price_per_hour) }}</span>
                                                 </div>
                                                 <div class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-sm">
                                                      <span class="text-xs font-black uppercase tracking-widest opacity-80">Daily Deployment</span>
                                                      <span class="text-xs font-black text-safari-gold">{{ formatPrice(selectedVehicle.price_per_day) }}</span>
                                                 </div>
                                                 <div class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-sm">
                                                      <span class="text-xs font-black uppercase tracking-widest opacity-80">Full Safari Mission</span>
                                                      <span class="text-xs font-black text-safari-gold">{{ formatPrice(selectedVehicle.price_per_safari_drive) }}</span>
                                                 </div>
                                            </div>
                                       </div>
                                  </div>
                             </div>
                        </div>

                       <!-- Form -->
                       <div class="lg:w-3/5 p-3 py-4 md:p-8 lg:p-12 relative overflow-y-auto w-full bg-[#0a0a0a] lg:bg-transparent modal-scrollbar">
                            <button @click="closeHireModal" class="absolute top-3 right-3 md:top-10 md:right-10 p-1.5 md:p-3 border border-white/5 bg-white/5 rounded-full hover:bg-white/10 transition-all text-white z-50">
                                 <X class="w-3.5 h-3.5 md:w-5 md:h-5" />
                            </button>

                            <div class="space-y-4 md:space-y-8 mt-1 md:mt-0">
                                 <div class="space-y-1 md:space-y-4">
                                      <h3 class="font-display text-lg md:text-3xl font-black uppercase tracking-tighter text-white pr-8">Deployment Registry</h3>
                                      <p class="text-[9px] md:text-sm opacity-70 uppercase tracking-[0.4em] italic font-bold text-white/50">Establish your mission parameters</p>
                                 </div>

                                  <form @submit.prevent="step === 1 ? nextStep() : submit()" class="space-y-4 md:space-y-6">
                                       
                                       <div v-show="step === 1" class="space-y-3 md:space-y-10 animate-in fade-in slide-in-from-bottom">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-8">
                                                 <div class="space-y-1 md:space-y-4">
                                                      <label class="text-[9px] md:text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">Identity Name</label>
                                                      <div class="relative">
                                                           <input v-model="form.name" type="text" placeholder="Scout Identifier" class="w-full bg-white/5 border border-white/10 rounded-sm px-3 md:px-6 py-2 md:py-4 text-[10px] md:text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" :required="step === 1" />
                                                      </div>
                                                 </div>
                                                 <div class="space-y-1 md:space-y-4">
                                                      <label class="text-[9px] md:text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">Communications Relay</label>
                                                      <div class="relative">
                                                           <input v-model="form.email" type="email" placeholder="Email Address" class="w-full bg-white/5 border border-white/10 rounded-sm px-3 md:px-6 py-2 md:py-4 text-[10px] md:text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" :required="step === 1" />
                                                      </div>
                                                 </div>
                                            </div>

                                            <div class="space-y-1 md:space-y-4">
                                                 <label class="text-[9px] md:text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">Tactical Contact (Phone)</label>
                                                 <div class="relative">
                                                      <input v-model="form.phone" type="text" placeholder="+254..." class="w-full bg-white/5 border border-white/10 rounded-sm px-3 md:px-6 py-2 md:py-4 text-[10px] md:text-sm font-light tracking-[0.2em] outline-none focus:border-safari-gold transition-all text-white" :required="step === 1" />
                                                 </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-2 md:gap-8">
                                                 <div class="space-y-1 md:space-y-4">
                                                      <label class="text-[9px] md:text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">Start</label>
                                                      <input v-model="form.start_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-sm px-3 md:px-6 py-2 md:py-4 text-[10px] md:text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" :required="step === 1" />
                                                 </div>
                                                 <div class="space-y-1 md:space-y-4">
                                                      <label class="text-[9px] md:text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">End</label>
                                                      <input v-model="form.end_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-sm px-3 md:px-6 py-2 md:py-4 text-[10px] md:text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" :required="step === 1" />
                                                 </div>
                                            </div>


                                            
                                             <div class="space-y-4 md:space-y-6 animate-in fade-in zoom-in duration-500">
                                                  <label class="text-[9px] md:text-[11px] font-black uppercase tracking-[0.5em] text-safari-gold ml-1">Fleet Deployment Protocol</label>
                                                  <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4">
                                                       <button type="button" 
                                                           @click="form.pricing_model = 'hourly'"
                                                           :class="['group/opt flex flex-col items-center justify-center p-3 md:p-8 border rounded-sm transition-all duration-300', form.pricing_model === 'hourly' ? 'border-safari-gold bg-safari-gold/20 shadow-[0_0_30px_rgba(212,175,55,0.15)] ring-1 ring-safari-gold/30' : 'border-white/10 bg-white/[0.03] hover:border-white/30 hover:bg-white/[0.05]']"
                                                       >
                                                           <span :class="['text-[8px] md:text-[11px] font-black uppercase tracking-[0.2em] transition-colors mb-1 md:mb-3', form.pricing_model === 'hourly' ? 'text-safari-gold' : 'text-white/60 group-hover/opt:text-white']">Hourly Recon</span>
                                                           <span :class="['text-lg md:text-2xl font-display font-black tracking-tight transition-colors', form.pricing_model === 'hourly' ? 'text-white' : 'text-safari-gold/80 group-hover/opt:text-safari-gold']">{{ formatPrice(selectedVehicle.price_per_hour) }}</span>
                                                       </button>
                                                       <button type="button" 
                                                           @click="form.pricing_model = 'daily'"
                                                           :class="['group/opt flex flex-col items-center justify-center p-3 md:p-8 border rounded-sm transition-all duration-300', form.pricing_model === 'daily' ? 'border-safari-gold bg-safari-gold/20 shadow-[0_0_30px_rgba(212,175,55,0.15)] ring-1 ring-safari-gold/30' : 'border-white/10 bg-white/[0.03] hover:border-white/30 hover:bg-white/[0.05]']"
                                                       >
                                                           <span :class="['text-[8px] md:text-[11px] font-black uppercase tracking-[0.2em] transition-colors mb-1 md:mb-3', form.pricing_model === 'daily' ? 'text-safari-gold' : 'text-white/60 group-hover/opt:text-white']">Daily Mission</span>
                                                           <span :class="['text-lg md:text-2xl font-display font-black tracking-tight transition-colors', form.pricing_model === 'daily' ? 'text-white' : 'text-safari-gold/80 group-hover/opt:text-safari-gold']">{{ formatPrice(selectedVehicle.price_per_day) }}</span>
                                                       </button>
                                                       <button type="button" 
                                                           @click="form.pricing_model = 'safari'"
                                                           :class="['group/opt flex flex-col items-center justify-center p-3 md:p-8 border rounded-sm transition-all duration-300', form.pricing_model === 'safari' ? 'border-safari-gold bg-safari-gold/20 shadow-[0_0_30px_rgba(212,175,55,0.15)] ring-1 ring-safari-gold/30' : 'border-white/10 bg-white/[0.03] hover:border-white/30 hover:bg-white/[0.05]']"
                                                       >
                                                           <span :class="['text-[8px] md:text-[11px] font-black uppercase tracking-[0.2em] transition-colors mb-1 md:mb-3', form.pricing_model === 'safari' ? 'text-safari-gold' : 'text-white/60 group-hover/opt:text-white']">Full Safari</span>
                                                           <span :class="['text-lg md:text-2xl font-display font-black tracking-tight transition-colors', form.pricing_model === 'safari' ? 'text-white' : 'text-safari-gold/80 group-hover/opt:text-safari-gold']">{{ formatPrice(selectedVehicle.price_per_safari_drive) }}</span>
                                                       </button>
                                                  </div>
                                                  <input type="hidden" v-model="form.pricing_model" required />
                                             </div>

                                             <div class="space-y-1 md:space-y-4">
                                                  <label class="text-[9px] md:text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">Mission Briefing</label>
                                                  <textarea v-model="form.message" rows="2" placeholder="SPECIFY YOUR MISSION REQUIREMENTS OR DESTINATIONS..." class="w-full bg-white/5 border border-white/10 rounded-sm p-3 md:p-6 text-[10px] md:text-sm font-light outline-none focus:border-safari-gold transition-all leading-relaxed uppercase placeholder:opacity-10 text-white"></textarea>
                                            </div>
                                       </div>

                                        <!-- Step 2: Payment Integration -->
                                        <div v-show="step === 2" class="space-y-4 md:space-y-10 animate-in fade-in slide-in-from-right">
                                             <div class="p-4 md:p-8 bg-white/5 border border-safari-gold/20 rounded-sm space-y-4 md:space-y-6">
                                                  <div class="space-y-1 md:space-y-2">
                                                       <p class="text-[9px] md:text-xs uppercase tracking-[0.3em] text-safari-gold font-black flex items-center gap-2"><Lock class="w-2.5 h-2.5 md:w-3 md:h-3"/> Secure Checkout</p>
                                                       <h4 class="font-display text-lg md:text-2xl text-white font-black tracking-tighter uppercase">Clearance Authorization</h4>
                                                       <p class="text-[9px] md:text-xs text-white/40 uppercase tracking-widest leading-relaxed">Select your payment protocol to finalize this logistics deployment.</p>
                                                  </div>
                                                  
                                                  <div class="p-4 md:p-8 bg-black/40 border border-white/10 rounded-sm space-y-3 md:space-y-4">
                                                       <div class="flex items-center justify-between opacity-60">
                                                            <span class="text-[8px] md:text-[10px] font-black uppercase tracking-[0.4em]">Selected Protocol</span>
                                                            <span class="text-[9px] md:text-xs font-black uppercase tracking-widest text-safari-gold">{{ form.pricing_model?.toUpperCase() }} MISSION</span>
                                                       </div>
                                                       <div class="h-[1px] bg-white/10"></div>
                                                       <div class="flex items-center justify-between">
                                                            <div class="space-y-1">
                                                                 <span class="text-[9px] md:text-xs font-black uppercase tracking-[0.3em] text-white/80">Authorized Total</span>
                                                                 <p class="text-[8px] md:text-[10px] uppercase tracking-widest text-white/30 italic">Strategic Logistics Rate</p>
                                                            </div>
                                                            <span class="text-xl md:text-3xl font-display font-black text-safari-gold tracking-tighter">{{ formatPrice(form.pricing_model === 'hourly' ? selectedVehicle.price_per_hour : (form.pricing_model === 'daily' ? selectedVehicle.price_per_day : selectedVehicle.price_per_safari_drive)) }}</span>
                                                       </div>
                                                  </div>
                                             <div class="grid grid-cols-2 gap-2 md:gap-4">
                                                 <label :class="`p-3 md:p-6 border rounded-sm cursor-pointer transition-all flex flex-col items-center gap-2 md:gap-4 ${form.payment_method === 'mpesa' ? 'border-safari-gold bg-safari-gold/5' : 'border-white/10 hover:border-white/30 bg-white/[0.02]'}`">
                                                      <input type="radio" v-model="form.payment_method" value="mpesa" class="hidden" />
                                                      <div class="w-8 h-8 md:w-12 md:h-12 bg-green-500/10 rounded-full flex items-center justify-center text-green-500">
                                                           <Phone class="w-3.5 h-3.5 md:w-5 md:h-5" />
                                                      </div>
                                                      <span class="text-[9px] md:text-xs font-black uppercase tracking-widest text-white">M-Pesa</span>
                                                 </label>
                                                 
                                                 <label :class="`p-3 md:p-6 border rounded-sm cursor-pointer transition-all flex flex-col items-center gap-2 md:gap-4 ${form.payment_method === 'visa' ? 'border-safari-gold bg-safari-gold/5' : 'border-white/10 hover:border-white/30 bg-white/[0.02]'}`">
                                                      <input type="radio" v-model="form.payment_method" value="visa" class="hidden" />
                                                      <div class="w-8 h-8 md:w-12 md:h-12 bg-blue-500/10 rounded-full flex items-center justify-center text-blue-500">
                                                           <CreditCard class="w-3.5 h-3.5 md:w-5 md:h-5" />
                                                      </div>
                                                      <span class="text-[9px] md:text-xs font-black uppercase tracking-widest text-white">Visa / Card</span>
                                                 </label>
                                            </div>

                                            <div v-if="form.payment_method === 'mpesa'" class="space-y-3 md:space-y-4 p-4 md:p-8 bg-black/40 border border-white/5 rounded-sm">
                                                 <label class="text-xs md:text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">M-Pesa Number</label>
                                                 <input v-model="form.phone" type="text" placeholder="2547XXXXXXXX" class="w-full bg-white/5 border border-white/10 rounded-sm px-4 py-4 md:px-6 md:py-5 text-xs md:text-sm font-light tracking-[0.2em] outline-none focus:border-green-500 transition-all text-white placeholder:text-white/20" :required="step === 2" />
                                                 <p class="text-[10px] md:text-xs text-green-500/60 uppercase tracking-widest font-black mt-2">A payment prompt will be sent to your phone.</p>
                                                 <p v-if="form.errors.phone" class="text-xs text-red-400 uppercase tracking-widest font-black mt-2">{{ form.errors.phone }}</p>
                                                 <p v-if="form.errors.payment_method" class="text-xs text-red-500 uppercase tracking-widest font-black mt-2">{{ form.errors.payment_method }}</p>
                                            </div>

                                            <div v-if="form.payment_method === 'visa'" class="space-y-4 p-5 md:p-8 bg-black/40 border border-white/5 rounded-sm">
                                                 <label class="text-xs md:text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">Card Number</label>
                                                 <div class="relative">
                                                      <input type="text" placeholder="XXXX XXXX XXXX XXXX" class="w-full bg-white/5 border border-white/10 rounded-sm px-4 py-4 md:px-6 md:py-5 text-xs md:text-sm font-light tracking-[0.2em] outline-none focus:border-blue-500 transition-all text-white placeholder:text-white/20" :required="step === 2" />
                                                 </div>
                                                 <div class="grid grid-cols-2 gap-4 mt-4">
                                                      <div>
                                                           <label class="text-[10px] md:text-xs font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">Expiry</label>
                                                           <input type="text" placeholder="MM/YY" class="w-full mt-2 bg-white/5 border border-white/10 rounded-sm px-4 py-3 md:px-6 md:py-4 text-xs font-light tracking-[0.2em] outline-none focus:border-blue-500 transition-all text-white placeholder:text-white/20" :required="step === 2" />
                                                      </div>
                                                      <div>
                                                           <label class="text-[10px] md:text-xs font-black uppercase tracking-[0.4em] opacity-80 ml-1 text-white">CVC</label>
                                                           <input type="text" placeholder="123" class="w-full mt-2 bg-white/5 border border-white/10 rounded-sm px-4 py-3 md:px-6 md:py-4 text-xs font-light tracking-[0.2em] outline-none focus:border-blue-500 transition-all text-white placeholder:text-white/20" :required="step === 2" />
                                                      </div>
                                                 </div>
                                            </div>
                                       </div>
                                       </div>

                                        <div class="pt-4 md:pt-8 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8 border-t border-white/10">
                                             <div class="flex items-center gap-3 md:gap-4 opacity-70">
                                                  <ShieldCheck class="w-4 h-4 md:w-5 md:h-5 text-safari-gold" />
                                                  <span class="text-[10px] md:text-xs font-black uppercase tracking-widest text-white">{{ step === 1 ? 'Phase 1 / 2' : 'Encrypted Payment Gateway' }}</span>
                                             </div>
                                             <div class="flex gap-3 md:gap-4 w-full md:w-auto">
                                                  <button v-if="step === 2" @click="step = 1" type="button" class="w-full md:w-auto px-4 md:px-8 py-3 md:py-6 text-[10px] md:text-base font-black uppercase tracking-[0.3em] text-white/40 hover:text-white transition-all bg-white/5 rounded-sm">
                                                       BACK
                                                  </button>
                                                  <button type="submit" :disabled="form.processing" class="w-full md:w-auto px-4 md:px-16 py-3 md:py-6 bg-safari-gold text-black text-[10px] md:text-base font-black uppercase tracking-[0.3em] md:tracking-[0.5em] hover:bg-white transition-all transform hover:-translate-y-1 shadow-2xl rounded-sm">
                                                       {{ form.processing ? (step === 1 ? 'PREPARING...' : 'PROCESSING...') : (step === 1 ? 'PROCEED TO PAYMENT' : 'AUTHORIZE PAYMENT') }}
                                                  </button>
                                             </div>
                                        </div>
                                  </form>
                            </div>
                       </div>
                  </div>
             </div>
        </Transition>
    </component>
</template>

<style scoped>
main { font-family: 'Outfit', sans-serif; }

/* Modal Scrollbar Styling */
.modal-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.modal-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.02);
    border-radius: 4px;
}
.modal-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(212, 175, 55, 0.5); /* Safari Gold */
    border-radius: 4px;
}
.modal-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(212, 175, 55, 0.8);
}
</style>
