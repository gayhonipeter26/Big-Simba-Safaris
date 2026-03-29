<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Car, Trash2, CheckCircle, XCircle, Clock, MapPin, User, Mail, Phone, MessageSquare } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Fleet Recon', href: '/admin/fleet' },
    { title: 'Hiring Orders', href: '/admin/fleet-hires' },
];

const props = defineProps<{
    hires: any[];
}>();

const updateStatus = (id: number, status: string) => {
    router.patch(route('admin.fleet-hires.update', { fleetHire: id }), {
        status: status
    }, {
        preserveScroll: true
    });
};

const deleteRecord = (id: number) => {
    if (confirm('Purge this deployment record from the archives?')) {
        router.delete(route('admin.fleet-hires.destroy', { fleetHire: id }), {
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
    <Head title="Hiring Orders - Big Simba Fleet" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto pb-44">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <p class="text-sm font-black uppercase tracking-[0.4em] text-safari-gold mb-3">Logistics Command</p>
                      <h1 class="font-display text-4xl font-black uppercase tracking-tighter">Hiring <span class="text-safari-gold/30">Orders</span></h1>
                      <p class="text-xs opacity-80 mt-2 uppercase tracking-widest italic">Monitor and authorize field deployments</p>
                 </div>
            </div>

            <div v-if="hires.length > 0" class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                 <div v-for="hire in hires" :key="hire.id" class="group bg-card border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-700 shadow-2xl">
                      <div class="flex flex-col md:flex-row h-full">
                           <!-- Vehicle Preview -->
                           <div class="md:w-1/3 relative bg-black border-r border-white/5">
                                <img :src="hire.fleet?.image" class="h-full w-full object-cover opacity-90 group-hover:scale-110 transition-transform duration-[2000ms]" />
                                <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black to-transparent">
                                     <p class="text-xs font-black uppercase tracking-widest text-safari-gold mb-1">{{ hire.fleet?.drive_type }}</p>
                                     <h3 class="font-display text-sm font-black uppercase tracking-tight text-white">{{ hire.fleet?.name }}</h3>
                                </div>
                           </div>

                           <!-- Intel -->
                           <div class="md:w-2/3 p-8 flex flex-col justify-between">
                                <div class="space-y-6">
                                     <div class="flex items-center justify-between">
                                          <div class="flex items-center gap-4">
                                               <span :class="['px-3 py-1 text-xs font-black uppercase tracking-widest border rounded-sm', getStatusColor(hire.status)]">
                                                    {{ hire.status }}
                                               </span>
                                               <span class="text-xs font-bold opacity-70 text-white uppercase tracking-widest italic">Request #{{ hire.id.toString().padStart(4, '0') }}</span>
                                          </div>
                                          <div class="flex items-center gap-2">
                                               <button v-if="hire.status === 'pending'" @click="updateStatus(hire.id, 'approved')" class="p-2 border border-white/5 hover:border-green-500/50 hover:bg-green-500/5 text-green-500/40 hover:text-green-500 transition-all rounded-sm" title="Approve">
                                                    <CheckCircle class="w-4 h-4" />
                                               </button>
                                               <button v-if="hire.status === 'pending'" @click="updateStatus(hire.id, 'rejected')" class="p-2 border border-white/5 hover:border-red-500/50 hover:bg-red-500/5 text-red-500/40 hover:text-red-500 transition-all rounded-sm" title="Reject">
                                                    <XCircle class="w-4 h-4" />
                                               </button>
                                               <button v-if="hire.status === 'approved'" @click="updateStatus(hire.id, 'completed')" class="p-2 border border-white/5 hover:border-blue-500/50 hover:bg-blue-500/5 text-blue-500/40 hover:text-blue-500 transition-all rounded-sm" title="Mark Completed">
                                                    <CheckCircle class="w-4 h-4" />
                                               </button>
                                               <button @click="deleteRecord(hire.id)" class="p-2 border border-white/5 hover:border-white/20 text-white/20 hover:text-white transition-all rounded-sm" title="Delete Archive">
                                                    <Trash2 class="w-4 h-4" />
                                               </button>
                                          </div>
                                     </div>

                                     <div class="grid grid-cols-2 gap-8 py-6 border-y border-white/5">
                                          <div class="space-y-4">
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <User class="w-3.5 h-3.5" />
                                                    <span class="text-sm font-black uppercase tracking-widest">{{ hire.name }}</span>
                                               </div>
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <Mail class="w-3.5 h-3.5" />
                                                    <span class="text-xs font-bold truncate">{{ hire.email }}</span>
                                               </div>
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <Phone class="w-3.5 h-3.5" />
                                                    <span class="text-sm font-black tracking-widest">{{ hire.phone }}</span>
                                               </div>
                                          </div>
                                          <div class="space-y-4">
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <Clock class="w-3.5 h-3.5 text-safari-gold" />
                                                    <span class="text-sm font-black uppercase tracking-widest">{{ new Date(hire.start_date).toLocaleDateString() }}</span>
                                               </div>
                                               <div class="flex items-center gap-3 text-white/40 ml-1">
                                                    <span class="text-xs font-black uppercase tracking-[0.2em] opacity-70">To</span>
                                               </div>
                                               <div class="flex items-center gap-3 text-white/40">
                                                    <Clock class="w-3.5 h-3.5 text-safari-gold" />
                                                    <span class="text-sm font-black uppercase tracking-widest">{{ new Date(hire.end_date).toLocaleDateString() }}</span>
                                               </div>
                                          </div>
                                     </div>

                                     <div v-if="hire.message" class="space-y-2">
                                          <div class="flex items-center gap-2 text-white/20">
                                               <MessageSquare class="w-3 h-3" />
                                               <span class="text-xs font-black uppercase tracking-widest">Client Briefing</span>
                                          </div>
                                          <p class="text-xs font-light opacity-90 italic leading-relaxed">"{{ hire.message }}"</p>
                                     </div>
                                </div>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Empty state -->
            <div v-else class="py-44 text-center border border-dashed border-white/10 rounded-sm">
                 <Car class="w-20 h-20 mx-auto opacity-10 mb-8" />
                 <h2 class="font-display text-2xl font-black uppercase tracking-widest text-white/20">The Field is Empty</h2>
                 <p class="text-xs opacity-20 uppercase tracking-[0.4em] mt-2">No active logistics missions found</p>
            </div>
        </div>
    </AppLayout>
</template>
