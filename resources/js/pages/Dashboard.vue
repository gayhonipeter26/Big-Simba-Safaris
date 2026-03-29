<script setup lang="ts">
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { 
    Calendar, Users, DollarSign, MapPin, Clock, ArrowRight, 
    ShieldCheck, Plane, Compass, Search, Camera, Smartphone, CreditCard, Eye, Newspaper,
    ChevronDown, ChevronUp, ChevronLeft, ChevronRight, X, Star, Trash2, Info, AlertTriangle,
    Layers, LayoutGrid, Bell, Phone
} from 'lucide-vue-next';
import { computed, ref, onMounted } from 'vue';
import { useCurrency } from '@/composables/useCurrency';
import PaymentModal from '@/components/PaymentModal.vue';

const page = usePage<any>();
const user = computed(() => page.props.auth?.user);
const isStatusClosed = ref(true);
const isReady = ref(false);

onMounted(() => {
    isReady.value = true;
});

const props = defineProps<{
    bookings: any[];
    fleet_hires: any[];
    tour_enquiries: any[];
    pending_reviews: any[];
    total_tours: number;
    total_fleet: number;
    total_services: number;
}>();

const searchQuery = ref('');

const search = () => {
    router.get(route('user.tours.index'), { search: searchQuery.value }, { preserveState: true });
};

const upcomingBookings = computed(() => {
    return props.bookings.filter(b => b.status === 'confirmed' || b.status === 'pending');
});

const pastBookings = computed(() => {
    return props.bookings.filter(b => b.status === 'completed' || b.status === 'cancelled');
});

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};

const { formatPrice, currency } = useCurrency();

const selectedPayment = ref<any>(null);
const isPaymentModalOpen = ref(false);

const openPaymentModal = (id: number, type: string, amount: number, reference: string) => {
    const kesAmount = currency.value.current === 'USD' ? amount * (1 / (currency.value.rate || 1)) : amount;
    selectedPayment.value = { id, type, amount: Math.round(kesAmount), reference };
    isPaymentModalOpen.value = true;
};

const quickAssets = computed(() => [
    { 
        title: 'Expedition Registry', 
        count: props.total_tours, 
        icon: MapPin, 
        image: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?q=80&w=2068',
        link: route('user.tours.index'),
        pending: props.bookings.filter(b => b.status === 'pending').length + (props.tour_enquiries?.filter(e => e.status === 'pending').length || 0),
        approved: props.bookings.filter(b => b.status === 'confirmed').length + (props.tour_enquiries?.filter(e => e.status === 'approved').length || 0),
        tag: 'SURVEY SAVANNA'
    },
    { 
        title: 'Fleet Division', 
        count: props.total_fleet, 
        icon: Smartphone, 
        image: 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=2070',
        link: route('user.fleet-hire.index'),
        pending: props.fleet_hires.filter(f => f.status === 'pending').length,
        approved: props.fleet_hires.filter(f => f.status === 'approved').length,
        tag: 'DEPLOY ASSETS'
    },
    { 
        title: 'Logistics Pulse', 
        count: props.total_services, 
        icon: ShieldCheck, 
        image: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=2021',
        link: route('services.index'),
        pending: 0, 
        approved: 0,
        tag: 'ACQUIRE INTEL'
    }
]);

const heroImage = 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=2000'; // Majestic African Lion (Simba)

const currentMonth = ref(new Date().getMonth());
const currentYear = ref(new Date().getFullYear());
const monthNames = ["JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"];

const strategicEvents = [
    { 
        name: 'Mara Migration', 
        occasion: 'Great Migration Crossing',
        month: 6, // July (0-indexed)
        days: [12, 13, 14, 15, 16, 17, 18], 
        color: 'bg-safari-gold', 
        label: 'Peak',
        location: 'Maasai Mara',
        region: 'Narok County, Kenya',
        time: '06:00 - 18:30 HRS',
        description: 'Synchronized crossing of the Mara River discovered. Peak predator activity detected across the northern sector.',
        asset_class: 'ALPHA EXPEDITION',
        image: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?q=80&w=800'
    },
    { 
        name: 'Amboseli Calving', 
        occasion: 'Elephant Calving Window',
        month: 2, // March
        days: [24, 25, 26, 27, 28], 
        color: 'bg-green-500', 
        label: 'Event',
        location: 'Amboseli Park',
        region: 'Kajiado, Kenya',
        time: '07:00 - 17:00 HRS',
        description: 'Synchronized elephant calving detected. Large matriarchal herds converging at the wetlands for hydration.',
        asset_class: 'TACTICAL SURVEY',
        image: 'https://images.unsplash.com/photo-1557050543-4d5f4e07ef46?q=80&w=800'
    },
    { 
        name: 'Tsavo Recon', 
        occasion: 'Arid Reconnaisance',
        month: 7, // August
        days: [1, 2, 3, 4, 5], 
        color: 'bg-orange-500', 
        label: 'Optimal',
        location: 'Tsavo East',
        region: 'Coast Region, Kenya',
        time: '05:30 - 19:00 HRS',
        description: 'Extreme arid conditions forcing predator convergence at fixed watering points.',
        asset_class: 'SURVEY RECON',
        image: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=800'
    }
];

const selectedDayData = ref<any>(null);
const isIntelModalOpen = ref(false);
const isYearViewOpen = ref(false);

const openDayIntel = (i: number) => {
    if (i === 0) return;
    const event = strategicEvents.find(e => e.days.includes(i) && e.month === currentMonth.value);
    selectedDayData.value = {
        day: i,
        event: event || null,
        month: monthNames[currentMonth.value],
        year: currentYear.value
    };
    isIntelModalOpen.value = true;
};

const prevDay = () => {
    if (selectedDayData.value.day > 1) {
        openDayIntel(selectedDayData.value.day - 1);
    }
};

const nextDay = () => {
    if (selectedDayData.value.day < 31) {
        openDayIntel(selectedDayData.value.day + 1);
    }
};

const prevMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11;
        currentYear.value--;
    } else {
        currentMonth.value--;
    }
};

const nextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0;
        currentYear.value++;
    } else {
        currentMonth.value++;
    }
};

const selectMonth = (index: number) => {
    currentMonth.value = index;
    isYearViewOpen.value = false;
};

const getEventForDay = (day: number) => {
    return strategicEvents.find(e => e.days.includes(day) && e.month === currentMonth.value);
};

const getEventsForMonth = (monthIndex: number) => {
    return strategicEvents.filter(e => e.month === monthIndex);
};

// Expedition Request States
const selectedEnquiry = ref<any>(null);
const isEnquiryModalOpen = ref(false);
const cancellationReason = ref('');

const openEnquiryDetails = (enquiry: any) => {
    selectedEnquiry.value = enquiry;
    cancellationReason.value = '';
    isEnquiryModalOpen.value = true;
};

const canCancelEnquiry = (createdAt: string) => {
    const diff = new Date().getTime() - new Date(createdAt).getTime();
    return diff < 24 * 60 * 60 * 1000;
};

const cancelEnquiry = (id: number) => {
    if (!cancellationReason.value || cancellationReason.value.length < 10) {
        alert('TACTICAL REJECTION: You must provide a valid justification (min 10 characters) to abort this mission.');
        return;
    }
    
    if (confirm('TACTICAL OVERRIDE: Are you sure you want to abort this expedition request? This action is permanent.')) {
        router.delete(route('tour-enquiries.destroy', { tour_enquiry: id }), {
            data: { reason: cancellationReason.value },
            onSuccess: () => {
                isEnquiryModalOpen.value = false;
                cancellationReason.value = '';
            }
        });
    }
};

const getMissionCountdown = (startDate: string) => {
    const diff = new Date(startDate).getTime() - new Date().getTime();
    if (diff < 0) return 'DEPLOYED';
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    return `${days}D ${hours}H`;
};

// Calendar Logic
const calendarGrid = computed(() => {
    const firstDay = new Date(currentYear.value, currentMonth.value, 1).getDay();
    const daysInMonth = new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
    const grid = [];
    for (let i = 0; i < firstDay; i++) { grid.push(0); }
    for (let i = 1; i <= daysInMonth; i++) { grid.push(i); }
    while (grid.length % 7 !== 0) { grid.push(0); }
    return grid;
});
</script>

<template>
    <Head title="Client Dashboard" />

    <AppHeaderLayout :full-bleed="true">
        <div class="relative min-h-screen bg-[#050505] overflow-x-hidden">
            <!-- Kenyan Flag Hero Section -->
            <div class="relative h-[35vh] md:h-[45vh] w-full overflow-hidden bg-black z-40 rounded-b-[2rem] md:rounded-b-[4rem] shadow-[0_30px_100px_rgba(0,0,0,0.8)] border-b border-white/5">
                <div class="absolute inset-0">
                    <img :src="heroImage" class="h-full w-full object-cover scale-110 md:scale-105 opacity-80 brightness-90 transition-all duration-[10000ms]" />
                    <div class="absolute inset-x-0 bottom-0 h-full bg-gradient-to-t from-[#050505] via-transparent to-transparent"></div>
                </div>

                <div class="relative h-full flex flex-col items-start justify-center px-6 md:px-20 z-10 pt-10">
                    <div class="animate-in fade-in slide-in-from-left duration-1000 w-full max-w-5xl">
                        <p class="text-[10px] md:text-xs font-black tracking-[0.4em] uppercase text-safari-gold mb-4 opacity-80">- CLIENT PORTAL ACCESS //</p>
                        <h1 class="font-display text-3xl md:text-7xl font-black uppercase tracking-tighter leading-none text-white mb-4 md:mb-6">
                            Welcome, <span class="text-safari-gold">{{ user?.name?.split(' ')[0] || 'ADVENTURER' }}</span>
                        </h1>
                        <p class="text-[10px] md:text-base font-light italic opacity-70 text-white/80 max-w-2xl border-l border-safari-gold/30 pl-4 md:pl-6">
                            "The savanna's pulse remains steady. Mission parameters updated."
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative z-50 -mt-10 px-4 md:px-16 pb-24 md:pb-44 max-w-[1850px] mx-auto space-y-12 md:space-y-20">
                <div class="flex flex-col lg:flex-row gap-6 md:gap-8 items-stretch pt-4 px-2 md:px-0">
                     <div class="flex-1 relative group">
                          <Search class="absolute left-5 md:left-6 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-safari-gold opacity-50 group-focus-within:opacity-100 transition-opacity" />
                          <input v-model="searchQuery" @keyup.enter="search" type="text" placeholder="PLAN YOUR NEXT MISSION..." class="w-full bg-[#111] border border-white/5 pl-14 md:pl-16 pr-6 md:pr-8 py-4 md:py-5 text-[10px] md:text-xs font-black tracking-widest uppercase text-white placeholder:opacity-40 focus:border-safari-gold/30 outline-none transition-all rounded-sm" />
                     </div>
                     <Link v-if="user?.is_admin" :href="route('admin.dashboard')" class="bg-safari-gold/10 border border-safari-gold/20 hover:bg-safari-gold hover:text-black px-10 py-5 rounded-sm flex items-center gap-4 text-xs font-black uppercase tracking-[0.3em] transition-all group">
                          <ShieldCheck class="w-4 h-4" /> Operations Center Bridge <ArrowRight class="w-3 h-3 group-hover:translate-x-1 transition-transform" />
                     </Link>
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                     <div v-for="asset in quickAssets" :key="asset.title" class="group relative bg-[#111] border border-white/5 p-6 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-500 shadow-2xl">
                          <div class="absolute inset-0 opacity-[0.15] group-hover:opacity-[0.35] transition-opacity duration-1000">
                               <img :src="asset.image + '&auto=format&fit=crop&q=60&w=800'" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000" />
                          </div>
                          <div class="relative z-10 space-y-4">
                               <div class="flex items-start justify-between">
                                    <div class="w-10 h-10 rounded-sm border border-white/10 bg-black/40 flex items-center justify-center group-hover:bg-safari-gold transition-all duration-500">
                                         <component :is="asset.icon" class="w-4 h-4 text-safari-gold/60 group-hover:text-black transition-colors" />
                                    </div>
                                    <div class="text-right">
                                         <p class="text-[9px] font-black uppercase tracking-[0.4em] opacity-40">{{ asset.tag }}</p>
                                         <p class="text-[10px] font-black uppercase tracking-widest text-safari-gold">{{ asset.count }} UNITS</p>
                                    </div>
                               </div>
                               <div class="space-y-1">
                                    <h3 class="text-lg font-black uppercase tracking-tight text-white group-hover:text-safari-gold transition-colors">{{ asset.title.replace(' Division', '').replace(' Registry', '').replace(' Pulse', '') }}</h3>
                                    <div class="flex gap-4 border-t border-white/5 pt-3">
                                         <div class="flex items-center gap-1.5">
                                              <span class="text-[8px] font-black uppercase tracking-widest opacity-30">APP:</span>
                                              <span class="text-[10px] font-black text-safari-gold">{{ asset.approved }}</span>
                                         </div>
                                         <div class="flex items-center gap-1.5">
                                              <span class="text-[8px] font-black uppercase tracking-widest opacity-30">PND:</span>
                                              <span class="text-[10px] font-black text-white/40">{{ asset.pending }}</span>
                                         </div>
                                    </div>
                               </div>
                               <Link :href="asset.link" class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-r from-transparent via-transparent to-safari-gold/[0.03] flex items-center justify-end p-6">
                                    <ArrowRight class="w-4 h-4 text-safari-gold/40 translate-x-4 group-hover:translate-x-0 transition-transform duration-500" />
                               </Link>
                          </div>
                     </div>
                 </div>

                 <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                       <div class="xl:col-span-9">
                            <div class="bg-card border border-white/5 rounded-sm p-8 space-y-8 shadow-3xl relative overflow-hidden group">
                                 <div class="absolute -right-20 -top-20 w-96 h-96 bg-safari-gold/[0.03] rounded-full blur-[100px] pointer-events-none"></div>
                                 
                                 <div class="flex items-center justify-between relative z-10">
                                      <div class="space-y-2">
                                           <div class="flex items-center gap-4">
                                                <p class="text-xs font-black tracking-[0.5em] uppercase text-safari-gold">MISSION TIMELINE REGISTRY</p>
                                                <button @click="isYearViewOpen = !isYearViewOpen" class="bg-safari-gold/10 border border-safari-gold/20 px-3 py-1 rounded-sm text-[8px] font-black uppercase tracking-widest text-safari-gold hover:bg-safari-gold hover:text-black transition-all flex items-center gap-2">
                                                     <LayoutGrid class="w-2.5 h-2.5" /> YEAR VIEW
                                                </button>
                                           </div>
                                           <div class="flex items-center gap-4 group/month cursor-pointer" @click="isYearViewOpen = true">
                                                <h3 class="text-4xl font-black uppercase text-white tracking-tighter group-hover:text-safari-gold transition-colors">{{ monthNames[currentMonth] }} {{ currentYear }}</h3>
                                                <ChevronDown class="w-5 h-5 text-white/20 group-hover:text-safari-gold transition-all" />
                                           </div>
                                      </div>
                                      <div class="flex gap-2 min-[400px]:gap-4">
                                            <button @click="prevMonth" class="w-9 h-9 md:w-12 md:h-12 border border-white/10 rounded-sm flex items-center justify-center text-white hover:border-safari-gold/40 hover:text-safari-gold transition-all bg-white/5">
                                                 <ChevronLeft class="w-4 h-4 md:w-5 md:h-5" />
                                            </button>
                                            <button @click="nextMonth" class="w-9 h-9 md:w-12 md:h-12 border border-white/10 rounded-sm flex items-center justify-center text-white hover:border-safari-gold/40 hover:text-safari-gold transition-all bg-white/5">
                                                 <ChevronRight class="w-4 h-4 md:w-5 md:h-5" />
                                            </button>
                                      </div>
                                 </div>

                                 <!-- Standard Month Grid -->
                                 <div v-if="!isYearViewOpen" class="relative z-10 animate-in fade-in slide-in-from-bottom-4">
                                      <div class="grid grid-cols-7 gap-3 mb-4">
                                           <div v-for="day in ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY']" :key="day" class="text-center text-[8px] font-black text-safari-gold/40 tracking-[0.2em]">{{ day }}</div>
                                      </div>
                                      <div class="grid grid-cols-7 gap-3">
                                           <div v-for="(i, idx) in calendarGrid" :key="idx" 
                                                @click="i !== 0 && openDayIntel(i)"
                                                class="aspect-[16/11] border border-white/5 rounded-sm p-3 hover:border-safari-gold/30 transition-all group/day relative overflow-hidden flex flex-col justify-between" 
                                                :class="[i === 0 ? 'opacity-5 pointer-events-none' : 'cursor-pointer', getEventForDay(i) ? 'bg-white/[0.04]' : '']"
                                           >
                                                <div class="flex justify-between items-start">
                                                     <span v-if="i !== 0" class="text-xs font-black tracking-tighter" :class="i === new Date().getDate() && currentMonth === new Date().getMonth() ? 'text-safari-gold' : 'text-white/40 group-hover/day:text-white'">{{ i.toString().padStart(2, '0') }}</span>
                                                     <div v-if="getEventForDay(i)" class="w-1.5 h-1.5 rounded-full shadow-[0_0_10px_rgba(255,255,255,0.2)]" :class="getEventForDay(i)?.color"></div>
                                                </div>
                                                
                                                <div v-if="getEventForDay(i)" class="relative z-10">
                                                     <p class="text-[8px] font-black uppercase tracking-tight text-safari-gold/80 leading-none mb-1">{{ getEventForDay(i)?.name }}</p>
                                                     <p class="text-[7px] font-bold uppercase tracking-[0.2em] opacity-30 leading-none">{{ getEventForDay(i)?.label }} Recon</p>
                                                </div>

                                                <div v-if="i === new Date().getDate() && currentMonth === new Date().getMonth()" class="absolute inset-0 bg-safari-gold/[0.03]"></div>
                                                <div v-if="getEventForDay(i)" class="absolute bottom-0 left-0 right-0 h-1" :class="getEventForDay(i)?.color + ' opacity-40'"></div>
                                           </div>
                                      </div>
                                 </div>

                                 <!-- Yearly Sector Grid (Multi-month view) -->
                                 <div v-else class="relative z-20 animate-in fade-in zoom-in duration-500 pb-10">
                                      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                                           <div v-for="(month, idx) in monthNames" :key="month" 
                                                @click="selectMonth(idx)"
                                                class="group relative bg-black/40 border border-white/5 p-6 rounded-sm hover:border-safari-gold/40 transition-all cursor-pointer overflow-hidden aspect-video flex flex-col justify-between"
                                                :class="{ 'border-safari-gold ring-1 ring-safari-gold/30': currentMonth === idx }"
                                           >
                                                <div class="flex items-start justify-between relative z-10">
                                                     <p class="text-[10px] font-black uppercase tracking-[0.3em]" :class="currentMonth === idx ? 'text-safari-gold' : 'text-white/40 group-hover:text-white'">{{ month }}</p>
                                                     <div v-if="getEventsForMonth(idx).length > 0" class="flex flex-col items-end gap-1">
                                                          <div class="w-1.5 h-1.5 rounded-full bg-safari-gold shadow-[0_0_10px_rgba(234,179,8,0.5)]"></div>
                                                          <p class="text-[7px] font-black text-safari-gold uppercase tracking-tighter">{{ getEventsForMonth(idx).length }} EVENTS</p>
                                                     </div>
                                                </div>
                                                
                                                <div class="relative z-10 flex flex-wrap gap-1">
                                                     <div v-for="event in getEventsForMonth(idx)" :key="event.name" class="px-2 py-0.5 rounded-[1px] text-[7px] font-black uppercase border border-white/5 bg-white/5 text-white/60">
                                                          {{ event.name }}
                                                     </div>
                                                </div>

                                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-br from-safari-gold/[0.05] to-transparent"></div>
                                                <div v-if="currentMonth === idx" class="absolute inset-0 bg-safari-gold/[0.02]"></div>
                                           </div>
                                      </div>
                                 </div>

                                 <div class="pt-10 border-t border-white/5 space-y-8 relative z-10">
                                      <div class="flex items-center gap-6">
                                           <p class="text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold opacity-60">// REGIONAL MISSION KEY</p>
                                           <div class="h-[1px] flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                                      </div>
                                      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                           <div v-for="event in strategicEvents" :key="event.name" class="flex items-center gap-6 group hover:bg-white/[0.02] p-4 -ml-4 transition-colors rounded-sm cursor-help">
                                                <div class="w-2.5 h-10 rounded-sm" :class="event.color"></div>
                                                <div class="space-y-1">
                                                     <p class="text-[10px] font-black uppercase tracking-tight text-white group-hover:text-safari-gold transition-colors">{{ event.name }}</p>
                                                     <p class="text-[8px] font-bold uppercase tracking-widest opacity-30">{{ event.label }} Recon window //</p>
                                                </div>
                                           </div>
                                      </div>
                                 </div>
                            </div>
                       </div>

                       <div class="xl:col-span-3 space-y-8">
                            <div class="bg-[#111] border border-white/5 rounded-sm overflow-hidden flex flex-col shadow-2xl transition-all duration-500">
                                 <button @click="isStatusClosed = !isStatusClosed" class="bg-white/[0.02] border-b border-white/5 p-10 flex items-center justify-between w-full text-left">
                                      <div class="space-y-1">
                                           <p class="text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold">MISSION STATUS HANDSHAKE</p>
                                           <p class="text-[9px] font-bold uppercase tracking-widest opacity-30">{{ isStatusClosed ? 'Command Quadrant Locked //' : 'Real-time Data Sync //' }}</p>
                                      </div>
                                      <component :is="isStatusClosed ? ChevronDown : ChevronUp" class="w-5 h-5 text-safari-gold/40" />
                                 </button>
                                 <div v-if="!isStatusClosed" class="p-10 animate-in fade-in slide-in-from-top-4">
                                      <table class="w-full">
                                           <thead>
                                                <tr class="text-left border-b border-white/5">
                                                     <th class="pb-6 text-[10px] font-black uppercase tracking-widest opacity-40">TARGET</th>
                                                     <th class="pb-6 text-[10px] font-black uppercase tracking-widest opacity-40 text-center">CONFIRM</th>
                                                     <th class="pb-6 text-[10px] font-black uppercase tracking-widest opacity-40 text-right">PEND</th>
                                                </tr>
                                           </thead>
                                           <tbody class="divide-y divide-white/5">
                                                <tr v-for="asset in quickAssets" :key="asset.title + '_row'" class="group">
                                                     <td class="py-8">
                                                          <div class="flex items-center gap-4">
                                                               <div class="w-2 h-2 rounded-full bg-safari-gold/30 group-hover:bg-safari-gold transition-colors shadow-[0_0_10px_rgba(234,179,8,0)] group-hover:shadow-[0_0_10px_rgba(234,179,8,0.3)]"></div>
                                                               <span class="text-xs font-black uppercase tracking-tight text-white/80 group-hover:text-white">{{ asset.title.replace(' Division', '').replace(' Registry', '').replace(' Pulse', '') }}</span>
                                                          </div>
                                                     </td>
                                                     <td class="py-8 text-center text-sm font-black text-safari-gold">{{ asset.approved }}</td>
                                                     <td class="py-8 text-right text-sm font-black text-white/40 group-hover:text-white/70 transition-colors">{{ asset.pending }}</td>
                                                </tr>
                                           </tbody>
                                      </table>
                                 </div>
                            </div>

                            <div v-if="fleet_hires && fleet_hires.length > 0" class="bg-[#111] border border-white/5 rounded-sm overflow-hidden flex flex-col shadow-2xl">
                                 <div class="bg-white/[0.02] border-b border-white/5 p-10 flex items-center justify-between">
                                      <div class="space-y-1">
                                           <p class="text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold">LOGISTICS DEPLOYMENTS</p>
                                           <p class="text-[9px] font-bold uppercase tracking-widest opacity-30">Active Fleet Assets //</p>
                                      </div>
                                      <Link :href="route('user.fleet-hire.index')" class="text-[10px] font-black uppercase tracking-widest text-safari-gold/50 hover:text-safari-gold transition-colors">+ NEW</Link>
                                 </div>
                                 <div class="p-8">
                                      <table class="w-full">
                                           <thead>
                                                <tr class="text-left border-b border-white/5">
                                                     <th class="pb-4 text-[9px] font-black uppercase tracking-widest opacity-40">VEHICLE</th>
                                                     <th class="pb-4 text-[9px] font-black uppercase tracking-widest opacity-40 text-right">STATUS</th>
                                               </tr>
                                           </thead>
                                           <tbody class="divide-y divide-white/5">
                                                <tr v-for="hire in fleet_hires" :key="hire.id" class="group hover:bg-white/[0.02] transition-colors">
                                                     <td class="py-5">
                                                          <div class="flex items-center gap-3">
                                                               <img :src="hire.fleet?.image" class="w-10 h-10 object-cover rounded-sm border border-white/10 grayscale group-hover:grayscale-0 transition-all" />
                                                               <div class="space-y-0.5">
                                                                    <p class="text-[11px] font-black uppercase tracking-tight text-white group-hover:text-safari-gold transition-colors line-clamp-1">{{ hire.fleet?.name }}</p>
                                                                    <p class="text-[8px] font-bold uppercase tracking-widest text-white/20 italic">{{ new Date(hire.start_date).toLocaleDateString() }}</p>
                                                               </div>
                                                          </div>
                                                     </td>
                                                     <td class="py-5 text-right">
                                                          <!-- Countdown if approved -->
                                                          <div v-if="hire.status === 'approved'" class="flex flex-col items-end gap-1 mb-2">
                                                               <p class="text-[7px] font-black text-safari-gold/50 uppercase tracking-widest">LAUNCHING IN</p>
                                                               <p class="text-[10px] font-black bg-safari-gold/10 px-2 py-0.5 rounded-sm border border-safari-gold/20 text-safari-gold animate-pulse">{{ getMissionCountdown(hire.start_date) }}</p>
                                                          </div>
                                                          <span :class="{
                                                               'text-green-500 bg-green-500/10 border-green-500/20': hire.status === 'approved' || hire.status === 'completed',
                                                               'text-orange-500 bg-orange-500/10 border-orange-500/20': hire.status === 'pending',
                                                               'text-red-500 bg-red-500/10 border-red-500/20': hire.status === 'rejected'
                                                          }" class="px-3 py-1 text-[8px] font-black uppercase tracking-widest rounded-full border">
                                                               {{ hire.status }}
                                                          </span>
                                                     </td>
                                                </tr>
                                           </tbody>
                                      </table>
                                 </div>
                            </div>

                            <div v-if="tour_enquiries && tour_enquiries.length > 0" class="bg-[#111] border border-white/5 rounded-sm overflow-hidden flex flex-col shadow-2xl">
                                 <div class="bg-white/[0.02] border-b border-white/5 p-10 flex items-center justify-between">
                                      <div class="space-y-1">
                                           <p class="text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold">EXPEDITION REQUESTS</p>
                                           <p class="text-[9px] font-bold uppercase tracking-widest opacity-30">Active Enquiries //</p>
                                      </div>
                                      <Link :href="route('user.tours.index')" class="text-[10px] font-black uppercase tracking-widest text-safari-gold/50 hover:text-safari-gold transition-colors">+ NEW</Link>
                                 </div>
                                 <div class="p-8">
                                      <table class="w-full">
                                           <thead>
                                                <tr class="text-left border-b border-white/5">
                                                     <th class="pb-4 text-[9px] font-black uppercase tracking-widest opacity-40">MISSION</th>
                                                     <th class="pb-4 text-[9px] font-black uppercase tracking-widest opacity-40 text-right">INTEL</th>
                                                </tr>
                                           </thead>
                                           <tbody class="divide-y divide-white/5">
                                                <tr v-for="enquiry in tour_enquiries" :key="enquiry.id" class="group hover:bg-white/[0.02] transition-colors">
                                                     <td class="py-5">
                                                          <div class="flex items-center gap-4">
                                                               <img :src="enquiry.tour?.images?.[0]" class="w-12 h-12 object-cover rounded-sm border border-white/5 grayscale group-hover:grayscale-0 transition-all duration-500" />
                                                               <div class="space-y-1">
                                                                    <p class="text-[11px] font-black uppercase tracking-tight text-white group-hover:text-safari-gold transition-colors line-clamp-1">{{ enquiry.tour?.name }}</p>
                                                                    <div v-if="enquiry.status === 'approved'" class="flex items-center gap-2">
                                                                         <Clock class="w-2.5 h-2.5 text-safari-gold animate-pulse" />
                                                                         <span class="text-[9px] font-black text-safari-gold uppercase tracking-tighter">{{ getMissionCountdown(enquiry.start_date) }} UNTIL DEPLOYMENT</span>
                                                                    </div>
                                                                    <p v-else class="text-[9px] font-bold uppercase tracking-widest text-white/20 italic">{{ new Date(enquiry.start_date).toLocaleDateString() }}</p>
                                                               </div>
                                                          </div>
                                                     </td>
                                                     <td class="py-5 text-right">
                                                          <div class="flex items-center justify-end gap-3">
                                                               <button @click="openEnquiryDetails(enquiry)" class="w-8 h-8 rounded-sm border border-white/5 flex items-center justify-center text-white/40 hover:text-safari-gold hover:border-safari-gold/30 transition-all">
                                                                    <Eye class="w-3.5 h-3.5" />
                                                               </button>
                                                               <button 
                                                                    v-if="canCancelEnquiry(enquiry.created_at) && enquiry.status === 'pending'"
                                                                    @click="cancelEnquiry(enquiry.id)"
                                                                    class="w-8 h-8 rounded-sm border border-white/5 flex items-center justify-center text-white/20 hover:text-red-500 hover:border-red-500/30 transition-all"
                                                                    title="Cancel Request (Available for 24h)"
                                                               >
                                                                    <Trash2 class="w-3.5 h-3.5" />
                                                               </button>
                                                               <div :class="{
                                                                    'text-green-500 bg-green-500/10 border-green-500/20': enquiry.status === 'approved' || enquiry.status === 'completed',
                                                                    'text-orange-500 bg-orange-500/10 border-orange-500/20': enquiry.status === 'pending',
                                                                    'text-red-500 bg-red-500/10 border-red-500/20': enquiry.status === 'rejected'
                                                               }" class="px-3 py-1 text-[8px] font-black uppercase tracking-widest rounded-full border">
                                                                    {{ enquiry.status }}
                                                               </div>
                                                          </div>
                                                     </td>
                                                </tr>
                                           </tbody>
                                      </table>
                                 </div>
                            </div>
                       </div>
                 </div>

                <div v-if="pending_reviews && pending_reviews.length > 0" class="space-y-16">
                    <div class="flex items-center gap-10">
                        <h3 class="font-display text-[14px] font-black uppercase tracking-[0.5em] opacity-80">Debriefing Room</h3>
                        <div class="h-[1px] w-full bg-gradient-to-r from-white/5 to-transparent"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div v-for="review in pending_reviews" :key="review.id" class="p-8 border border-white/5 rounded-sm bg-white/[0.01] hover:border-safari-gold/20 transition-all duration-700 relative overflow-hidden">
                             <div class="flex gap-6 items-start">
                                  <div class="w-20 h-20 shrink-0 rounded-sm overflow-hidden border border-white/10 bg-black"><img :src="review.tour?.images?.[0]" class="w-full h-full object-cover opacity-60" /></div>
                                  <div class="flex-1">
                                       <p class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold mb-2">Review Pending Verification //</p>
                                       <h4 class="text-lg font-black uppercase tracking-tight text-white mb-2">{{ review.tour?.name }}</h4>
                                       <div class="flex gap-1 mb-4"><Star v-for="i in 5" :key="i" class="w-3 h-3" :class="i <= review.rating ? 'text-safari-gold fill-safari-gold' : 'text-white/10'" /></div>
                                       <p class="text-sm italic opacity-70 line-clamp-2">"{{ review.comment }}"</p>
                                  </div>
                             </div>
                             <div class="absolute right-0 top-0 bottom-0 w-1 bg-safari-gold/20 group-hover:bg-safari-gold transition-all"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <PaymentModal v-if="selectedPayment" :is-open="isPaymentModalOpen" :order-id="selectedPayment.id" :order-type="selectedPayment.type" :amount="selectedPayment.amount" :reference="selectedPayment.reference" @close="isPaymentModalOpen = false" />

        <!-- Intel Modal (Strategic Event) -->
        <div v-if="isIntelModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/98 backdrop-blur-2xl" @click="isIntelModalOpen = false"></div>
            <div class="relative w-full max-w-3xl bg-[#080808] border border-white/10 rounded-sm overflow-hidden shadow-[0_100px_200px_rgba(0,0,0,1)] flex flex-col md:flex-row md:min-h-[500px] max-h-[85vh] overflow-y-auto md:overflow-hidden">
                <div class="md:w-5/12 relative min-h-[200px] md:min-h-0 bg-[#050505] flex flex-col shrink-0">
                    <img :src="selectedDayData.event?.image || 'https://images.unsplash.com/photo-1549480017-d76466a4b7e8?q=80&w=1000'" class="absolute inset-0 w-full h-full object-cover grayscale opacity-30" />
                    <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-black via-black/40 to-transparent"></div>
                    <div class="relative flex-1 p-6 md:p-12 flex flex-col justify-between h-full">
                         <div class="space-y-3">
                              <p class="text-[9px] md:text-[11px] font-black tracking-[0.6em] uppercase text-safari-gold opacity-60">// TEMPORAL MARKER</p>
                              <div class="flex items-baseline gap-3">
                                   <h3 class="text-5xl md:text-7xl font-black uppercase text-white leading-none">{{ selectedDayData.day }}</h3>
                                   <div class="space-y-0">
                                        <p class="text-xl md:text-2xl font-black text-safari-gold leading-none">{{ selectedDayData.month }}</p>
                                        <p class="text-sm md:text-lg font-black opacity-20 leading-none tracking-widest">{{ selectedDayData.year }}</p>
                                   </div>
                              </div>
                         </div>
                         <div class="flex gap-3 pt-6 md:pt-0">
                              <button @click="prevDay" class="w-12 h-12 md:w-14 md:h-14 border border-white/10 flex items-center justify-center text-white hover:bg-safari-gold hover:text-black transition-all group/nav bg-black/40"><ChevronLeft class="w-4 h-4 md:w-5 md:h-5 group-hover/nav:-translate-x-1 transition-transform" /></button>
                              <button @click="nextDay" class="w-12 h-12 md:w-14 md:h-14 border border-white/10 flex items-center justify-center text-white hover:bg-safari-gold hover:text-black transition-all group/nav bg-black/40"><ChevronRight class="w-4 h-4 md:w-5 md:h-5 group-hover/nav:translate-x-1 transition-transform" /></button>
                         </div>
                    </div>
                </div>
                <div class="md:w-7/12 p-6 md:p-12 flex flex-col justify-between relative bg-black overflow-y-auto">
                    <button @click="isIntelModalOpen = false" class="absolute top-4 right-4 md:top-8 md:right-8 w-8 h-8 flex items-center justify-center text-white/20 hover:text-safari-gold transition-colors z-20"><X class="w-5 h-5" /></button>
                    <div class="space-y-8 md:space-y-10">
                        <div class="space-y-1.5">
                             <p class="text-[8px] md:text-[9px] font-black uppercase tracking-[0.4em] text-safari-gold opacity-60">STRATEGIC OCCASION //</p>
                             <h4 class="text-xl md:text-2xl font-black uppercase tracking-tighter text-white">{{ selectedDayData.event?.occasion || 'Standard Recon' }}</h4>
                        </div>
                        <div class="grid grid-cols-1 xs:grid-cols-2 gap-5 md:gap-8">
                             <div class="space-y-1 md:space-y-2"><p class="text-[7px] md:text-[8px] font-black uppercase tracking-[0.4em] opacity-30">TACTICAL SECTOR</p><div class="flex items-center gap-2"><MapPin class="w-3.5 h-3.5 text-safari-gold" /><p class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-white/80 line-clamp-1">{{ selectedDayData.event?.location || 'General Reserve' }}</p></div></div>
                             <div class="space-y-1 md:space-y-2"><p class="text-[7px] md:text-[8px] font-black uppercase tracking-[0.4em] opacity-30">KENYA REGION</p><p class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-white/40 line-clamp-1">{{ selectedDayData.event?.region || 'Tier 01 Surveillance' }}</p></div>
                             <div class="space-y-1 md:space-y-2"><p class="text-[7px] md:text-[8px] font-black uppercase tracking-[0.4em] opacity-30">TIME WINDOW</p><div class="flex items-center gap-2"><Clock class="w-3.5 h-3.5 text-safari-gold/60" /><p class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-white/60 line-clamp-1">{{ selectedDayData.event?.time || '00:00 - 23:59 HRS' }}</p></div></div>
                             <div class="space-y-1 md:space-y-2"><p class="text-[7px] md:text-[8px] font-black uppercase tracking-[0.4em] opacity-30">ASSET CLASS</p><p class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-safari-gold line-clamp-1">{{ selectedDayData.event?.asset_class || 'UTILITY' }}</p></div>
                        </div>
                        <div class="space-y-3">
                             <p class="text-[8px] md:text-[9px] font-black tracking-[0.5em] uppercase text-safari-gold opacity-80">INTEL BRIEFING</p>
                             <p class="text-[11px] md:text-xs font-light italic text-white/60 leading-relaxed border-l border-safari-gold/30 pl-5 md:pl-6">"{{ selectedDayData.event?.description || 'No major anomalies or weather disruptions detected in this temporal sector. Standard mission parameters remain active.' }}"</p>
                        </div>
                    </div>
                    <div class="pt-8 md:pt-10 flex flex-col sm:flex-row gap-3">
                         <Link :href="route('user.tours.index', { search: selectedDayData.event?.location || '', highlight: 'true' })" class="flex-1 bg-safari-gold/[0.9] text-black text-center py-4 md:py-5 rounded-sm font-black uppercase tracking-[0.3em] text-[9px] hover:bg-white transition-all">LAUNCH MISSION</Link>
                         <button @click="isIntelModalOpen = false" class="px-8 border border-white/5 hover:border-white/20 text-[9px] font-black uppercase tracking-[0.3em] transition-colors py-4 md:py-0">DISMISS</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-safari-gold via-black to-safari-gold/20"></div>
            </div>
        </div>

        <!-- Expedition Request Details Modal -->
        <div v-if="isEnquiryModalOpen && selectedEnquiry" class="fixed inset-0 z-[120] flex items-center justify-center p-6 md:p-10">
            <div class="absolute inset-0 bg-black/98 backdrop-blur-2xl" @click="isEnquiryModalOpen = false"></div>
            <div class="relative w-full max-w-2xl bg-[#080808] border border-white/10 rounded-sm overflow-hidden shadow-3xl">
                <!-- Header with Request ID -->
                <div class="p-8 border-b border-white/5 flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold">// MISSION BRIEFING DATA</p>
                        <h4 class="text-xl font-black uppercase tracking-tight text-white">RECON #{{ selectedEnquiry.id?.toString().padStart(6, '0') }}</h4>
                    </div>
                    <button @click="isEnquiryModalOpen = false" class="w-10 h-10 flex items-center justify-center text-white/20 hover:text-white transition-colors">
                        <X class="w-6 h-6" />
                    </button>
                </div>

                <!-- Content -->
                <div class="p-10 space-y-10">
                    <!-- Mission Summary & Logistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white/[0.02] border border-white/5 p-8 rounded-sm">
                         <div class="space-y-4">
                              <div class="space-y-1">
                                   <p class="text-[9px] font-black uppercase tracking-[0.4em] text-safari-gold">DEPLOYMENT WINDOW</p>
                                   <div class="flex items-center gap-3">
                                        <Calendar class="w-3.5 h-3.5 opacity-40" />
                                        <p class="text-xs font-black text-white uppercase">{{ new Date(selectedEnquiry.start_date).toLocaleDateString('en-US', { day: '2-digit', month: 'long', year: 'numeric' }) }}</p>
                                   </div>
                                   <div class="flex items-center gap-3">
                                        <Clock class="w-3.5 h-3.5 opacity-40" />
                                        <p class="text-[10px] font-black text-white/60 uppercase">{{ new Date(selectedEnquiry.start_date).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) }} // UTC+3</p>
                                   </div>
                              </div>
                              <div class="space-y-1">
                                   <p class="text-[9px] font-black uppercase tracking-[0.4em] text-safari-gold">LOGISTICS ASSET</p>
                                   <div class="flex items-center gap-3">
                                        <Smartphone v-if="selectedEnquiry.fleet" class="w-3.5 h-3.5 opacity-40" />
                                        <MapPin v-else class="w-3.5 h-3.5 opacity-40" />
                                        <p class="text-xs font-black text-white uppercase">{{ selectedEnquiry.fleet?.name || 'STANDARD RECON' }}</p>
                                   </div>
                                   <p v-if="selectedEnquiry.fleet" class="text-[9px] font-black text-white/30 uppercase pl-6 tracking-widest">{{ selectedEnquiry.fleet?.type || '4x4 ASSET' }} CLASS</p>
                              </div>
                         </div>

                         <div class="space-y-4 border-l border-white/5 pl-8">
                              <div class="space-y-1">
                                   <p class="text-[9px] font-black uppercase tracking-[0.4em] text-safari-gold">TACTICAL FUNDING</p>
                                   <div class="flex items-baseline gap-2">
                                        <p class="text-[10px] font-black text-white/40">{{ currency.current }}</p>
                                        <p class="text-2xl font-black text-white">{{ formatPrice(selectedEnquiry.paid_amount) }}</p>
                                   </div>
                                   <p class="text-[9px] font-medium text-white/20 uppercase tracking-widest italic">// Commitment Dispatched via {{ selectedEnquiry.payment_method }}</p>
                              </div>
                              <div class="space-y-1">
                                   <p class="text-[9px] font-black uppercase tracking-[0.4em] text-safari-gold">GUEST CAPACITY</p>
                                   <p class="text-xs font-black text-white uppercase">{{ selectedEnquiry.guests }} REGISTERED OPERATIVES</p>
                              </div>
                         </div>
                    </div>

                    <!-- Target Objective -->
                    <div class="space-y-3">
                         <p class="text-[9px] font-black uppercase tracking-[0.4em] opacity-30">PRIMARY TARGET</p>
                         <p class="text-xl font-black text-white uppercase leading-none tracking-tight">{{ selectedEnquiry.tour?.name }}</p>
                    </div>

                    <!-- Alerts & Notice -->
                    <div v-if="selectedEnquiry.status === 'pending'" class="space-y-6 pt-4">
                        <div v-if="canCancelEnquiry(selectedEnquiry.created_at)" class="bg-red-500/5 border border-red-500/10 p-6 rounded-sm space-y-4">
                             <div class="flex items-center gap-4">
                                  <AlertTriangle class="w-5 h-5 text-red-500 shrink-0" />
                                  <div class="space-y-1">
                                       <p class="text-[10px] font-extrabold text-red-500 uppercase tracking-widest">CANCELLATION PROTOCOL: ACTIVE</p>
                                       <p class="text-[9px] font-medium text-red-500/60 uppercase tracking-tighter">MISSION ABORT REMAINS WITHIN 24H AUTHORIZATION WINDOW</p>
                                  </div>
                             </div>
                             <div class="space-y-2">
                                  <p class="text-[9px] font-black uppercase tracking-[0.3em] opacity-30 italic">STATE RELEVANT JUSTIFICATION FOR ABORT:</p>
                                  <textarea v-model="cancellationReason" placeholder="Required: Why is this mission being aborted? (min 10 chars)..." class="w-full bg-black border border-white/10 p-4 text-xs font-light italic text-white/60 placeholder:opacity-20 outline-none focus:border-red-500/30 rounded-sm h-28 transition-all"></textarea>
                             </div>
                        </div>
                        <div v-else class="bg-orange-500/5 border border-orange-500/10 p-6 rounded-sm flex items-center gap-4">
                            <Info class="w-6 h-6 text-orange-500 shrink-0" />
                            <div class="space-y-1">
                                 <p class="text-[10px] font-extrabold text-orange-500 uppercase tracking-widest">THRESHOLD EXCEEDED</p>
                                 <p class="text-[9px] font-medium text-orange-500/80 leading-relaxed uppercase tracking-widest">
                                     The 24-hour tactical window has expired. Direct user deletion is locked. Please contact Admin Command for an authorized mission override.
                                 </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Actions -->
                <div class="p-8 bg-white/[0.02] border-t border-white/5 flex gap-4">
                    <button 
                        v-if="canCancelEnquiry(selectedEnquiry.created_at) && selectedEnquiry.status === 'pending'"
                        @click="cancelEnquiry(selectedEnquiry.id)"
                        class="flex-[2] bg-red-600/10 border border-red-600/20 text-red-500 py-5 rounded-sm font-black uppercase tracking-[0.3em] text-[10px] hover:bg-red-600 hover:text-white transition-all flex items-center justify-center gap-2 disabled:opacity-30 disabled:cursor-not-allowed"
                        :disabled="!cancellationReason || cancellationReason.length < 10"
                    >
                        <Trash2 class="w-3.5 h-3.5" /> CONFIRM MISSION ABORT
                    </button>
                    <button 
                        @click="isEnquiryModalOpen = false" 
                        class="px-10 border border-white/5 hover:border-white/20 text-[10px] font-black uppercase tracking-[0.3em] transition-colors py-5 rounded-sm flex-1"
                    >
                        CLOSE INTEL
                    </button>
                </div>
            </div>
        </div>
    </AppHeaderLayout>
</template>

<style scoped>
.animate-in { animation-duration: 1.2s; animation-fill-mode: both; }
@keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
@keyframes slide-in-from-bottom { from { transform: translateY(30px); } to { transform: translateY(0); } }
.text-balance { text-wrap: balance; }

/* Tactical Safari Scrollbar */
::-webkit-scrollbar {
  width: 4px;
  height: 4px;
}

::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.02);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: rgba(234, 179, 8, 0.15); /* Muted safari-gold */
  border-radius: 10px;
  transition: all 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(234, 179, 8, 0.4); /* Active safari-gold */
}

/* Specific scrolling containers */
.overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(234, 179, 8, 0.2) rgba(255, 255, 255, 0.02);
}
</style>
