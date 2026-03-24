<script setup lang="ts">
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Clock, MapPin, Search, ArrowRight, ArrowUpRight, Users, Gauge, Zap, UserCheck, Target, X, ShieldCheck } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps<{
    tours: any[];
    fleet: any[];
    filters: any;
}>();

const page = usePage<any>();
const auth = computed(() => page.props.auth);
const layout = computed(() => page.url.startsWith('/dashboard') ? AppHeaderLayout : GuestLayout);

const searchQuery = ref(props.filters?.search || '');

const formatPrice = (price: string | number) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(parseFloat(price.toString()));
};

const filteredTours = computed(() => {
    if (!searchQuery.value) return props.tours;
    return props.tours.filter(tour => 
        tour.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        tour.destination?.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const selectedVehicle = ref<any>(null);
const modalStep = ref(1);

const form = useForm({
    fleet_id: '',
    name: (page.props as any).auth?.user?.name || '',
    email: (page.props as any).auth?.user?.email || '',
    phone: '',
    start_date: '',
    end_date: '',
    message: '',
});

const openHireModal = (vehicle: any) => {
    selectedVehicle.value = vehicle;
    form.fleet_id = vehicle.id;
    modalStep.value = 1; // Reset to details
};

const closeHireModal = () => {
    selectedVehicle.value = null;
    form.reset();
    modalStep.value = 1;
};

const submit = () => {
    form.post(route('fleet-hire.store'), {
        preserveScroll: true,
        onSuccess: () => {
             closeHireModal();
        }
    });
};
</script>

<template>
    <Head title="Expedition Registry - Big Simba Safaris" />

    <component :is="layout" :full-bleed="true">
        <main class="max-w-[1800px] mx-auto px-8 md:px-20 py-20 md:py-44 min-h-screen bg-[#050505]">
            <!-- Immersive Header -->
            <header class="mb-24 space-y-8 flex flex-col md:flex-row md:items-end justify-between gap-12 pt-32 lg:pt-0">
                 <div class="space-y-4">
                      <p class="text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold">Safari Collection 2026</p>
                      <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-[0.85] text-white">Select Your <br/>Legend.</h1>
                 </div>
                 
                 <div class="relative w-full md:w-96 group">
                      <Search class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-safari-gold opacity-40 group-focus-within:opacity-100 transition-opacity" />
                      <input 
                           v-model="searchQuery"
                           type="text" 
                           placeholder="REGION OR EXPEDITION..." 
                           class="w-full bg-white/5 border border-white/10 rounded-sm pl-16 pr-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] focus:border-safari-gold transition-all outline-none text-white placeholder:opacity-20" 
                      />
                 </div>
            </header>

            <!-- Refined Grid -->
            <div v-if="filteredTours.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-24">
                <div 
                    v-for="tour in filteredTours" 
                    :key="tour.id"
                    class="group relative space-y-6"
                >
                    <Link :href="route(page.url.startsWith('/dashboard') ? 'user.tours.show' : 'tours.show', tour.slug)" class="block">
                         <div class="aspect-[4/5] overflow-hidden rounded-sm border border-white/5 relative bg-white/5 shadow-2xl">
                              <img 
                                   :src="tour.images?.[0] || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072'" 
                                   :alt="tour.name"
                                   class="absolute inset-0 h-full w-full object-cover grayscale-[0.3] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-1000"
                                   loading="lazy"
                              />
                              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent opacity-60"></div>
                              
                              <div class="absolute top-8 right-8 bg-black/60 backdrop-blur-md border border-white/10 px-5 py-2.5 rounded-sm">
                                   <p class="text-[11px] font-black text-safari-gold tracking-[0.2em]">{{ formatPrice(tour.price) }}</p>
                              </div>
                         </div>
                    </Link>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                             <div class="flex items-center gap-3 text-safari-gold/60">
                                  <MapPin class="w-3.5 h-3.5" />
                                  <span class="text-[10px] font-black tracking-[0.3em] uppercase">{{ tour.destination?.name || 'KENYA' }}</span>
                             </div>
                             <div class="flex items-center gap-2 text-[10px] font-bold opacity-30 uppercase tracking-[0.3em] text-white">
                                  <Clock class="w-3.5 h-3.5" /> {{ tour.duration }}
                             </div>
                        </div>
                        
                        <Link :href="route(page.url.startsWith('/dashboard') ? 'user.tours.show' : 'tours.show', tour.slug)" class="group/title block">
                             <h3 class="text-3xl font-black uppercase tracking-tight group-hover/title:text-safari-gold transition-colors underline decoration-transparent group-hover/title:decoration-safari-gold underline-offset-8 decoration-2 text-white">
                                  {{ tour.name }}
                             </h3>
                        </Link>
                        
                        <p class="text-xs font-light opacity-40 leading-relaxed line-clamp-2 italic text-white/80">
                             {{ tour.description }}
                        </p>

                        <div class="pt-8 flex items-center justify-between border-t border-white/5">
                             <Link :href="route(page.url.startsWith('/dashboard') ? 'user.tours.show' : 'tours.show', tour.slug)" class="text-[10px] font-black uppercase tracking-widest flex items-center gap-3 group/btn text-white/60 hover:text-safari-gold transition-colors">
                                  View Intelligence <ArrowUpRight class="w-3.5 h-3.5 group-hover/btn:-translate-y-0.5 group-hover/btn:translate-x-0.5 transition-transform" />
                             </Link>
                             <div class="flex items-center gap-3">
                                  <div class="w-2 h-2 rounded-full bg-safari-gold animate-pulse shadow-[0_0_100px_rgba(202,138,4,0.5)]"></div>
                                  <span class="text-[9px] font-black uppercase tracking-[0.3em] opacity-30 text-white">Pulse Online</span>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
 
 
            <!-- Fleet Pulse Section (Small Cards) -->
            <section v-if="fleet && fleet.length > 0" class="mt-44 pt-44 border-t border-white/5 space-y-24">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                    <div class="space-y-4">
                        <p class="text-[9px] font-black tracking-[0.5em] uppercase text-safari-gold opacity-50">// LOGISTICS PULSE</p>
                        <h2 class="text-5xl font-black uppercase tracking-tighter text-white">Active Fleet Recon.</h2>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-30 text-white max-w-sm italic">
                        Our specialized expedition logistics fleet, maintained for absolute savanna dominance.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">
                    <div v-for="vehicle in fleet" :key="vehicle.id" @click="openHireModal(vehicle)" class="group relative aspect-[4/5] bg-white/[0.02] border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/30 transition-all duration-700 shadow-2xl cursor-pointer">
                        <img 
                            v-if="vehicle.image" 
                            :src="vehicle.image" 
                            class="absolute inset-0 h-full w-full object-cover grayscale opacity-30 group-hover:opacity-100 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-1000"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-8 space-y-4 z-10 transition-transform duration-500 transform group-hover:-translate-y-2">
                            <div class="flex items-center gap-3 text-safari-gold">
                                <Zap class="w-4 h-4" />
                                <span class="text-[10px] font-black tracking-[0.2em] uppercase">{{ vehicle.drive_type }}</span>
                            </div>
                            <h4 class="text-xl font-black uppercase tracking-tight text-white group-hover:text-safari-gold transition-colors">{{ vehicle.name }}</h4>
                            <div class="flex items-center gap-4 pt-4 border-t border-white/10">
                                <div class="flex items-center gap-1.5 text-[8px] font-bold uppercase tracking-widest opacity-40 text-white">
                                    <Users class="w-3 h-3 text-safari-gold/40" />
                                    {{ vehicle.capacity.split(' ')[0] }}
                                </div>
                                <div class="px-2 py-0.5 bg-safari-gold text-black text-[7px] font-black uppercase tracking-widest rounded-full">
                                    Ready
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Empty State -->
            <div v-else-if="filteredTours.length === 0" class="py-44 border border-dashed border-white/5 rounded-sm flex flex-col items-center text-center bg-white/[0.01]">
                 <Search class="w-16 h-16 opacity-10 mb-10 text-safari-gold" />
                 <h2 class="text-4xl font-black uppercase tracking-widest mb-6 text-white">Archives Silent</h2>
                 <p class="text-xs opacity-40 max-w-sm font-light uppercase tracking-widest italic text-white/60 leading-loose">
                      "The savanna is vast, but we couldn't find a match for this scout report."
                 </p>
            </div>
        </main>
        <!-- Hire Modal -->
        <Transition enter-active-class="transition duration-500 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-300 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
             <div v-if="selectedVehicle" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/95 backdrop-blur-sm">
                  <div class="bg-card border border-white/10 w-full max-w-4xl rounded-sm overflow-hidden flex flex-col shadow-[0_0_100px_rgba(202,138,4,0.1)] relative">
                       <button @click="closeHireModal" class="absolute top-8 right-8 p-3 border border-white/5 bg-white/5 rounded-full hover:bg-white/10 transition-all text-white z-[110]">
                            <X class="w-5 h-5" />
                       </button>

                       <!-- Intelligence Step -->
                       <div v-if="modalStep === 1" class="animate-in fade-in slide-in-from-right duration-700">
                            <div class="flex flex-col lg:flex-row h-full">
                                 <div class="lg:w-1/2 relative h-64 lg:h-[600px] bg-black">
                                      <img :src="selectedVehicle.image" class="absolute inset-0 w-full h-full object-cover opacity-60" />
                                      <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                                      <div class="absolute bottom-10 left-10">
                                           <div class="px-3 py-1 bg-safari-gold text-black text-[8px] font-black uppercase tracking-widest mb-4 inline-block">Unit Ready</div>
                                           <h2 class="text-4xl font-black uppercase tracking-tighter text-white">{{ selectedVehicle.name }}</h2>
                                      </div>
                                 </div>
                                 <div class="lg:w-1/2 p-10 lg:p-12 space-y-10 flex flex-col justify-between">
                                      <div class="space-y-8">
                                           <div class="space-y-2">
                                                <p class="text-[9px] font-black uppercase tracking-[0.5em] text-safari-gold">Tactical Intelligence</p>
                                                <h3 class="text-xl font-black uppercase tracking-tight text-white/90 italic">Strategic Recon Report</h3>
                                           </div>
                                           
                                           <div class="grid grid-cols-1 gap-6">
                                                <div v-if="selectedVehicle.drivers" class="flex items-center gap-5 p-4 bg-white/[0.02] border border-white/5 rounded-sm">
                                                     <UserCheck class="w-5 h-5 text-safari-gold opacity-50" />
                                                     <div class="flex flex-col">
                                                          <span class="text-[8px] font-black uppercase tracking-widest opacity-30">Assigned Personnel</span>
                                                          <span class="text-xs font-black uppercase tracking-widest text-white/80">{{ selectedVehicle.drivers }}</span>
                                                     </div>
                                                </div>
                                                <div v-if="selectedVehicle.suited_for" class="flex items-center gap-5 p-4 bg-white/[0.02] border border-white/5 rounded-sm">
                                                     <Target class="w-5 h-5 text-safari-gold opacity-50" />
                                                     <div class="flex flex-col">
                                                          <span class="text-[8px] font-black uppercase tracking-widest opacity-30">Mission Optimization</span>
                                                          <span class="text-xs font-black uppercase tracking-widest text-white/80">{{ selectedVehicle.suited_for }}</span>
                                                     </div>
                                                </div>
                                                <div v-if="selectedVehicle.availability" class="flex items-center gap-5 p-4 bg-white/[0.02] border border-white/5 rounded-sm">
                                                     <Clock class="w-5 h-5 text-safari-gold opacity-50" />
                                                     <div class="flex flex-col">
                                                          <span class="text-[8px] font-black uppercase tracking-widest opacity-30">Operational Window</span>
                                                          <span class="text-xs font-black uppercase tracking-widest text-white/80">{{ selectedVehicle.availability }}</span>
                                                     </div>
                                                </div>
                                           </div>

                                           <div class="pt-6 border-t border-white/5">
                                                <p class="text-[8px] font-black opacity-30 uppercase tracking-[0.4em] mb-6 tracking-widest">Induction Economics</p>
                                                <div class="grid grid-cols-3 gap-4">
                                                     <div class="text-center">
                                                          <p class="text-[7px] font-black opacity-40 mb-1 tracking-widest">HOURLY</p>
                                                          <p class="text-sm font-black text-safari-gold">${{ selectedVehicle.price_per_hour || 'TBD' }}</p>
                                                     </div>
                                                     <div class="text-center border-x border-white/5 px-4">
                                                          <p class="text-[7px] font-black opacity-40 mb-1 tracking-widest">DAILY</p>
                                                          <p class="text-sm font-black text-safari-gold">${{ selectedVehicle.price_per_day || 'TBD' }}</p>
                                                     </div>
                                                     <div class="text-center">
                                                          <p class="text-[7px] font-black opacity-40 mb-1 tracking-widest">SAFARI</p>
                                                          <p class="text-sm font-black text-safari-gold">${{ selectedVehicle.price_per_safari_drive || 'TBD' }}</p>
                                                     </div>
                                                </div>
                                           </div>
                                      </div>

                                      <button @click="modalStep = 2" class="w-full py-5 bg-safari-gold text-black text-[11px] font-black uppercase tracking-[0.5em] hover:bg-white transition-all shadow-[0_10px_40px_rgba(202,138,4,0.15)] flex items-center justify-center gap-4 group">
                                           Initialize Deployment <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
                                      </button>
                                 </div>
                            </div>
                       </div>

                       <!-- Registry Step -->
                       <div v-if="modalStep === 2" class="p-12 lg:p-16 animate-in fade-in slide-in-from-left duration-700 max-h-[90vh] overflow-y-auto">
                            <div class="max-w-3xl mx-auto space-y-12">
                                 <div class="flex items-center gap-8 mb-4">
                                      <button @click="modalStep = 1" class="p-2 border border-white/5 bg-white/5 rounded-full hover:bg-white/10 transition-all text-white/60 hover:text-safari-gold">
                                           <ArrowRight class="w-5 h-5 rotate-180" />
                                      </button>
                                      <div class="space-y-2">
                                           <h3 class="text-3xl font-black uppercase tracking-tighter text-white">Deployment Registry</h3>
                                           <p class="text-[10px] opacity-30 uppercase tracking-[0.4em] italic font-bold text-white/50">Mission Parameter Setup // Unit: {{ selectedVehicle.name }}</p>
                                      </div>
                                 </div>

                                 <form @submit.prevent="submit" class="space-y-10">
                                      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-white">
                                           <div class="space-y-4">
                                                <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1">Identity Name</label>
                                                <input v-model="form.name" type="text" placeholder="Scout Identifier" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                           </div>
                                           <div class="space-y-4">
                                                <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1">Communications Relay</label>
                                                <input v-model="form.email" type="email" placeholder="Email Address" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                           </div>
                                      </div>

                                      <div class="space-y-4 text-white">
                                           <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1">Tactical Contact (Phone)</label>
                                           <input v-model="form.phone" type="text" placeholder="+254..." class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-sm font-light tracking-[0.2em] outline-none focus:border-safari-gold transition-all text-white" required />
                                      </div>

                                      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-white">
                                           <div class="space-y-4">
                                                <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1">Deployment Start</label>
                                                <input v-model="form.start_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                           </div>
                                           <div class="space-y-4">
                                                <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1">End of Mission</label>
                                                <input v-model="form.end_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                           </div>
                                      </div>

                                      <div class="space-y-4 text-white">
                                           <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1">Mission Briefing</label>
                                           <textarea v-model="form.message" rows="4" placeholder="SPECIFY YOUR MISSION REQUIREMENTS OR DESTINATIONS..." class="w-full bg-white/5 border border-white/10 rounded-sm p-6 text-sm font-light outline-none focus:border-safari-gold transition-all leading-relaxed uppercase placeholder:opacity-10 text-white"></textarea>
                                      </div>

                                      <div class="pt-10 flex flex-col md:flex-row items-center justify-between gap-8 border-t border-white/10">
                                           <div class="flex items-center gap-4 opacity-30">
                                                <ShieldCheck class="w-6 h-6 text-safari-gold" />
                                                <span class="text-[9px] font-black uppercase tracking-widest text-white">Strategic Logistics Verified</span>
                                           </div>
                                           <button type="submit" :disabled="form.processing" class="w-full md:w-auto px-16 py-6 bg-safari-gold text-black text-[12px] font-black uppercase tracking-[0.5em] hover:bg-white transition-all transform hover:-translate-y-1 shadow-2xl rounded-sm">
                                                {{ form.processing ? 'TRANSMITTING...' : 'AUTHORIZE DEPLOYMENT' }}
                                           </button>
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
main {
    font-family: 'Outfit', sans-serif;
}
</style>
