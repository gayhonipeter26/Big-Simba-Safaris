<script setup lang="ts">
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { 
    Calendar, Users, DollarSign, MapPin, Clock, ArrowRight, 
    ShieldCheck, Plane, Compass, Search, Camera 
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

const page = usePage<any>();
const user = computed(() => page.props.auth?.user);

const props = defineProps<{
    bookings: any[];
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

const stats = computed(() => {
    const totalSpent = props.bookings.reduce((sum, b) => sum + parseFloat(b.total_price), 0);
    return [
        { label: 'Total Expeditions', value: props.bookings.length, icon: Compass },
        { label: 'Total Spent', value: `$${totalSpent.toLocaleString()}`, icon: DollarSign },
        { label: 'Active Safaris', value: upcomingBookings.value.length, icon: Plane },
    ];
});
</script>

<template>
    <Head title="Client Dashboard" />

    <AppHeaderLayout :full-bleed="true">
        <div class="relative min-h-screen bg-[#050505] overflow-x-hidden">
            <!-- Cinematic Hero Section -->
            <div class="relative h-[85vh] w-full overflow-hidden bg-black z-40 rounded-b-[6rem] shadow-[0_30px_100px_rgba(0,0,0,0.8)] border-b border-white/5">
                <!-- Background Image -->
                <div class="absolute inset-0">
                    <img 
                        src="https://images.unsplash.com/photo-1549480017-d76466a4b7e8?q=80&w=2048" 
                        class="h-full w-full object-cover brightness-[0.3] scale-105"
                        alt="Safari Dashboard"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                </div>

                <!-- Hero Content -->
                <div class="relative h-full flex flex-col items-center justify-center text-center px-8 z-10 pt-20">
                    <div class="animate-in fade-in slide-in-from-bottom duration-1000 w-full max-w-5xl">
                        <p class="text-[12px] md:text-[14px] font-black tracking-[0.6em] uppercase text-safari-gold mb-12 drop-shadow-[0_2px_15px_rgba(202,138,4,0.5)]">- YOUR WILD EXPEDITIONS LOG //</p>
                        
                        <h1 class="text-5xl md:text-[8rem] font-black uppercase tracking-tighter leading-[0.8] text-white mb-8 drop-shadow-2xl">
                            WELCOME,<br/>
                            <span class="text-safari-gold drop-shadow-[0_0_30px_rgba(202,138,4,0.3)]">{{ user?.name?.split(' ')[0] || 'ADVENTURER' }}</span>
                        </h1>
                        
                        <p class="text-lg md:text-xl font-light italic opacity-50 text-white max-w-2xl mx-auto mb-20">
                            "The savanna is calling. Your wild pulse remains steady as you track your next mission."
                        </p>

                        <!-- Integrated Search / Scout -->
                        <div class="relative group max-w-3xl mx-auto">
                            <Search class="absolute left-8 top-1/2 -translate-y-1/2 w-5 h-5 text-safari-gold opacity-40 group-focus-within:opacity-100 transition-opacity" />
                            <input 
                                v-model="searchQuery"
                                @keyup.enter="search"
                                type="text" 
                                placeholder="PLAN YOUR NEXT MISSION... (MARA, AMBOSELI, DIANI)" 
                                class="w-full bg-white/[0.03] backdrop-blur-3xl border border-white/10 pl-20 pr-32 py-8 text-[11px] font-black tracking-[0.2em] uppercase text-white placeholder:opacity-30 focus:border-safari-gold/50 focus:bg-white/[0.07] outline-none transition-all rounded-sm shadow-2xl"
                            />
                            <button @click="search" class="absolute right-2 top-1/2 -translate-y-1/2 bg-safari-gold text-black px-8 py-4.5 text-[9px] font-black uppercase tracking-[0.2em] hover:bg-white transition-all transform hover:scale-105 duration-500 rounded-sm">
                                Launch Scout
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content Area - Shifted Slightly for Balance -->
            <div class="relative z-50 -mt-20 pt-32 px-6 md:px-20 pb-44 max-w-[1700px] mx-auto space-y-32">
                
                <!-- Pulse Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="stat in stats" :key="stat.label" class="bg-[#111] backdrop-blur-2xl border border-white/5 p-12 rounded-sm group hover:border-safari-gold/30 transition-all shadow-2xl relative overflow-hidden">
                        <div class="flex items-center justify-between mb-10 relative z-10">
                            <div class="w-14 h-14 rounded-full bg-safari-gold/[0.03] border border-safari-gold/10 flex items-center justify-center group-hover:bg-safari-gold/10 transition-all duration-500">
                                <component :is="stat.icon" class="w-5 h-5 text-safari-gold/40 group-hover:text-safari-gold transition-colors" />
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40">{{ stat.label }}</span>
                        </div>
                        <p class="text-5xl font-black uppercase tracking-tighter relative z-10 group-hover:text-safari-gold transition-colors">{{ stat.value }}</p>
                        <div class="absolute -right-4 -bottom-4 opacity-[0.01] group-hover:opacity-[0.04] transition-opacity duration-1000">
                             <component :is="stat.icon" class="w-32 h-32" />
                        </div>
                    </div>
                </div>

                <!-- Active Expeditions -->
                <div class="space-y-16">
                    <div class="flex items-center gap-10">
                        <h2 class="text-3xl font-black uppercase tracking-widest whitespace-nowrap">Active Expeditions</h2>
                        <div class="h-[1px] w-full bg-gradient-to-r from-white/10 to-transparent"></div>
                    </div>

                    <div v-if="upcomingBookings.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div v-for="booking in upcomingBookings" :key="booking.id" class="group relative overflow-hidden bg-white/[0.01] rounded-sm border border-white/5 hover:border-safari-gold/20 transition-all duration-1000 shadow-xl">
                            <div class="flex flex-col md:flex-row h-full">
                                <div class="w-full md:w-[45%] h-72 md:h-auto relative overflow-hidden">
                                    <img :src="booking.tour.images?.[0] || 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071'" class="absolute inset-0 h-full w-full object-cover grayscale-[0.5] transition-transform duration-1000 group-hover:scale-110 group-hover:grayscale-0" />
                                    <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-transparent to-transparent"></div>
                                    <div class="absolute top-8 left-8">
                                        <span class="px-5 py-2 bg-safari-gold text-black text-[10px] font-black uppercase tracking-widest rounded-full shadow-2xl">{{ booking.status }}</span>
                                    </div>
                                </div>
                                <div class="p-12 flex-1 flex flex-col justify-between">
                                    <div class="space-y-8">
                                        <div class="flex items-center gap-4 text-safari-gold/60">
                                            <MapPin class="w-4 h-4" />
                                            <p class="text-[11px] font-black uppercase tracking-[0.4em]">{{ booking.tour.destination?.name }}</p>
                                        </div>
                                        <h3 class="text-3xl font-black uppercase tracking-tight leading-tight group-hover:text-safari-gold transition-colors">{{ booking.tour.name }}</h3>
                                        
                                        <div class="grid grid-cols-2 gap-10 pt-8 border-t border-white/5">
                                            <div class="space-y-3">
                                                <p class="text-[9px] font-black opacity-30 uppercase tracking-[0.3em]">Commencement</p>
                                                <div class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest">
                                                    <Calendar class="w-4 h-4 text-safari-gold/40" />
                                                    <span>{{ formatDate(booking.booking_date) }}</span>
                                                </div>
                                            </div>
                                            <div class="space-y-3">
                                                <p class="text-[9px] font-black opacity-30 uppercase tracking-[0.3em]">Adventurers</p>
                                                <div class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest">
                                                    <Users class="w-4 h-4 text-safari-gold/40" />
                                                    <span>{{ booking.number_of_people }} Souls</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-12 flex items-center justify-between">
                                        <div class="text-4xl font-black text-white/95 tracking-tighter">${{ parseFloat(booking.total_price).toLocaleString() }}</div>
                                        <Link :href="route('user.tours.show', { slug: booking.tour.slug })" class="text-[10px] font-black uppercase tracking-[0.4em] hover:text-safari-gold transition-all flex items-center gap-4 group/link">
                                            Intelligence <ArrowRight class="w-4 h-4 group-hover/link:translate-x-2 transition-transform" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Redesigned Empty State -->
                    <div v-else class="py-44 border border-dashed border-white/5 rounded-sm flex flex-col items-center text-center px-10 group hover:border-safari-gold/10 transition-all duration-1000 relative overflow-hidden bg-white/[0.01]">
                        <div class="absolute -right-20 -top-20 opacity-[0.01] pointer-events-none text-[25rem] font-black uppercase select-none">Void</div>
                        <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-12 group-hover:scale-110 transition-transform duration-1000 border border-white/5 relative z-10 shadow-2xl">
                            <Compass class="w-12 h-12 text-white/10 group-hover:text-safari-gold/60 transition-colors" />
                        </div>
                        <h3 class="text-4xl font-black uppercase tracking-[0.3em] mb-6 relative z-10">The records are silent</h3>
                        <p class="text-[13px] opacity-40 max-w-sm mb-16 uppercase tracking-[0.2em] font-light italic leading-loose relative z-10 px-4">
                             "A traveler without a trail is like a lion without a territory. Reclaim your wild pulse today."
                        </p>
                        <Link :href="route('user.tours.index')" class="px-24 py-7 bg-white text-black text-[13px] font-black uppercase tracking-[0.5em] hover:bg-safari-gold transition-all duration-700 rounded-sm transform hover:-translate-y-2 shadow-2xl relative z-10">
                            Launch Recon
                        </Link>
                    </div>
                </div>

                <!-- Witnessed Chronicles -->
                <div v-if="pastBookings.length > 0" class="space-y-16">
                    <div class="flex items-center gap-10">
                        <h3 class="text-[14px] font-black uppercase tracking-[0.5em] opacity-40">Witnessed Chronicles</h3>
                        <div class="h-[1px] w-full bg-gradient-to-r from-white/5 to-transparent"></div>
                    </div>

                    <div class="overflow-hidden border border-white/5 rounded-sm bg-white/[0.01] shadow-2xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/[0.02]">
                                    <th class="py-10 px-12 text-[11px] font-black uppercase tracking-[0.3em] opacity-30">Archive Entry</th>
                                    <th class="py-10 px-12 text-[11px] font-black uppercase tracking-[0.3em] opacity-30">Timeline</th>
                                    <th class="py-10 px-12 text-[11px] font-black uppercase tracking-[0.3em] opacity-30 text-right">Investment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="booking in pastBookings" :key="booking.id" class="border-t border-white/5 group hover:bg-white/[0.02] transition-all">
                                    <td class="py-14 px-12">
                                        <div class="flex items-center gap-10">
                                            <div class="w-24 h-24 rounded-sm overflow-hidden bg-black border border-white/5 shadow-2xl">
                                                <img :src="booking.tour.images?.[0] || 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071'" class="w-full h-full object-cover grayscale opacity-40 group-hover:opacity-100 group-hover:scale-110 transition-all duration-1000" />
                                            </div>
                                            <div>
                                                <p class="text-2xl font-black uppercase tracking-tight group-hover:text-safari-gold transition-colors">{{ booking.tour.name }}</p>
                                                <p class="text-[10px] opacity-30 uppercase tracking-[0.4em] mt-3 italic">{{ booking.tour.duration }} //</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-14 px-12 text-[11px] font-bold opacity-30 uppercase tracking-[0.3em] group-hover:opacity-100 transition-opacity">{{ formatDate(booking.booking_date) }}</td>
                                    <td class="py-14 px-12 text-right">
                                         <p class="text-2xl font-black text-white/95 tracking-tighter group-hover:text-safari-gold transition-colors">${{ parseFloat(booking.total_price).toLocaleString() }}</p>
                                         <p class="text-[9px] font-black uppercase tracking-widest mt-2" :class="booking.status === 'completed' ? 'text-green-500/30 group-hover:text-green-500' : 'text-red-500/30 group-hover:text-red-500'">{{ booking.status }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppHeaderLayout>
</template>

<style scoped>
.animate-in {
    animation-duration: 1.2s;
    animation-fill-mode: both;
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slide-in-from-bottom {
    from { transform: translateY(30px); }
    to { transform: translateY(0); }
}

.text-balance {
    text-wrap: balance;
}
</style>


<style scoped>
.animate-in {
    animation-duration: 1s;
    animation-fill-mode: both;
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slide-in-from-bottom {
    from { transform: translateY(20px); }
    to { transform: translateY(0); }
}

.text-balance {
    text-wrap: balance;
}
</style>
