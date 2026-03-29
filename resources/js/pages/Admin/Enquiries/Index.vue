<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Compass, Trash2, CheckCircle, XCircle, Clock, MapPin, User, Mail, Phone, MessageSquare, Car, Users } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Safari Pipeline', href: '/admin/tour-enquiries' },
];

const props = defineProps<{
    enquiries: any;
}>();

const updateStatus = (id: number, status: string) => {
    router.patch(route('admin.tour-enquiries.update', { tour_enquiry: id }), {
        status: status
    }, {
        preserveScroll: true
    });
};

const deleteRecord = (id: number) => {
    if (confirm('Purge this expedition request from the archives?')) {
        router.delete(route('admin.tour-enquiries.destroy', { tour_enquiry: id }), {
            preserveScroll: true
        });
    }
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'approved': return 'bg-green-500/10 text-green-500 border-green-500/20';
        case 'rejected': return 'bg-red-500/10 text-red-500 border-red-500/20';
        case 'completed': return 'bg-blue-500/10 text-blue-500 border-blue-500/20';
        default: return 'bg-orange-500/10 text-orange-500 border-orange-500/20';
    }
};
</script>

<template>
    <Head title="Safari Pipeline - Big Simba Safaris" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto pb-44">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <p class="text-sm font-black uppercase tracking-[0.4em] text-safari-gold mb-3">Expedition Command</p>
                      <h1 class="font-display text-4xl font-black uppercase tracking-tighter">Safari <span class="text-safari-gold/30">Pipeline</span></h1>
                      <p class="text-xs opacity-80 mt-2 uppercase tracking-widest italic">Monitor and authorize custom expeditions and logistics</p>
                 </div>
            </div>

            <div v-if="enquiries.data.length > 0" class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                 <div v-for="enquiry in enquiries.data" :key="enquiry.id" class="group bg-card border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-700 shadow-2xl">
                      <div class="flex flex-col md:flex-row h-full">
                           <!-- Preview -->
                           <div class="md:w-1/3 relative bg-black border-r border-white/5">
                                <img :src="enquiry.tour?.images?.[0] || 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071'" class="h-full w-full object-cover opacity-90 group-hover:scale-110 transition-transform duration-[2000ms]" />
                                <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black to-transparent">
                                     <p class="text-xs font-black uppercase tracking-widest text-safari-gold mb-1">{{ enquiry.tour?.destination?.name || 'CUSTOM' }}</p>
                                     <h3 class="font-display text-sm font-black uppercase tracking-tight text-white">{{ enquiry.tour?.name }}</h3>
                                </div>
                           </div>

                           <!-- Intel -->
                           <div class="md:w-2/3 p-8 flex flex-col justify-between">
                                <div class="space-y-6">
                                     <div class="flex items-center justify-between">
                                          <div class="flex items-center gap-4">
                                               <span :class="['px-3 py-1 text-xs font-black uppercase tracking-widest border rounded-sm', getStatusColor(enquiry.status || 'pending')]">
                                                    {{ enquiry.status || 'pending' }}
                                               </span>
                                               <span class="text-xs font-bold opacity-70 text-white uppercase tracking-widest italic">Request #{{ enquiry.id.toString().padStart(4, '0') }}</span>
                                          </div>
                                          <div class="flex items-center gap-2">
                                               <button v-if="!enquiry.status || enquiry.status === 'pending'" @click="updateStatus(enquiry.id, 'approved')" class="p-2 border border-white/5 hover:border-green-500/50 hover:bg-green-500/5 text-green-500/40 hover:text-green-500 transition-all rounded-sm" title="Approve">
                                                    <CheckCircle class="w-4 h-4" />
                                               </button>
                                               <button v-if="!enquiry.status || enquiry.status === 'pending'" @click="updateStatus(enquiry.id, 'rejected')" class="p-2 border border-white/5 hover:border-red-500/50 hover:bg-red-500/5 text-red-500/40 hover:text-red-500 transition-all rounded-sm" title="Reject">
                                                    <XCircle class="w-4 h-4" />
                                               </button>
                                               <button v-if="enquiry.status === 'approved'" @click="updateStatus(enquiry.id, 'completed')" class="p-2 border border-white/5 hover:border-blue-500/50 hover:bg-blue-500/5 text-blue-500/40 hover:text-blue-500 transition-all rounded-sm" title="Mark Completed">
                                                    <CheckCircle class="w-4 h-4" />
                                               </button>
                                               <button @click="deleteRecord(enquiry.id)" class="p-2 border border-white/5 hover:border-white/20 text-white/20 hover:text-white transition-all rounded-sm" title="Delete Archive">
                                                    <Trash2 class="w-4 h-4" />
                                               </button>
                                          </div>
                                     </div>

                                     <div class="grid grid-cols-2 gap-8 py-6 border-y border-white/5">
                                          <div class="space-y-4">
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <User class="w-3.5 h-3.5" />
                                                    <span class="text-sm font-black uppercase tracking-widest">{{ enquiry.name }}</span>
                                               </div>
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <Mail class="w-3.5 h-3.5" />
                                                    <span class="text-xs font-bold truncate">{{ enquiry.email }}</span>
                                               </div>
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <Phone class="w-3.5 h-3.5" />
                                                    <span class="text-sm font-black tracking-widest">{{ enquiry.phone }}</span>
                                               </div>
                                          </div>
                                          <div class="space-y-4">
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <Clock class="w-3.5 h-3.5 text-safari-gold" />
                                                    <span class="text-sm font-black uppercase tracking-[0.1em]"><span class="opacity-90 text-xs mr-2">FROM</span> {{ new Date(enquiry.start_date).toLocaleDateString() }}</span>
                                               </div>
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <Clock class="w-3.5 h-3.5 text-safari-gold" />
                                                    <span class="text-sm font-black uppercase tracking-[0.1em]"><span class="opacity-90 text-xs mr-2">TO</span> {{ new Date(enquiry.end_date).toLocaleDateString() }}</span>
                                               </div>
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <Users class="w-3.5 h-3.5 text-safari-gold" />
                                                    <span class="text-sm font-black uppercase tracking-widest">{{ enquiry.guests }} Souls</span>
                                               </div>
                                          </div>
                                     </div>

                                     <div v-if="enquiry.fleet" class="space-y-2">
                                          <div class="flex items-center gap-2 text-white/20">
                                               <Car class="w-3 h-3" />
                                               <span class="text-xs font-black uppercase tracking-widest">Attached Logistics</span>
                                          </div>
                                          <p class="text-sm font-bold uppercase tracking-widest text-safari-gold">{{ enquiry.fleet.name }}</p>
                                     </div>

                                     <div v-if="enquiry.message" class="space-y-2">
                                          <div class="flex items-center gap-2 text-white/20">
                                               <MessageSquare class="w-3 h-3" />
                                               <span class="text-xs font-black uppercase tracking-widest">Client Briefing</span>
                                          </div>
                                          <p class="text-xs font-light opacity-90 italic leading-relaxed">"{{ enquiry.message }}"</p>
                                     </div>
                                </div>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Empty state -->
            <div v-else class="py-44 text-center border border-dashed border-white/10 rounded-sm">
                 <Compass class="w-20 h-20 mx-auto opacity-10 mb-8" />
                 <h2 class="font-display text-2xl font-black uppercase tracking-widest text-white/20">Clean Radar</h2>
                 <p class="text-xs opacity-20 uppercase tracking-[0.4em] mt-2">No active expedition requests found</p>
            </div>
        </div>
    </AppLayout>
</template>
