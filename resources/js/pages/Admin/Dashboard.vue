<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Calendar, Users, DollarSign, MapPin, Clock, ArrowRight, ShieldCheck, Plane, Compass, LayoutDashboard, Globe, Newspaper, MessageSquare, Mail, Car, Camera, LayoutGrid, ChevronDown, ChevronLeft, ChevronRight, X, Eye, CheckCircle2, XCircle, BarChart3, TrendingUp } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Filler
} from 'chart.js';
import { Bar } from 'vue-chartjs';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement, Filler);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin Dashboard',
        href: '/admin',
    },
];

const props = defineProps<{
    stats: {
        totalRevenue: number;
        totalBookings: number;
        activeTours: number;
        totalUsers: number;
        totalPosts: number;
        totalGalleryItems: number;
        totalHeroSlides: number;
        pendingContent: {
            reviews: number;
            inquiries: number;
            fleetHires: number;
        };
        recentBookings: any[];
    };
    tour_enquiries?: any[];
    fleet_hires?: any[];
    strategic_events?: any[];
}>();

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};

const dashboardStats = computed(() => {
    return [
        { label: 'Total Revenue', value: `$${parseFloat(props.stats.totalRevenue.toString()).toLocaleString()}`, icon: DollarSign, color: 'text-green-500' },
        { label: 'Active Missions', value: props.stats.totalBookings, icon: ShieldCheck, color: 'text-safari-gold' },
        { label: 'Guest Recon', value: props.stats.totalUsers, icon: Users, color: 'text-purple-500' },
        { label: 'Inbound Inquiries', value: props.stats.pendingContent.inquiries, icon: Mail, color: 'text-cyan-500' },
    ];
});

const chartData = computed(() => ({
    labels: ['Intelligence', 'Wildlife', 'Combat Hub', 'Feedback', 'Fleet', 'Destinations'],
    datasets: [{
        label: 'Asset Allocation',
        backgroundColor: 'rgba(234, 179, 8, 0.2)',
        borderColor: '#EAB308',
        borderWidth: 2,
        borderRadius: 4,
        data: [
            props.stats.totalPosts,
            props.stats.totalGalleryItems,
            props.stats.totalHeroSlides,
            props.stats.pendingContent.reviews,
            props.stats.pendingContent.fleetHires,
            props.stats.activeTours
        ]
    }]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#000',
            titleFont: { family: 'Inter', weight: '900' },
            bodyFont: { family: 'Inter' },
            padding: 12,
            borderColor: 'rgba(255,255,255,0.1)',
            borderWidth: 1
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { color: 'rgba(255,255,255,0.05)' },
            ticks: { color: 'rgba(255,255,255,0.4)', font: { size: 10 } }
        },
        x: {
            grid: { display: false },
            ticks: { color: 'rgba(255,255,255,0.4)', font: { size: 10, weight: 'bold' } }
        }
    }
};

// Admin Approval Actions
const updateEnquiryStatus = (id: number, status: string) => {
    if (confirm(`Set mission ${id} to ${status.toUpperCase()}?`)) {
        router.patch(route('admin.tour-enquiries.update', { tour_enquiry: id }), { status }, { preserveScroll: true });
    }
};

const updateFleetHireStatus = (id: number, status: string) => {
    if (confirm(`Set fleet order ${id} to ${status.toUpperCase()}?`)) {
        router.patch(route('admin.fleet-hires.update', { fleetHire: id }), { status }, { preserveScroll: true });
    }
};

// Calendar Logic mapped from Dashboard
const isYearViewOpen = ref(false);
const currentMonth = ref(new Date().getMonth());
const currentYear = ref(new Date().getFullYear());
const monthNames = ["JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"];

const strategicEvents = computed(() => props.strategic_events || []);

const selectedDayData = ref<any>(null);
const isIntelModalOpen = ref(false);

const openDayIntel = (i: number) => {
    if (i === 0) return;
    const event = strategicEvents.value.find(e => e.days.includes(i) && e.month === currentMonth.value);
    selectedDayData.value = {
        day: i,
        event: event || null,
        month: monthNames[currentMonth.value],
        year: currentYear.value
    };
    isIntelModalOpen.value = true;
};

const prevMonth = () => {
    if (currentMonth.value === 0) { currentMonth.value = 11; currentYear.value--; } else { currentMonth.value--; }
};
const nextMonth = () => {
    if (currentMonth.value === 11) { currentMonth.value = 0; currentYear.value++; } else { currentMonth.value++; }
};
const selectMonth = (index: number) => { currentMonth.value = index; isYearViewOpen.value = false; };
const getEventForDay = (day: number) => strategicEvents.value.find(e => e.days.includes(day) && e.month === currentMonth.value);
const getEventsForMonth = (monthIndex: number) => strategicEvents.value.filter(e => e.month === monthIndex);

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
    <Head title="Admin Control Center" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 md:p-10 space-y-12 max-w-[1600px] mx-auto">
            <!-- Admin Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-sidebar-border/30 pb-12">
                 <div>
                      <p class="text-sm font-black uppercase tracking-[0.4em] text-safari-gold mb-4">Command Center</p>
                      <h1 class="font-display text-4xl md:text-5xl font-black uppercase tracking-tighter">Safari <span class="text-safari-gold/20">Operations</span></h1>
                 </div>
                 <div class="flex flex-wrap gap-4">
                      <Link :href="route('admin.tours.create')" class="px-6 py-3 border border-white/10 text-xs font-black uppercase tracking-widest hover:border-safari-gold/50 transition-all rounded-sm">
                           New Tour
                      </Link>
                      <Link :href="route('admin.blog.create')" class="px-6 py-3 border border-white/10 text-xs font-black uppercase tracking-widest hover:border-safari-gold/50 transition-all rounded-sm">
                           New Tale
                      </Link>
                      <Link :href="route('admin.bookings.index')" class="px-6 py-3 bg-safari-gold text-black text-xs font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-sm">
                           Mission Logs
                      </Link>
                 </div>
            </div>

            <!-- Dashboard Intel Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                 <div v-for="stat in dashboardStats" :key="stat.label" class="bg-card border border-sidebar-border/50 p-4 md:p-8 rounded-sm group hover:border-safari-gold/30 transition-all relative overflow-hidden">
                      <div class="flex items-center justify-between mb-4 md:mb-6 relative z-10">
                           <component :is="stat.icon" class="w-5 h-5 md:w-6 md:h-6 opacity-70 group-hover:opacity-100 transition-opacity" :class="stat.color" />
                           <p class="text-[8px] md:text-xs font-bold uppercase tracking-widest opacity-80">{{ stat.label }}</p>
                      </div>
                      <p class="text-xl md:text-3xl font-black uppercase tracking-tighter relative z-10">{{ stat.value }}</p>
                      <div class="absolute -right-4 -bottom-4 w-24 h-24 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity rotate-12 group-hover:rotate-0 duration-700">
                           <component :is="stat.icon" class="w-full h-full" />
                      </div>
                 </div>
            </div>

            <!-- Comprehensive Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                 <!-- Main Analytics Chart -->
                 <div class="lg:col-span-2 bg-card border border-white/5 rounded-sm p-5 md:p-8 space-y-6 shadow-3xl relative overflow-hidden group">
                      <div class="flex items-center justify-between relative z-10">
                           <div class="space-y-1">
                                <p class="text-[9px] md:text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold">Tactical Analysis</p>
                                <h3 class="text-xl md:text-2xl font-black uppercase tracking-tighter">Operational Distribution</h3>
                           </div>
                           <TrendingUp class="w-5 h-5 md:w-6 md:h-6 text-safari-gold/40" />
                      </div>
                      <div class="h-[250px] md:h-[300px] relative z-10">
                           <Bar :data="chartData" :options="chartOptions" />
                      </div>
                 </div>

                 <!-- Tactical Hub Shortcuts -->
                 <div class="space-y-6 bg-sidebar/20 p-5 md:p-8 rounded-sm border border-white/5 h-full shadow-2xl flex flex-col justify-between">
                      <div class="space-y-2">
                           <p class="text-[10px] md:text-xs font-black uppercase tracking-[0.4em] text-safari-gold">Rapid Access</p>
                           <h3 class="font-display text-base md:text-lg font-black uppercase tracking-tighter mb-4">Tactical Hub</h3>
                           
                           <div class="grid grid-cols-1 gap-2">
                                <Link :href="route('admin.hero-slides.index')" class="flex items-center justify-between p-3 bg-white/5 border border-white/5 hover:border-safari-gold/30 hover:bg-safari-gold/5 transition-all rounded-sm group">
                                     <div class="flex items-center gap-3">
                                          <Globe class="w-3.5 h-3.5 text-safari-gold opacity-90" />
                                          <span class="text-[10px] font-black uppercase tracking-widest text-white/80">Hero Hub</span>
                                     </div>
                                     <ArrowRight class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                                </Link>

                                <Link :href="route('admin.contact-messages.index')" class="flex items-center justify-between p-3 bg-white/5 border border-white/5 hover:border-safari-gold/30 hover:bg-safari-gold/5 transition-all rounded-sm group">
                                     <div class="flex items-center gap-3">
                                          <Mail class="w-3.5 h-3.5 text-safari-gold opacity-90" />
                                          <span class="text-[10px] font-black uppercase tracking-widest text-white/80">Inquiries</span>
                                     </div>
                                     <ArrowRight class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                                </Link>

                                <Link :href="route('admin.fleet-hires.index')" class="flex items-center justify-between p-3 bg-white/5 border border-white/5 hover:border-safari-gold/30 hover:bg-safari-gold/5 transition-all rounded-sm group">
                                     <div class="flex items-center gap-3">
                                          <Car class="w-3.5 h-3.5 text-safari-gold opacity-90" />
                                          <span class="text-[10px] font-black uppercase tracking-widest text-white/80">Fleet Recon</span>
                                     </div>
                                     <ArrowRight class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                                </Link>

                                <Link :href="route('admin.gallery.index')" class="flex items-center justify-between p-3 bg-white/5 border border-white/5 hover:border-safari-gold/30 hover:bg-safari-gold/5 transition-all rounded-sm group">
                                     <div class="flex items-center gap-3">
                                          <Camera class="w-3.5 h-3.5 text-safari-gold opacity-90" />
                                          <span class="text-[10px] font-black uppercase tracking-widest text-white/80">Gallery</span>
                                     </div>
                                     <ArrowRight class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                                </Link>
                           </div>
                      </div>

                      <div class="pt-6 border-t border-white/5 space-y-4">
                           <div class="flex items-center gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                                <p class="text-[9px] font-black uppercase tracking-widest">Global Status: Optimal</p>
                           </div>
                           <div class="flex flex-col gap-2">
                                <p class="text-[8px] font-black uppercase tracking-[0.3em] opacity-80">System Core</p>
                                <div class="h-1 bg-white/10 w-full rounded-full overflow-hidden">
                                     <div class="h-full bg-safari-gold/50 w-2/3 shadow-[0_0_10px_rgba(202,138,4,0.3)]"></div>
                                </div>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Calendar Sector -->
            <div class="bg-card border border-white/5 rounded-sm p-5 md:p-8 space-y-8 shadow-3xl relative overflow-hidden group">
                 <div class="absolute -right-20 -top-20 w-96 h-96 bg-safari-gold/[0.03] rounded-full blur-[100px] pointer-events-none"></div>
                 
                 <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                      <div class="space-y-2">
                           <div class="flex items-center gap-4">
                                <p class="text-[9px] md:text-xs font-black tracking-[0.5em] uppercase text-safari-gold">MISSION TIMELINE REGISTRY</p>
                                <button @click="isYearViewOpen = !isYearViewOpen" class="bg-safari-gold/10 border border-safari-gold/20 px-3 py-1 rounded-sm text-[8px] font-black uppercase tracking-widest text-safari-gold hover:bg-safari-gold hover:text-black transition-all flex items-center gap-2">
                                     <LayoutGrid class="w-2.5 h-2.5" /> YEAR VIEW
                                </button>
                           </div>
                           <div class="flex items-center gap-4 group/month cursor-pointer" @click="isYearViewOpen = true">
                                <h3 class="text-2xl md:text-4xl font-black uppercase text-white tracking-tighter group-hover:text-safari-gold transition-colors">{{ monthNames[currentMonth] }} {{ currentYear }}</h3>
                                <ChevronDown class="w-4 h-4 md:w-5 md:h-5 text-white/20 group-hover:text-safari-gold transition-all" />
                           </div>
                      </div>
                      <div class="flex gap-2 min-[400px]:gap-4">
                            <button @click="prevMonth" class="w-10 h-10 md:w-12 md:h-12 border border-white/10 rounded-sm flex items-center justify-center text-white hover:border-safari-gold/40 hover:text-safari-gold transition-all bg-white/5"><ChevronLeft class="w-4 h-4 md:w-5 md:h-5" /></button>
                            <button @click="nextMonth" class="w-10 h-10 md:w-12 md:h-12 border border-white/10 rounded-sm flex items-center justify-center text-white hover:border-safari-gold/40 hover:text-safari-gold transition-all bg-white/5"><ChevronRight class="w-4 h-4 md:w-5 md:h-5" /></button>
                      </div>
                 </div>

                 <div v-if="!isYearViewOpen" class="relative z-10 animate-in fade-in slide-in-from-bottom-4 overflow-x-auto pb-4">
                      <div class="min-w-[600px]">
                           <div class="grid grid-cols-7 gap-1 md:gap-3 mb-4">
                                <div v-for="day in ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT']" :key="day" class="text-center text-[7px] md:text-[8px] font-black text-safari-gold/40 tracking-[0.2em]">{{ day }}</div>
                           </div>
                           <div class="grid grid-cols-7 gap-1 md:gap-3">
                                <div v-for="(i, idx) in calendarGrid" :key="idx" @click="i !== 0 && openDayIntel(i)" class="aspect-[16/11] border border-white/5 rounded-sm p-2 md:p-3 hover:border-safari-gold/30 transition-all group/day relative overflow-hidden flex flex-col justify-between" :class="[i === 0 ? 'opacity-5 pointer-events-none' : 'cursor-pointer', getEventForDay(i) ? 'bg-white/[0.04]' : '']">
                                     <div class="flex justify-between items-start">
                                          <span v-if="i !== 0" class="text-[10px] md:text-xs font-black tracking-tighter" :class="i === new Date().getDate() && currentMonth === new Date().getMonth() ? 'text-safari-gold' : 'text-white/40 group-hover/day:text-white'">{{ i.toString().padStart(2, '0') }}</span>
                                          <div v-if="getEventForDay(i)" class="w-1 md:w-1.5 h-1 md:h-1.5 rounded-full shadow-[0_0_10px_rgba(255,255,255,0.2)]" :class="getEventForDay(i)?.color"></div>
                                     </div>
                                     <div v-if="getEventForDay(i)" class="relative z-10">
                                          <p class="text-[6px] md:text-[8px] font-black uppercase tracking-tight text-safari-gold/80 leading-none mb-1 line-clamp-1">{{ getEventForDay(i)?.name }}</p>
                                          <p class="text-[5px] md:text-[7px] font-bold uppercase tracking-[0.1em] opacity-30 leading-none">{{ getEventForDay(i)?.label }}</p>
                                     </div>
                                     <div v-if="i === new Date().getDate() && currentMonth === new Date().getMonth()" class="absolute inset-0 bg-safari-gold/[0.03]"></div>
                                     <div v-if="getEventForDay(i)" class="absolute bottom-0 left-0 right-0 h-0.5 md:h-1" :class="getEventForDay(i)?.color + ' opacity-40'"></div>
                                </div>
                           </div>
                      </div>
                 </div>

                 <div v-else class="relative z-20 animate-in fade-in zoom-in duration-500 pb-10">
                      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                           <div v-for="(month, idx) in monthNames" :key="month" @click="selectMonth(idx)" class="group relative bg-black/40 border border-white/5 p-6 rounded-sm hover:border-safari-gold/40 transition-all cursor-pointer overflow-hidden aspect-video flex flex-col justify-between" :class="{ 'border-safari-gold ring-1 ring-safari-gold/30': currentMonth === idx }">
                                <div class="flex items-start justify-between relative z-10">
                                     <p class="text-[10px] font-black uppercase tracking-[0.3em]" :class="currentMonth === idx ? 'text-safari-gold' : 'text-white/40 group-hover:text-white'">{{ month }}</p>
                                     <div v-if="getEventsForMonth(idx).length > 0" class="flex flex-col items-end gap-1"><div class="w-1.5 h-1.5 rounded-full bg-safari-gold shadow-[0_0_10px_rgba(234,179,8,0.5)]"></div><p class="text-[7px] font-black text-safari-gold uppercase tracking-tighter">{{ getEventsForMonth(idx).length }} EVENTS</p></div>
                                </div>
                                <div class="relative z-10 flex flex-wrap gap-1">
                                     <div v-for="event in getEventsForMonth(idx)" :key="event.name" class="px-2 py-0.5 rounded-[1px] text-[7px] font-black uppercase border border-white/5 bg-white/5 text-white/60">{{ event.name }}</div>
                                </div>
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-br from-safari-gold/[0.05] to-transparent"></div>
                                <div v-if="currentMonth === idx" class="absolute inset-0 bg-safari-gold/[0.02]"></div>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Full-Width Data Sections -->
            <div class="space-y-16 pt-8">
                 <!-- Interactive Fleet Hires -->
                 <div v-if="fleet_hires && fleet_hires.length > 0" class="space-y-6">
                      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-4">
                           <div class="flex items-center gap-4">
                                <Car class="w-5 h-5 md:w-6 md:h-6 text-safari-gold" />
                                <h2 class="font-display text-xl md:text-2xl font-black uppercase tracking-[0.2em]">Active Fleet Deployments</h2>
                           </div>
                           <span class="w-fit text-[8px] md:text-[10px] font-black uppercase bg-safari-gold text-black px-3 py-1 rounded-sm">{{ fleet_hires.length }} ACTIVE</span>
                      </div>
                      <div class="overflow-x-auto shadow-sm border border-white/5 rounded-sm">
                           <table class="w-full text-left border-collapse min-w-[800px] md:min-w-[1000px]">
                                <thead class="bg-white/5">
                                     <tr>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Asset & Operative</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Contact Intel</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Timeline</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80 text-center">Status</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80 text-center">Payment</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80 text-right">Actions</th>
                                     </tr>
                                </thead>
                                <tbody>
                                     <tr v-for="hire in fleet_hires" :key="hire.id" class="border-b border-white/5 group bg-[#0a0a0a] hover:bg-white/[0.03] transition-colors">
                                          <td class="py-4 md:py-6 px-4 md:px-6">
                                               <div class="flex items-center gap-3 md:gap-4">
                                                    <img v-if="hire.fleet?.image" :src="hire.fleet?.image" class="w-10 h-10 md:w-12 md:h-12 object-cover rounded-sm border border-white/10 grayscale group-hover:grayscale-0 transition-all" />
                                                    <div v-else class="w-10 h-10 md:w-12 md:h-12 bg-white/5 flex items-center justify-center rounded-sm"><Car class="w-5 h-5 md:w-6 md:h-6 opacity-20" /></div>
                                                    <div class="space-y-0.5 md:space-y-1">
                                                         <p class="text-[10px] md:text-[12px] font-black uppercase tracking-tight text-white">{{ hire.fleet?.name || 'GENERIC ASSET' }}</p>
                                                         <p class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-safari-gold">Op: {{ hire.user?.name || hire.name }}</p>
                                                    </div>
                                               </div>
                                          </td>
                                          <td class="py-4 md:py-6 px-4 md:px-6">
                                               <p class="text-[10px] md:text-[11px] font-bold text-white/90 truncate max-w-[150px]">{{ hire.user?.email || hire.email }}</p>
                                               <p class="text-[8px] md:text-[9px] font-black uppercase tracking-wider text-white/40 mt-1">{{ hire.phone }}</p>
                                          </td>
                                          <td class="py-6 px-6">
                                               <div class="flex flex-col gap-1">
                                                    <div class="flex items-center gap-2 text-[10px] font-black">
                                                         <Clock class="w-3 h-3 text-safari-gold/50" />
                                                         <span class="text-white/60">IN:</span> {{ new Date(hire.start_date).toLocaleDateString() }}
                                                    </div>
                                                    <div class="flex items-center gap-2 text-[10px] font-black">
                                                         <Clock class="w-3 h-3 text-red-500/50" />
                                                         <span class="text-white/60">OUT:</span> {{ new Date(hire.end_date).toLocaleDateString() }}
                                                    </div>
                                               </div>
                                          </td>
                                          <td class="py-6 px-6 text-center">
                                               <span :class="{'text-green-500 bg-green-500/10 border-green-500/20': hire.status === 'approved', 'text-safari-gold bg-safari-gold/10 border-safari-gold/20': hire.status === 'pending'}" class="px-3 py-1.5 rounded-[2px] text-[9px] font-black uppercase tracking-widest border border-white/10">{{ hire.status }}</span>
                                          </td>
                                          <td class="py-6 px-6 text-center">
                                               <span :class="{'text-green-500': hire.payment_status === 'paid', 'text-orange-500': hire.payment_status === 'pending', 'text-red-500': hire.payment_status === 'cancelled'}" class="text-[10px] font-black uppercase tracking-[0.1em]">{{ hire.payment_status || 'UNKNOWN' }}</span>
                                          </td>
                                          <td class="py-6 px-6 text-right space-x-2">
                                               <div class="flex items-center justify-end gap-2">
                                                    <button v-if="hire.status === 'pending'" @click="updateFleetHireStatus(hire.id, 'approved')" class="bg-green-500/20 text-green-500 hover:bg-green-500 hover:text-white w-8 h-8 rounded-sm flex items-center justify-center transition-all"><CheckCircle2 class="w-4 h-4" /></button>
                                                    <button v-if="hire.status === 'pending' || hire.status === 'approved'" @click="updateFleetHireStatus(hire.id, 'rejected')" class="bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white w-8 h-8 rounded-sm flex items-center justify-center transition-all"><XCircle class="w-4 h-4" /></button>
                                               </div>
                                          </td>
                                     </tr>
                                </tbody>
                           </table>
                      </div>
                 </div>

                 <!-- Interactive Tour Enquiries -->
                 <div v-if="tour_enquiries && tour_enquiries.length > 0" class="space-y-6">
                      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-4">
                           <div class="flex items-center gap-4">
                                <Plane class="w-5 h-5 md:w-6 md:h-6 text-safari-gold" />
                                <h2 class="font-display text-xl md:text-2xl font-black uppercase tracking-[0.2em]">Safari Pipeline Requests</h2>
                           </div>
                           <span class="w-fit text-[8px] md:text-[10px] font-black uppercase bg-safari-gold text-black px-3 py-1 rounded-sm">{{ tour_enquiries.length }} REQUESTS</span>
                      </div>
                      <div class="overflow-x-auto shadow-sm border border-white/5 rounded-sm">
                           <table class="w-full text-left border-collapse min-w-[800px] md:min-w-[1000px]">
                                <thead class="bg-white/5">
                                     <tr>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Mission Target</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Operative Intel</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Timeline</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80 text-center">Status</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80 text-center">Payment</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80 text-right">Actions</th>
                                     </tr>
                                </thead>
                                <tbody>
                                     <tr v-for="enquiry in tour_enquiries" :key="enquiry.id" class="border-b border-white/5 group bg-[#0a0a0a] hover:bg-white/[0.03] transition-colors">
                                          <td class="py-4 md:py-6 px-4 md:px-6">
                                               <div class="flex items-center gap-3 md:gap-4">
                                                    <img v-if="enquiry.tour?.images?.[0]" :src="enquiry.tour?.images?.[0]" class="w-10 h-10 md:w-12 md:h-12 object-cover rounded-sm border border-white/10 grayscale group-hover:grayscale-0 transition-all" />
                                                    <div v-else class="w-10 h-10 md:w-12 md:h-12 bg-white/5 flex items-center justify-center rounded-sm"><Plane class="w-5 h-5 md:w-6 md:h-6 opacity-20" /></div>
                                                    <div class="space-y-0.5 md:space-y-1">
                                                         <p class="text-[10px] md:text-[12px] font-black uppercase tracking-tight text-white line-clamp-1">{{ enquiry.tour?.name || 'CUSTOM MISSION' }}</p>
                                                         <p class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-safari-gold italic">{{ enquiry.tour?.destination?.name || 'UNKNOWN ZONE' }}</p>
                                                    </div>
                                               </div>
                                          </td>
                                          <td class="py-4 md:py-6 px-4 md:px-6">
                                               <p class="text-[10px] md:text-[11px] font-black uppercase text-safari-gold">{{ enquiry.user?.name || enquiry.name }}</p>
                                               <p class="text-[9px] md:text-[10px] font-bold text-white/60 truncate max-w-[120px] md:max-w-[180px]">{{ enquiry.user?.email || enquiry.email }}</p>
                                               <p class="text-[8px] md:text-[9px] font-black uppercase tracking-wider text-white/30 mt-1">{{ enquiry.phone }}</p>
                                          </td>
                                          <td class="py-4 md:py-6 px-4 md:px-6">
                                               <div class="text-[10px] md:text-[11px] font-black text-white/80">{{ new Date(enquiry.start_date).toLocaleDateString() }}</div>
                                               <div class="text-[8px] md:text-[9px] font-bold text-white/40 uppercase tracking-widest">{{ enquiry.guests }} GUESTS</div>
                                          </td>
                                          <td class="py-4 md:py-6 px-4 md:px-6 text-center">
                                               <span :class="{'text-green-500 bg-green-500/10 border-green-500/20': enquiry.status === 'approved', 'text-safari-gold bg-safari-gold/10 border-safari-gold/20': enquiry.status === 'pending'}" class="px-2 md:px-3 py-1 md:py-1.5 rounded-[2px] text-[8px] md:text-[9px] font-black uppercase tracking-widest border border-white/10">{{ enquiry.status }}</span>
                                          </td>
                                          <td class="py-4 md:py-6 px-4 md:px-6 text-center">
                                               <span :class="{'text-green-500': enquiry.payment_status === 'paid', 'text-orange-500': enquiry.payment_status === 'pending'}" class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.1em]">{{ enquiry.payment_status || 'NOT INIT.' }}</span>
                                          </td>
                                          <td class="py-4 md:py-6 px-4 md:px-6 text-right space-x-2 whitespace-nowrap">
                                               <div class="flex items-center justify-end gap-1 md:gap-2">
                                                    <button v-if="enquiry.status === 'pending'" @click="updateEnquiryStatus(enquiry.id, 'approved')" class="bg-green-500/20 text-green-500 hover:bg-green-500 hover:text-white w-7 h-7 md:w-8 md:h-8 rounded-sm flex items-center justify-center transition-all"><CheckCircle2 class="w-3.5 h-3.5 md:w-4 md:h-4" /></button>
                                                    <button v-if="enquiry.status === 'pending' || enquiry.status === 'approved'" @click="updateEnquiryStatus(enquiry.id, 'rejected')" class="bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white w-7 h-7 md:w-8 md:h-8 rounded-sm flex items-center justify-center transition-all"><XCircle class="w-3.5 h-3.5 md:w-4 md:h-4" /></button>
                                               </div>
                                          </td>
                                     </tr>
                                </tbody>
                           </table>
                      </div>
                 </div>

                 <!-- Recent Bookings Table -->
                 <div class="space-y-6">
                      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-4">
                           <div class="flex items-center gap-4">
                                <DollarSign class="w-5 h-5 md:w-6 md:h-6 text-safari-gold" />
                                <h2 class="font-display text-xl md:text-2xl font-black uppercase tracking-[0.2em]">Recent Mission Activity</h2>
                           </div>
                           <Link :href="route('admin.bookings.index')" class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-safari-gold border-b border-safari-gold/50 pb-0.5 hover:text-white transition-colors">Strategic Archive</Link>
                      </div>

                      <div class="overflow-x-auto shadow-sm border border-white/5 rounded-sm bg-[#0a0a0a]">
                           <table class="w-full text-left border-collapse min-w-[800px] md:min-w-[1000px]">
                                <thead class="bg-white/5">
                                     <tr>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Operative Identity</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Mission target</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Timestamp</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80 text-center">Payment Status</th>
                                          <th class="py-4 md:py-5 px-4 md:px-6 text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] opacity-80 text-right">Credits</th>
                                     </tr>
                                </thead>
                                <tbody>
                                     <tr v-for="booking in stats.recentBookings" :key="booking.id" class="border-b border-white/5 group hover:bg-white/[0.03] transition-colors">
                                          <td class="py-4 md:py-6 px-4 md:px-6">
                                               <div class="flex items-center gap-3 md:gap-5">
                                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-sm bg-safari-gold/10 flex items-center justify-center border border-safari-gold/20 shadow-[0_0_15px_rgba(234,179,8,0.05)]">
                                                         <span class="text-[10px] md:text-[12px] font-black uppercase text-safari-gold">{{ booking.user?.name.charAt(0) }}</span>
                                                    </div>
                                                    <div>
                                                         <p class="text-[10px] md:text-[12px] font-black uppercase tracking-tight text-white">{{ booking.user?.name }}</p>
                                                         <p class="text-[9px] md:text-[10px] font-medium text-white/50 tracking-wider truncate max-w-[120px] md:max-w-none">{{ booking.user?.email }}</p>
                                                    </div>
                                               </div>
                                          </td>
                                          <td class="py-4 md:py-6 px-4 md:px-6">
                                               <p class="text-[10px] md:text-[11px] font-black uppercase tracking-[0.1em] text-white line-clamp-1">{{ booking.tour?.name }}</p>
                                               <p class="text-[8px] md:text-[9px] font-black uppercase tracking-widest mt-1" :class="booking.status === 'confirmed' ? 'text-green-500' : 'text-safari-gold'">{{ booking.status }}</p>
                                          </td>
                                          <td class="py-4 md:py-6 px-4 md:px-6 text-[10px] md:text-[11px] font-black text-white/40 uppercase tracking-widest">{{ formatDate(booking.created_at) }}</td>
                                          <td class="py-4 md:py-6 px-4 md:px-6 text-center">
                                               <span :class="{'text-green-500': booking.payment_status === 'paid', 'text-yellow-500': booking.payment_status === 'pending'}" class="text-[8px] md:text-[10px] font-black uppercase border border-white/5 px-2 md:px-3 py-1 bg-white/5 rounded-sm">{{ booking.payment_status || 'N/A' }}</span>
                                          </td>
                                          <td class="py-4 md:py-6 px-4 md:px-6 text-right font-black text-[11px] md:text-sm text-safari-gold">${{ parseFloat(booking.total_price).toLocaleString() }}</td>
                                     </tr>
                                </tbody>
                           </table>
                      </div>
                 </div>
            </div>
        </div>
        
        <!-- Day Intel Modal -->
        <div v-if="isIntelModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
             <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="isIntelModalOpen = false"></div>
             
             <div class="bg-card w-full max-w-4xl border border-white/10 shadow-2xl relative z-10 animate-in fade-in zoom-in-95 duration-200 overflow-hidden">
                  <div class="absolute top-4 right-4 z-20">
                       <button @click="isIntelModalOpen = false" class="w-8 h-8 bg-black/50 hover:bg-black rounded-full flex items-center justify-center text-white/50 hover:text-white transition-all backdrop-blur-md">
                            <X class="w-4 h-4" />
                       </button>
                  </div>

                  <div v-if="selectedDayData?.event" class="w-full h-48 relative">
                       <img :src="selectedDayData.event.image" class="w-full h-full object-cover" />
                       <div class="absolute inset-0 bg-gradient-to-t from-card via-card/50 to-transparent"></div>
                       <div class="absolute top-4 left-4">
                            <span :class="selectedDayData.event.color" class="px-3 py-1 text-[9px] font-black uppercase tracking-widest text-black/90 shadow-lg">{{ selectedDayData.event.label }} RECON</span>
                       </div>
                  </div>
                  
                  <div class="p-8 pb-10 relative" :class="{'pt-12': !selectedDayData?.event}">
                       <div class="flex items-end justify-between mb-6">
                            <div>
                                 <p class="text-xs font-black tracking-widest uppercase mb-1" :class="selectedDayData?.event ? selectedDayData.event.color.replace('bg-', 'text-') : 'text-safari-gold'">{{ selectedDayData?.month }} {{ selectedDayData?.day }}, {{ selectedDayData?.year }}</p>
                                 <h3 class="text-3xl font-black uppercase tracking-tighter">{{ selectedDayData?.event ? selectedDayData.event.name : 'NO DESIGNATED OPERATIONS' }}</h3>
                            </div>
                       </div>

                       <div v-if="selectedDayData?.event" class="space-y-6">
                            <p class="text-sm opacity-80 leading-relaxed font-light">{{ selectedDayData.event.description }}</p>
                            
                            <div class="grid grid-cols-2 gap-4">
                                 <div class="bg-black/30 border border-white/5 p-4 rounded-sm flex flex-col gap-1">
                                      <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/40">Operation Zone</span>
                                      <span class="text-xs font-bold uppercase tracking-tight">{{ selectedDayData.event.location }}</span>
                                 </div>
                                 <div class="bg-black/30 border border-white/5 p-4 rounded-sm flex flex-col gap-1">
                                      <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/40">Asset Class</span>
                                      <span class="text-xs font-bold uppercase tracking-tight text-safari-gold">{{ selectedDayData.event.asset_class }}</span>
                                 </div>
                                 <div class="bg-black/30 border border-white/5 p-4 rounded-sm flex flex-col gap-1">
                                      <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/40">Engagement Time</span>
                                      <span class="text-xs font-bold uppercase tracking-tight">{{ selectedDayData.event.time }}</span>
                                 </div>
                                 <div class="bg-black/30 border border-white/5 p-4 rounded-sm flex flex-col gap-1">
                                      <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/40">Region</span>
                                      <span class="text-xs font-bold uppercase tracking-tight line-clamp-1">{{ selectedDayData.event.region }}</span>
                                 </div>
                            </div>
                       </div>
                       
                       <div v-else class="text-center py-10 opacity-50 flex flex-col items-center gap-4">
                            <ShieldCheck class="w-12 h-12 opacity-20" />
                            <p class="text-[10px] font-black uppercase tracking-[0.3em]">Sector Clear. No impending operations detected for this cycle.</p>
                       </div>
                  </div>
             </div>
        </div>
    </AppLayout>
</template>
