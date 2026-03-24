<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Calendar, Users, DollarSign, MapPin, Clock, ArrowRight, ShieldCheck, Plane, Compass, Trash2, Mail, Phone, ExternalLink } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Safari Pipeline', href: '/admin/bookings' },
];

const props = defineProps<{
    bookings: any[];
}>();

const updateStatus = (bookingId: number, status: string) => {
    router.patch(route('admin.bookings.update', { booking: bookingId }), {
        status: status
    }, {
        preserveScroll: true,
    });
};

const deleteBooking = (id: number) => {
    if (confirm('Are you sure you want to delete this booking?')) {
        router.delete(route('admin.bookings.destroy', { booking: id }), {
            preserveScroll: true,
        });
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short', day: 'numeric', year: 'numeric'
    });
};

const statusColors: Record<string, string> = {
    pending: 'text-safari-gold bg-safari-gold/10 border-safari-gold/20',
    confirmed: 'text-green-500 bg-green-500/10 border-green-500/20',
    completed: 'text-blue-500 bg-blue-500/10 border-blue-500/20',
    cancelled: 'text-red-500 bg-red-500/10 border-red-500/20',
};
</script>

<template>
    <Head title="Booking Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-12 max-w-[1600px] mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="text-3xl font-black uppercase tracking-tighter mb-2">Safari Pipeline</h1>
                      <p class="text-xs opacity-50 uppercase tracking-widest">{{ bookings.length }} Expeditions in queue</p>
                 </div>
            </div>

            <!-- Bookings List -->
            <div v-if="bookings.length > 0" class="space-y-6">
                 <div v-for="booking in bookings" :key="booking.id" class="group bg-card border border-white/5 rounded-sm p-8 hover:border-safari-gold/30 transition-all duration-700">
                      <div class="flex flex-col xl:flex-row gap-10">
                           <!-- Client & Info -->
                           <div class="flex-1 space-y-6">
                                <div class="flex items-start justify-between">
                                     <div class="flex items-center gap-4">
                                          <div class="w-14 h-14 rounded-full bg-safari-gold/5 flex items-center justify-center border border-safari-gold/10">
                                               <span class="text-sm font-black uppercase text-safari-gold">{{ booking.user.name.charAt(0) }}</span>
                                          </div>
                                          <div>
                                               <h3 class="text-xl font-black uppercase tracking-tight">{{ booking.user.name }}</h3>
                                               <div class="flex items-center gap-4 mt-2">
                                                    <a :href="`mailto:${booking.user.email}`" class="text-[10px] opacity-40 hover:opacity-100 flex items-center gap-2 tracking-widest uppercase">
                                                         <Mail class="w-3 h-3" /> {{ booking.user.email }}
                                                    </a>
                                               </div>
                                          </div>
                                     </div>
                                     <div class="flex flex-col items-end gap-2">
                                          <span :class="['px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border', statusColors[booking.status]]">
                                               {{ booking.status }}
                                          </span>
                                          <p class="text-[9px] opacity-20 uppercase tracking-widest">Booked on {{ formatDate(booking.created_at) }}</p>
                                     </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 pt-6 border-t border-white/5">
                                     <div class="space-y-1">
                                          <p class="text-[9px] font-bold uppercase tracking-widest opacity-30">Tour Selection</p>
                                          <p class="text-xs font-black uppercase tracking-tight text-safari-gold">{{ booking.tour.name }}</p>
                                     </div>
                                     <div class="space-y-1">
                                          <p class="text-[9px] font-bold uppercase tracking-widest opacity-30">Departure</p>
                                          <p class="text-xs font-black uppercase tracking-tight">{{ formatDate(booking.booking_date) }}</p>
                                     </div>
                                     <div class="space-y-1">
                                          <p class="text-[9px] font-bold uppercase tracking-widest opacity-30">Adventurers</p>
                                          <p class="text-xs font-black uppercase tracking-tight">{{ booking.number_of_people }} People</p>
                                     </div>
                                     <div class="space-y-1">
                                          <p class="text-[9px] font-bold uppercase tracking-widest opacity-30">Total Investment</p>
                                          <p class="text-xs font-black uppercase tracking-tight text-green-500">${{ parseFloat(booking.total_price).toLocaleString() }}</p>
                                     </div>
                                </div>
                           </div>

                           <!-- Actions -->
                           <div class="xl:w-64 flex flex-col justify-between gap-6 border-t xl:border-t-0 xl:border-l border-white/5 xl:pl-10 pt-6 xl:pt-0">
                                <div class="space-y-4">
                                     <p class="text-[9px] font-black uppercase tracking-widest opacity-30">Update Status</p>
                                     <div class="grid grid-cols-2 gap-2">
                                          <button v-for="status in ['pending', 'confirmed', 'completed', 'cancelled']" :key="status" 
                                               @click="updateStatus(booking.id, status)"
                                               :class="['px-3 py-2 text-[8px] font-black uppercase tracking-widest rounded-sm transition-all border', 
                                                    booking.status === status ? 'bg-white/10 border-white/20' : 'border-white/5 opacity-40 hover:opacity-100 hover:border-white/20']"
                                          >
                                               {{ status }}
                                          </button>
                                     </div>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                     <Link :href="route('tours.show', booking.tour.slug)" target="_blank" class="text-[9px] font-black uppercase tracking-widest opacity-40 hover:opacity-100 flex items-center gap-2">
                                          Preview <ExternalLink class="w-3 h-3" />
                                     </Link>
                                     <button @click="deleteBooking(booking.id)" class="p-3 text-red-500/30 hover:text-red-500 transition-colors">
                                          <Trash2 class="w-4 h-4" />
                                     </button>
                                </div>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-32 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                 <Compass class="w-12 h-12 opacity-10 mb-6" />
                 <h3 class="text-xl font-black uppercase tracking-widest mb-2">No Bookings Found</h3>
                 <p class="text-xs opacity-40 max-w-xs mb-8 uppercase tracking-widest">The safari pipeline is currently quiet.</p>
            </div>
        </div>
    </AppLayout>
</template>
