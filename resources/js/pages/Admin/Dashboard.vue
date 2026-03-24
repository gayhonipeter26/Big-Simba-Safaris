<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Users, DollarSign, MapPin, Clock, ArrowRight, ShieldCheck, Plane, Compass, LayoutDashboard, Globe, Newspaper, MessageSquare, Mail, Car, Camera } from 'lucide-vue-next';
import { computed } from 'vue';

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
        { label: 'Intelligence Posts', value: props.stats.totalPosts, icon: Newspaper, color: 'text-blue-500' },
        { label: 'Wildlife Gallery', value: props.stats.totalGalleryItems, icon: Camera, color: 'text-rose-500' },
        { label: 'Combat Hub Slides', value: props.stats.totalHeroSlides, icon: Globe, color: 'text-emerald-500' },
        { label: 'Guest Recon', value: props.stats.totalUsers, icon: Users, color: 'text-purple-500' },
        { label: 'Pending Feedback', value: props.stats.pendingContent.reviews, icon: MessageSquare, color: 'text-orange-500' },
        { label: 'Inbound Inquiries', value: props.stats.pendingContent.inquiries, icon: Mail, color: 'text-cyan-500' },
        { label: 'Fleet Hiring', value: props.stats.pendingContent.fleetHires, icon: Car, color: 'text-indigo-500' },
        { label: 'Active Dest.', value: props.stats.activeTours, icon: MapPin, color: 'text-red-500' },
    ];
});
</script>

<template>
    <Head title="Admin Control Center" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-12 max-w-[1600px] mx-auto">
            <!-- Admin Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-sidebar-border/30 pb-12">
                 <div>
                      <p class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold mb-4">Command Center</p>
                      <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter">Safari <span class="text-safari-gold/20">Operations</span></h1>
                 </div>
                 <div class="flex flex-wrap gap-4">
                      <Link :href="route('admin.tours.create')" class="px-6 py-3 border border-white/10 text-[9px] font-black uppercase tracking-widest hover:border-safari-gold/50 transition-all rounded-sm">
                           New Tour
                      </Link>
                      <Link :href="route('admin.blog.create')" class="px-6 py-3 border border-white/10 text-[9px] font-black uppercase tracking-widest hover:border-safari-gold/50 transition-all rounded-sm">
                           New Tale
                      </Link>
                      <Link :href="route('admin.bookings.index')" class="px-6 py-3 bg-safari-gold text-black text-[9px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-sm">
                           Mission Logs
                      </Link>
                 </div>
            </div>

            <!-- Dashboard Intel Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                 <div v-for="stat in dashboardStats" :key="stat.label" class="bg-card border border-sidebar-border/50 p-8 rounded-sm group hover:border-safari-gold/30 transition-all relative overflow-hidden">
                      <div class="flex items-center justify-between mb-6 relative z-10">
                           <component :is="stat.icon" class="w-6 h-6 opacity-30 group-hover:opacity-100 transition-opacity" :class="stat.color" />
                           <p class="text-[9px] font-bold uppercase tracking-widest opacity-40">{{ stat.label }}</p>
                      </div>
                      <p class="text-3xl font-black uppercase tracking-tighter relative z-10">{{ stat.value }}</p>
                      <div class="absolute -right-4 -bottom-4 w-24 h-24 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity rotate-12 group-hover:rotate-0 duration-700">
                           <component :is="stat.icon" class="w-full h-full" />
                      </div>
                 </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                 <!-- Recent Bookings Table -->
                 <div class="lg:col-span-2 space-y-8">
                      <div class="flex items-center justify-between">
                           <h2 class="text-xl font-black uppercase tracking-widest">Recent Mission Activity</h2>
                           <Link :href="route('admin.bookings.index')" class="text-[9px] font-black uppercase tracking-widest text-safari-gold hover:underline">Full Archive</Link>
                      </div>

                      <div class="overflow-x-auto shadow-sm border border-white/5 rounded-sm">
                           <table class="w-full text-left border-collapse">
                                <thead class="bg-white/5">
                                     <tr>
                                          <th class="py-4 px-6 text-[10px] font-bold uppercase tracking-[0.2em] opacity-40">Client Intel</th>
                                          <th class="py-4 px-6 text-[10px] font-bold uppercase tracking-[0.2em] opacity-40">Target Tour</th>
                                          <th class="py-4 px-6 text-[10px] font-bold uppercase tracking-[0.2em] opacity-40">Timestamp</th>
                                          <th class="py-4 px-6 text-[10px] font-bold uppercase tracking-[0.2em] opacity-40 text-right">Revenue</th>
                                     </tr>
                                </thead>
                                <tbody>
                                     <tr v-for="booking in stats.recentBookings" :key="booking.id" class="border-b border-white/5 group hover:bg-white/[0.02] transition-colors">
                                          <td class="py-6 px-6">
                                               <div class="flex items-center gap-4">
                                                    <div class="w-10 h-10 rounded-full bg-safari-gold/10 flex items-center justify-center border border-safari-gold/20">
                                                         <span class="text-[10px] font-black uppercase text-safari-gold">{{ booking.user?.name.charAt(0) }}</span>
                                                    </div>
                                                    <div>
                                                         <p class="text-xs font-bold uppercase tracking-tight">{{ booking.user?.name }}</p>
                                                         <p class="text-[9px] opacity-40 tracking-wider font-extralight">{{ booking.user?.email }}</p>
                                                    </div>
                                               </div>
                                          </td>
                                          <td class="py-6 px-6">
                                               <p class="text-xs font-bold uppercase tracking-[0.1em]">{{ booking.tour?.name }}</p>
                                               <p class="text-[9px] font-black uppercase tracking-widest opacity-40" :class="booking.status === 'confirmed' ? 'text-green-500' : 'text-safari-gold'">{{ booking.status }}</p>
                                          </td>
                                          <td class="py-6 px-6 text-[10px] opacity-60 uppercase tracking-tighter">{{ formatDate(booking.created_at) }}</td>
                                          <td class="py-6 px-6 text-right font-black text-xs">${{ parseFloat(booking.total_price).toLocaleString() }}</td>
                                     </tr>
                                </tbody>
                           </table>
                      </div>
                 </div>

                 <!-- Tactical Hub Shortcuts -->
                 <div class="space-y-10 bg-sidebar/20 p-8 rounded-sm border border-white/5 h-fit shadow-2xl">
                      <div class="space-y-2">
                           <p class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold">Rapid Access</p>
                           <h3 class="text-xl font-black uppercase tracking-tighter mb-8">Tactical Hub</h3>
                      </div>

                      <div class="grid grid-cols-1 gap-4">
                           <Link :href="route('admin.hero-slides.index')" class="flex items-center justify-between p-5 bg-white/5 border border-white/5 hover:border-safari-gold/30 hover:bg-safari-gold/5 transition-all rounded-sm group">
                                <div class="flex items-center gap-4">
                                     <Globe class="w-4 h-4 text-safari-gold opacity-50 group-hover:opacity-100" />
                                     <div class="flex flex-col">
                                          <span class="text-[10px] font-black uppercase tracking-widest leading-none">Hero Hub</span>
                                          <span class="text-[8px] opacity-30 mt-1 uppercase tracking-[0.2em] font-light">Manage Cinematic Slides</span>
                                     </div>
                                </div>
                                <ArrowRight class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                           </Link>

                           <Link :href="route('admin.contact-messages.index')" class="flex items-center justify-between p-5 bg-white/5 border border-white/5 hover:border-safari-gold/30 hover:bg-safari-gold/5 transition-all rounded-sm group">
                                <div class="flex items-center gap-4">
                                     <Mail class="w-4 h-4 text-safari-gold opacity-50 group-hover:opacity-100" />
                                     <div class="flex flex-col">
                                          <span class="text-[10px] font-black uppercase tracking-widest leading-none">Guest Inquiries</span>
                                          <span class="text-[8px] opacity-30 mt-1 uppercase tracking-[0.2em] font-light">Monitor Recon Messages</span>
                                     </div>
                                </div>
                                <ArrowRight class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                           </Link>

                           <Link :href="route('admin.fleet-hires.index')" class="flex items-center justify-between p-5 bg-white/5 border border-white/5 hover:border-safari-gold/30 hover:bg-safari-gold/5 transition-all rounded-sm group">
                                <div class="flex items-center gap-4">
                                     <Car class="w-4 h-4 text-safari-gold opacity-50 group-hover:opacity-100" />
                                     <div class="flex flex-col">
                                          <span class="text-[10px] font-black uppercase tracking-widest leading-none">Fleet Recon</span>
                                          <span class="text-[8px] opacity-30 mt-1 uppercase tracking-[0.2em] font-light">Manage Vehicle Logistics</span>
                                     </div>
                                </div>
                                <ArrowRight class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                           </Link>

                           <Link :href="route('admin.gallery.index')" class="flex items-center justify-between p-5 bg-white/5 border border-white/5 hover:border-safari-gold/30 hover:bg-safari-gold/5 transition-all rounded-sm group">
                                <div class="flex items-center gap-4">
                                     <Camera class="w-4 h-4 text-safari-gold opacity-50 group-hover:opacity-100" />
                                     <div class="flex flex-col">
                                          <span class="text-[10px] font-black uppercase tracking-widest leading-none">Wild Gallery</span>
                                          <span class="text-[8px] opacity-30 mt-1 uppercase tracking-[0.2em] font-light">Update Visual Intel</span>
                                     </div>
                                </div>
                                <ArrowRight class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                           </Link>

                           <Link :href="route('admin.users.index')" class="flex items-center justify-between p-5 bg-white/5 border border-white/5 hover:border-safari-gold/30 hover:bg-safari-gold/5 transition-all rounded-sm group">
                                <div class="flex items-center gap-4">
                                     <Users class="w-4 h-4 text-safari-gold opacity-50 group-hover:opacity-100" />
                                     <div class="flex flex-col">
                                          <span class="text-[10px] font-black uppercase tracking-widest leading-none">Pride Members</span>
                                          <span class="text-[8px] opacity-30 mt-1 uppercase tracking-[0.2em] font-light">Force Personnel Records</span>
                                     </div>
                                </div>
                                <ArrowRight class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                           </Link>
                      </div>

                      <div class="mt-8 pt-8 border-t border-white/5 space-y-6">
                           <div class="flex items-center gap-4">
                                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                <p class="text-[9px] font-black uppercase tracking-widest">Global Status: Optimal</p>
                           </div>
                           <div class="flex flex-col gap-3">
                                <p class="text-[8px] font-black uppercase tracking-[0.3em] opacity-40">System Core</p>
                                <div class="h-1 bg-white/5 w-full rounded-full overflow-hidden">
                                     <div class="h-full bg-safari-gold/50 w-2/3 shadow-[0_0_10px_rgba(202,138,4,0.3)]"></div>
                                </div>
                           </div>
                      </div>
                 </div>
            </div>
        </div>
    </AppLayout>
</template>
