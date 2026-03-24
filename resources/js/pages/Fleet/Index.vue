<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Car, Users, Settings, ShieldCheck, Mail, Phone, Calendar, MessageSquare, Briefcase, ChevronRight, X, Clock, UserCheck, Target, DollarSign } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps<{
    fleet: any[];
}>();

const page = usePage<any>();
const layout = computed(() => page.url.startsWith('/dashboard') ? AppHeaderLayout : GuestLayout);

const selectedVehicle = ref<any>(null);

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
};

const closeHireModal = () => {
    selectedVehicle.value = null;
    form.reset();
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
    <Head title="Strategic Logistics - Hire Our Fleet" />

    <component :is="layout">
        <main class="bg-[#050505] min-h-screen pt-44 pb-44 px-8 md:px-20 max-w-[1800px] mx-auto">
            <!-- Hero -->
            <div class="mb-24 space-y-6">
                <p class="text-xs md:text-sm font-black uppercase tracking-[0.5em] text-safari-gold animate-in fade-in slide-in-from-left duration-1000">Tactical Mobility</p>
                <h1 class="text-7xl md:text-9xl font-black uppercase tracking-tighter leading-[0.75] text-white">Hire Our <br/><span class="text-safari-gold/20">Fleet.</span></h1>
                <p class="text-lg md:text-xl font-light opacity-30 max-w-3xl leading-relaxed italic text-white/50">
                     Uncompromising logistics for the independent explorer. Command the wilderness with our precision-engineered safari fleet.
                </p>
            </div>

            <!-- Fleet Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                 <div v-for="vehicle in fleet" :key="vehicle.id" class="group bg-white/[0.01] border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-1000 flex flex-col md:flex-row shadow-2xl">
                      <div class="md:w-[45%] h-[380px] md:h-auto relative overflow-hidden bg-black">
                           <img :src="vehicle.image" class="w-full h-full object-cover transition-transform duration-[3000ms] group-hover:scale-110 opacity-70" />
                           <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent"></div>
                      </div>
                      <div class="md:w-[55%] p-10 lg:p-12 flex flex-col justify-between space-y-8 text-white">
                           <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                     <span class="text-[9px] font-black uppercase tracking-[0.4em] text-safari-gold">{{ vehicle.drive_type }}</span>
                                     <div class="flex items-center gap-2 opacity-30 group-hover:opacity-100 transition-opacity">
                                          <Users class="w-3 h-3" />
                                          <span class="text-[10px] font-black uppercase tracking-widest">{{ vehicle.capacity }} SEATS</span>
                                     </div>
                                </div>
                                <h3 class="text-3xl font-black uppercase tracking-tighter text-white">{{ vehicle.name }}</h3>
                                <div class="flex flex-wrap gap-2 mb-4">
                                     <span v-for="feat in (vehicle.features?.split(',') || [])" :key="feat" class="px-2 py-1 bg-white/5 text-[8px] font-bold uppercase tracking-widest text-white/60 border border-white/5">
                                          {{ feat.trim() }}
                                     </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/5">
                                     <div v-if="vehicle.drivers" class="flex items-center gap-3">
                                          <UserCheck class="w-3.5 h-3.5 text-safari-gold opacity-50" />
                                          <span class="text-[9px] font-black uppercase tracking-widest opacity-40">{{ vehicle.drivers }}</span>
                                     </div>
                                     <div v-if="vehicle.availability" class="flex items-center gap-3">
                                          <Clock class="w-3.5 h-3.5 text-safari-gold opacity-50" />
                                          <span class="text-[9px] font-black uppercase tracking-widest opacity-40">{{ vehicle.availability }}</span>
                                     </div>
                                     <div v-if="vehicle.suited_for" class="flex items-center gap-3 col-span-2">
                                          <Target class="w-3.5 h-3.5 text-safari-gold opacity-50" />
                                          <span class="text-[9px] font-black uppercase tracking-widest opacity-40">Best for: {{ vehicle.suited_for }}</span>
                                     </div>
                                </div>
                           </div>

                           <div class="space-y-6">
                                <div class="grid grid-cols-3 gap-2 border-y border-white/5 py-4">
                                     <div class="text-center">
                                          <p class="text-[7px] font-black opacity-30 uppercase tracking-widest mb-1">Per Hour</p>
                                          <p class="text-xs font-black text-safari-gold">${{ vehicle.price_per_hour || '0' }}</p>
                                     </div>
                                     <div class="text-center border-x border-white/5">
                                          <p class="text-[7px] font-black opacity-30 uppercase tracking-widest mb-1">Per Day</p>
                                          <p class="text-xs font-black text-safari-gold">${{ vehicle.price_per_day || '0' }}</p>
                                     </div>
                                     <div class="text-center">
                                          <p class="text-[7px] font-black opacity-30 uppercase tracking-widest mb-1">Safari</p>
                                          <p class="text-xs font-black text-safari-gold">${{ vehicle.price_per_safari_drive || '0' }}</p>
                                     </div>
                                </div>
                                <button @click="openHireModal(vehicle)" class="w-full py-4 border border-white/10 text-[10px] font-black uppercase tracking-[0.4em] hover:bg-safari-gold hover:text-black hover:border-safari-gold transition-all rounded-sm">
                                     REQUEST INDUCTION
                                </button>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Why Big Simba Logistics -->
            <section class="mt-44 grid grid-cols-1 md:grid-cols-3 gap-12 py-32 border-y border-white/5">
                <div v-for="(item, i) in [
                     { icon: Settings, title: 'Precision Serviced', desc: 'Each machine undergoes a 48-point tactical inspection before every deployment.' },
                     { icon: ShieldCheck, title: 'Wild Insurance', desc: 'Comprehensive field coverage ensuring your mission remains uncompromised.' },
                     { icon: Briefcase, title: 'Logistics Support', desc: '24/7 communications relay for emergency extraction or mechanical guidance.' }
                ]" :key="i" class="space-y-6 p-8 bg-white/[0.01] border border-transparent hover:border-white/5 rounded-sm transition-all shadow-xl group">
                     <div class="w-14 h-14 rounded-full border border-white/5 flex items-center justify-center bg-black group-hover:border-safari-gold transition-all duration-700">
                          <component :is="item.icon" class="w-5 h-5 text-safari-gold opacity-40 group-hover:opacity-100 transition-opacity" />
                     </div>
                     <h4 class="text-xl font-black uppercase tracking-tight text-white">{{ item.title }}</h4>
                     <p class="text-[13px] font-light opacity-50 leading-relaxed italic text-white/60">{{ item.desc }}</p>
                </div>
           </section>
        </main>

        <!-- Hire Modal -->
        <Transition enter-active-class="transition duration-500 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-300 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
             <div v-if="selectedVehicle" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/95 backdrop-blur-sm">
                  <div class="bg-card border border-white/10 w-full max-w-7xl rounded-sm overflow-hidden flex flex-col lg:flex-row shadow-[0_0_100px_rgba(202,138,4,0.1)]">
                       <!-- Side Panel -->
                       <div class="lg:w-2/5 relative h-80 lg:h-auto bg-black">
                            <img :src="selectedVehicle.image" class="absolute inset-0 w-full h-full object-cover opacity-60" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                            <div class="absolute bottom-12 left-12 right-12 space-y-6">
                                 <p class="text-[10px] font-black uppercase tracking-[0.5em] text-safari-gold">Selected Unit</p>
                                 <h2 class="text-5xl font-black uppercase tracking-tighter text-white">{{ selectedVehicle.name }}</h2>
                                  <div class="grid grid-cols-1 gap-4 opacity-70">
                                       <div v-if="selectedVehicle.drivers" class="flex items-center gap-4">
                                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center border border-white/10 text-safari-gold">
                                                 <UserCheck class="w-3.5 h-3.5" />
                                            </div>
                                            <div class="flex flex-col">
                                                 <span class="text-[8px] font-black uppercase tracking-widest opacity-40">Personnel</span>
                                                 <span class="text-[10px] font-black uppercase tracking-widest text-white">{{ selectedVehicle.drivers }}</span>
                                            </div>
                                       </div>
                                       <div v-if="selectedVehicle.suited_for" class="flex items-center gap-4">
                                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center border border-white/10 text-safari-gold">
                                                 <Target class="w-3.5 h-3.5" />
                                            </div>
                                            <div class="flex flex-col">
                                                 <span class="text-[8px] font-black uppercase tracking-widest opacity-40">Optimized For</span>
                                                 <span class="text-[10px] font-black uppercase tracking-widest text-white">{{ selectedVehicle.suited_for }}</span>
                                            </div>
                                       </div>
                                       <div v-if="selectedVehicle.availability" class="flex items-center gap-4">
                                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center border border-white/10 text-safari-gold">
                                                 <Clock class="w-3.5 h-3.5" />
                                            </div>
                                            <div class="flex flex-col">
                                                 <span class="text-[8px] font-black uppercase tracking-widest opacity-40">Deployment Window</span>
                                                 <span class="text-[10px] font-black uppercase tracking-widest text-white">{{ selectedVehicle.availability }}</span>
                                            </div>
                                       </div>
                                  </div>

                                  <div class="pt-8 border-t border-white/5">
                                       <p class="text-[10px] font-black uppercase tracking-[0.5em] text-safari-gold mb-4">Induction Rates</p>
                                       <div class="grid grid-cols-1 gap-3">
                                            <div class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-sm">
                                                 <span class="text-[8px] font-black uppercase tracking-widest opacity-40">Hourly Recon</span>
                                                 <span class="text-xs font-black text-safari-gold">${{ selectedVehicle.price_per_hour || 'TBD' }}</span>
                                            </div>
                                            <div class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-sm">
                                                 <span class="text-[8px] font-black uppercase tracking-widest opacity-40">Daily Deployment</span>
                                                 <span class="text-xs font-black text-safari-gold">${{ selectedVehicle.price_per_day || 'TBD' }}</span>
                                            </div>
                                            <div class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-sm">
                                                 <span class="text-[8px] font-black uppercase tracking-widest opacity-40">Full Safari Mission</span>
                                                 <span class="text-xs font-black text-safari-gold">${{ selectedVehicle.price_per_safari_drive || 'TBD' }}</span>
                                            </div>
                                       </div>
                                  </div>
                             </div>
                        </div>

                       <!-- Form -->
                       <div class="lg:w-3/5 p-12 lg:p-16 relative overflow-y-auto max-h-[90vh] lg:max-h-none">
                            <button @click="closeHireModal" class="absolute top-10 right-10 p-3 border border-white/5 bg-white/5 rounded-full hover:bg-white/10 transition-all text-white">
                                 <X class="w-5 h-5" />
                            </button>

                            <div class="space-y-12">
                                 <div class="space-y-4">
                                      <h3 class="text-3xl font-black uppercase tracking-tighter text-white">Deployment Registry</h3>
                                      <p class="text-[10px] opacity-30 uppercase tracking-[0.4em] italic font-bold text-white/50">Establish your mission parameters</p>
                                 </div>

                                 <form @submit.prevent="submit" class="space-y-10">
                                      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                           <div class="space-y-4">
                                                <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1 text-white">Identity Name</label>
                                                <div class="relative">
                                                     <input v-model="form.name" type="text" placeholder="Scout Identifier" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                                </div>
                                           </div>
                                           <div class="space-y-4">
                                                <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1 text-white">Communications Relay</label>
                                                <div class="relative">
                                                     <input v-model="form.email" type="email" placeholder="Email Address" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                                </div>
                                           </div>
                                      </div>

                                      <div class="space-y-4">
                                           <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1 text-white">Tactical Contact (Phone)</label>
                                           <div class="relative">
                                                <input v-model="form.phone" type="text" placeholder="+254..." class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-sm font-light tracking-[0.2em] outline-none focus:border-safari-gold transition-all text-white" required />
                                           </div>
                                      </div>

                                      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                           <div class="space-y-4">
                                                <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1 text-white">Deployment Start</label>
                                                <input v-model="form.start_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                           </div>
                                           <div class="space-y-4">
                                                <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1 text-white">End of Mission</label>
                                                <input v-model="form.end_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                           </div>
                                      </div>

                                      <div class="space-y-4">
                                           <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1 text-white">Mission Briefing</label>
                                           <textarea v-model="form.message" rows="4" placeholder="SPECIFY YOUR MISSION REQUIREMENTS OR DESTINATIONS..." class="w-full bg-white/5 border border-white/10 rounded-sm p-6 text-sm font-light outline-none focus:border-safari-gold transition-all leading-relaxed uppercase placeholder:opacity-10 text-white"></textarea>
                                      </div>

                                      <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-8 border-t border-white/10">
                                           <div class="flex items-center gap-4 opacity-30">
                                                <ShieldCheck class="w-5 h-5 text-safari-gold" />
                                                <span class="text-[9px] font-black uppercase tracking-widest text-white">Big Simba Logistics Secured</span>
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
main { font-family: 'Outfit', sans-serif; }
</style>
