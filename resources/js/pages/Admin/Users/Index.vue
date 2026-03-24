<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Mail, ShieldCheck, Trash2, Users } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'System Users', href: '/admin/users' },
];

const props = defineProps<{
    users: any;
}>();

const toggleAdmin = (user: any) => {
    router.put(route('admin.users.update', { user: user.id }), {
        name: user.name,
        email: user.email,
        is_admin: !user.is_admin
    }, {
        preserveScroll: true,
    });
};

const deleteUser = (id: number) => {
    if (confirm('Are you absolutely sure you want to permanently delete this user?')) {
        router.delete(route('admin.users.destroy', { user: id }), {
            preserveScroll: true,
        });
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short', day: 'numeric', year: 'numeric'
    });
};
</script>

<template>
    <Head title="User Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-12 max-w-[1600px] mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="text-3xl font-black uppercase tracking-tighter mb-2">Platform Users</h1>
                      <p class="text-xs opacity-50 uppercase tracking-widest">{{ users.total }} Total Registers</p>
                 </div>
            </div>

            <!-- Users List -->
            <div v-if="users.data.length > 0" class="space-y-6">
                 <div v-for="user in users.data" :key="user.id" class="group bg-card border border-white/5 rounded-sm p-8 hover:border-safari-gold/30 transition-all duration-700">
                      <div class="flex flex-col xl:flex-row gap-10">
                           <!-- User Info -->
                           <div class="flex-1 space-y-6">
                                <div class="flex items-start justify-between">
                                     <div class="flex items-center gap-4">
                                          <div v-if="user.avatar" class="w-14 h-14 rounded-full overflow-hidden border border-white/10">
                                               <img :src="user.avatar" alt="Avatar" class="w-full h-full object-cover">
                                          </div>
                                          <div v-else class="w-14 h-14 rounded-full bg-safari-gold/5 flex items-center justify-center border border-safari-gold/10">
                                               <span class="text-sm font-black uppercase text-safari-gold">{{ user.name.charAt(0) }}</span>
                                          </div>
                                          <div>
                                               <h3 class="text-xl font-black uppercase tracking-tight flex items-center gap-3">
                                                    {{ user.name }}
                                                    <span v-if="user.is_admin" class="px-2 py-0.5 rounded-full text-[8px] tracking-widest bg-safari-gold/20 text-safari-gold border border-safari-gold/30">
                                                         SYS ADMIN
                                                    </span>
                                                    <span v-if="user.google_id" class="px-2 py-0.5 rounded-full text-[8px] tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                         GOOGLE
                                                    </span>
                                               </h3>
                                               <div class="flex items-center gap-4 mt-2">
                                                    <a :href="`mailto:${user.email}`" class="text-[10px] opacity-40 hover:opacity-100 flex items-center gap-2 tracking-widest uppercase">
                                                         <Mail class="w-3 h-3" /> {{ user.email }}
                                                    </a>
                                               </div>
                                          </div>
                                     </div>
                                     <div class="flex flex-col items-end gap-2">
                                          <p class="text-[9px] opacity-20 uppercase tracking-widest">Joined {{ formatDate(user.created_at) }}</p>
                                     </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 pt-6 border-t border-white/5">
                                     <div class="space-y-1">
                                          <p class="text-[9px] font-bold uppercase tracking-widest opacity-30">Total Expeditions</p>
                                          <p class="text-xs font-black uppercase tracking-tight text-safari-gold">{{ user.bookings_count }} Bookings</p>
                                     </div>
                                </div>
                           </div>

                           <!-- Actions -->
                           <div class="xl:w-64 flex flex-col justify-between gap-6 border-t xl:border-t-0 xl:border-l border-white/5 xl:pl-10 pt-6 xl:pt-0">
                                <div class="space-y-4">
                                     <p class="text-[9px] font-black uppercase tracking-widest opacity-30">Access Level</p>
                                     <button @click="toggleAdmin(user)" :class="['w-full py-3 text-[9px] font-black uppercase tracking-widest rounded-sm transition-all border flex flex-col items-center justify-center gap-2', user.is_admin ? 'bg-red-500/10 border-red-500/20 text-red-500 hover:bg-red-500/20' : 'bg-safari-gold/10 border-safari-gold/20 text-safari-gold hover:bg-safari-gold/20']">
                                          <ShieldCheck class="w-4 h-4" />
                                          {{ user.is_admin ? 'Revoke Admin' : 'Grant Admin' }}
                                     </button>
                                </div>

                                <div class="flex items-center justify-end">
                                     <button @click="deleteUser(user.id)" class="p-3 text-red-500/30 hover:text-red-500 transition-colors">
                                          <Trash2 class="w-4 h-4" />
                                     </button>
                                </div>
                           </div>
                      </div>
                 </div>

                 <!-- Pagination Details could be added here later using users.links -->
            </div>

            <!-- Empty State -->
            <div v-else class="py-32 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                 <Users class="w-12 h-12 opacity-10 mb-6" />
                 <h3 class="text-xl font-black uppercase tracking-widest mb-2">No Users Found</h3>
                 <p class="text-xs opacity-40 max-w-xs mb-8 uppercase tracking-widest">The system has no active users.</p>
            </div>
        </div>
    </AppLayout>
</template>
