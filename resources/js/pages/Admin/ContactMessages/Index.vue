<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Trash2, Mail, User, Clock, CheckCircle, Archive, AlertCircle } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Inquiries', href: '/admin/contact-messages' },
];

defineProps<{
    messages: any[];
}>();

const updateStatus = (id: number, status: string) => {
    router.patch(route('admin.contact-messages.update', { contact_message: id }), {
        status: status,
    }, {
        preserveScroll: true,
    });
};

const deleteMessage = (id: number) => {
    if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
        router.delete(route('admin.contact-messages.destroy', { contact_message: id }), {
            preserveScroll: true,
        });
    }
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'pending': return 'text-amber-500 bg-amber-500/10 border-amber-500/20';
        case 'responded': return 'text-green-500 bg-green-500/10 border-green-500/20';
        case 'archived': return 'text-safari-gold bg-safari-gold/10 border-safari-gold/20';
        default: return 'text-white/40 bg-white/5 border-white/10';
    }
};
</script>

<template>
    <Head title="Guest Inquiries" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="text-3xl font-black uppercase tracking-tighter mb-2">Safari Intel</h1>
                      <p class="text-xs opacity-50 uppercase tracking-widest">Review and manage inbound guest communications</p>
                 </div>
            </div>

            <!-- Messages List -->
            <div v-if="messages.length > 0" class="space-y-6">
                 <div v-for="message in messages" :key="message.id" class="group bg-card border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/30 transition-all duration-700 p-8">
                      <div class="flex flex-col lg:flex-row gap-8 justify-between">
                           <div class="space-y-6 flex-1">
                                <div class="flex items-center gap-6">
                                     <span :class="['px-3 py-1 text-[8px] font-black uppercase tracking-widest rounded-full border', getStatusColor(message.status)]">
                                          {{ message.status }}
                                     </span>
                                     <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider opacity-30">
                                          <Clock class="w-3.5 h-3.5" />
                                          {{ new Date(message.created_at).toLocaleString() }}
                                     </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                     <div class="space-y-2">
                                          <p class="text-[9px] font-black uppercase tracking-[0.4em] opacity-30">Inbound Source</p>
                                          <div class="flex items-center gap-3">
                                               <User class="w-4 h-4 text-safari-gold/40" />
                                               <h3 class="text-lg font-black uppercase tracking-tight">{{ message.name }}</h3>
                                          </div>
                                          <div class="flex items-center gap-3">
                                               <Mail class="w-4 h-4 text-safari-gold/40" />
                                               <a :href="'mailto:' + message.email" class="text-sm opacity-60 hover:text-safari-gold transition-colors">{{ message.email }}</a>
                                          </div>
                                     </div>
                                     <div class="space-y-2">
                                          <p class="text-[9px] font-black uppercase tracking-[0.4em] opacity-30">Mission Detail</p>
                                          <p class="text-sm font-light leading-relaxed opacity-70 italic">"{{ message.message }}"</p>
                                     </div>
                                </div>
                           </div>

                           <div class="flex lg:flex-col items-center justify-end gap-3 pt-6 lg:pt-0 lg:pl-10 lg:border-l border-white/5">
                                <button 
                                     v-if="message.status !== 'responded'"
                                     @click="updateStatus(message.id, 'responded')" 
                                     class="p-3 border border-white/5 hover:border-green-500/50 hover:bg-green-500/5 transition-all group/btn flex items-center gap-3 w-full lg:w-44 whitespace-nowrap"
                                     title="Mark as Responded"
                                >
                                     <CheckCircle class="w-4 h-4 opacity-40 group-hover/btn:opacity-100 group-hover/btn:text-green-500" />
                                     <span class="text-[9px] font-black uppercase tracking-widest opacity-0 group-hover/btn:opacity-100 transition-opacity">Mark Responded</span>
                                </button>
                                <button 
                                     v-if="message.status !== 'archived'"
                                     @click="updateStatus(message.id, 'archived')" 
                                     class="p-3 border border-white/5 hover:border-safari-gold/50 hover:bg-safari-gold/5 transition-all group/btn flex items-center gap-3 w-full lg:w-44 whitespace-nowrap"
                                     title="Archive Message"
                                >
                                     <Archive class="w-4 h-4 opacity-40 group-hover/btn:opacity-100 group-hover/btn:text-safari-gold" />
                                     <span class="text-[9px] font-black uppercase tracking-widest opacity-0 group-hover/btn:opacity-100 transition-opacity">Archive Source</span>
                                </button>
                                <button 
                                     v-if="message.status !== 'pending'"
                                     @click="updateStatus(message.id, 'pending')" 
                                     class="p-3 border border-white/5 hover:border-amber-500/50 hover:bg-amber-500/5 transition-all group/btn flex items-center gap-3 w-full lg:w-44 whitespace-nowrap"
                                     title="Set to Pending"
                                >
                                     <AlertCircle class="w-4 h-4 opacity-40 group-hover/btn:opacity-100 group-hover/btn:text-amber-500" />
                                     <span class="text-[9px] font-black uppercase tracking-widest opacity-0 group-hover/btn:opacity-100 transition-opacity">Reset to Pending</span>
                                </button>
                                <button 
                                     @click="deleteMessage(message.id)" 
                                     class="p-3 border border-white/5 hover:border-red-500/50 hover:bg-red-500/5 transition-all group/btn flex items-center gap-3 w-full lg:w-44 whitespace-nowrap font-black"
                                     title="Expunge Message"
                                >
                                     <Trash2 class="w-4 h-4 opacity-40 group-hover/btn:opacity-100 group-hover/btn:text-red-500" />
                                     <span class="text-[9px] font-black uppercase tracking-widest opacity-0 group-hover/btn:opacity-100 transition-opacity">Expunge Intel</span>
                                </button>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-32 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                 <Mail class="w-12 h-12 opacity-10 mb-6" />
                 <h3 class="text-xl font-black uppercase tracking-widest mb-2">No Inbound Intel</h3>
                 <p class="text-xs opacity-40 max-w-xs uppercase tracking-widest">The contact logs are currently silent.</p>
            </div>
        </div>
    </AppLayout>
</template>
