<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Mail, ShieldCheck, Trash2, Users, Search, MapPin, User, Calendar } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'System Users', href: '/admin/users' },
];

const props = defineProps<{
    users: any;
    filters?: any;
}>();

const searchQuery = ref(props.filters?.search || '');

let searchTimeout: any = null;
watch(searchQuery, (value) => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('admin.users.index'), { search: value }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }, 400);
});

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
    if (!date) return '—';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short', day: 'numeric', year: 'numeric'
    });
};
</script>

<template>
    <Head title="User Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-12 max-w-[1700px] mx-auto">
            <!-- Header & Search -->
            <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-8 pb-10 border-b border-white/5">
                 <div class="space-y-6 flex-1">
                      <div>
                           <p class="text-sm font-black tracking-[0.4em] uppercase text-safari-gold mb-2">// IDENTITY DIRECTORY</p>
                           <h1 class="font-display text-4xl font-black uppercase tracking-tighter">System Operatives</h1>
                      </div>
                      
                      <!-- Search Bar -->
                      <div class="relative w-full max-w-lg group">
                           <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white/20 group-focus-within:text-safari-gold transition-colors" />
                           <input 
                                v-model="searchQuery"
                                type="text"
                                placeholder="SEARCH OPERATIVES BY NAME, EMAIL OR LOCATION..."
                                class="w-full bg-black/40 border border-white/10 rounded-sm pl-12 pr-4 py-3 text-sm font-black uppercase tracking-[0.2em] focus:border-safari-gold transition-all outline-none text-white placeholder:opacity-70"
                           />
                      </div>
                 </div>
                 <div class="text-sm font-black uppercase tracking-widest px-4 py-3 border border-safari-gold/20 text-safari-gold bg-safari-gold/5 rounded-sm shrink-0 flex items-center gap-3">
                      <Users class="w-4 h-4" />
                      Total Assets: {{ users.total || users.data.length }}
                 </div>
            </div>

            <!-- Users Table (Desktop) -->
            <div v-if="users.data.length > 0" class="hidden md:block overflow-x-auto border border-white/5 rounded-sm bg-card shadow-2xl">
                 <table class="w-full text-left border-collapse">
                      <thead>
                           <tr class="bg-black/50 border-b border-white/5">
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60 w-16">ID</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60">Operative Identity</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60">Demographics</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60">Clearance Level</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60">Auth Network</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60">Induction Date</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60 text-right">Actions</th>
                           </tr>
                      </thead>
                      <tbody class="divide-y divide-white/5">
                           <tr v-for="user in users.data" :key="user.id" class="group hover:bg-white/[0.02] transition-colors duration-300">
                                <td class="py-6 px-8 text-sm font-black text-white/20">#{{ user.id.toString().padStart(3, '0') }}</td>
                                <td class="py-6 px-8">
                                     <div class="flex items-center gap-5">
                                          <img v-if="user.avatar" :src="user.avatar" class="w-12 h-12 rounded-sm object-cover border border-white/10" />
                                          <div v-else class="w-12 h-12 rounded-sm bg-white/5 flex items-center justify-center border border-white/10 text-safari-gold text-sm font-black uppercase">
                                               {{ user.name.charAt(0) }}
                                          </div>
                                          <div class="space-y-1">
                                               <p class="text-sm font-black uppercase tracking-wider text-white">{{ user.name }}</p>
                                               <p class="text-xs font-bold tracking-widest uppercase text-white/40 flex items-center gap-1.5"><Mail class="w-3 h-3" /> {{ user.email }}</p>
                                          </div>
                                     </div>
                                </td>
                                <td class="py-6 px-8">
                                     <div class="space-y-2">
                                          <div class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.3em] text-white/40" title="Location">
                                               <MapPin class="w-3 h-3 text-safari-gold/50" />
                                               {{ user.location || '—' }}
                                          </div>
                                          <div class="flex items-center gap-6">
                                              <div class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.3em] text-white/40" title="Gender">
                                                   <User class="w-3 h-3 text-safari-gold/50" />
                                                   {{ user.gender || '—' }}
                                              </div>
                                              <div class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.3em] text-white/40" title="Birthday">
                                                   <Calendar class="w-3 h-3 text-safari-gold/50" />
                                                   {{ formatDate(user.birthday) }}
                                              </div>
                                          </div>
                                     </div>
                                </td>
                                <td class="py-6 px-8">
                                     <span v-if="user.is_admin" class="px-3 py-1.5 text-xs font-black uppercase tracking-[0.3em] bg-red-500/10 text-red-500 border border-red-500/20 rounded-sm shadow-[0_0_15px_rgba(239,68,68,0.1)] inline-block">SYS ADMIN</span>
                                     <span v-else class="px-3 py-1.5 text-xs font-black uppercase tracking-[0.3em] bg-white/5 text-white/40 border border-white/10 rounded-sm inline-block">STANDARD</span>
                                </td>
                                <td class="py-6 px-8">
                                     <span v-if="user.google_id" class="px-3 py-1.5 text-xs font-black uppercase tracking-[0.3em] bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-sm shadow-[0_0_15px_rgba(59,130,246,0.1)] inline-flex items-center">
                                          GOOGLE OAUTH
                                     </span>
                                     <span v-else class="px-3 py-1.5 text-xs font-black uppercase tracking-[0.3em] bg-white/5 text-white/40 border border-white/10 rounded-sm inline-flex">DIRECT DB</span>
                                </td>
                                <td class="py-6 px-8 text-sm font-bold uppercase tracking-widest text-white/30">
                                     {{ formatDate(user.created_at) }}
                                </td>
                                <td class="py-6 px-8 text-right space-x-3 whitespace-nowrap">
                                     <!-- Grant/Revoke Admin -->
                                     <button @click="toggleAdmin(user)" :title="user.is_admin ? 'Revoke System Admin Clearance' : 'Grant System Admin Clearance'" class="p-2.5 border rounded-sm transition-all inline-flex items-center justify-center group/btn shadow-xl" :class="user.is_admin ? 'border-red-500/20 text-red-500 hover:bg-red-500 hover:text-white' : 'border-safari-gold/20 text-safari-gold hover:bg-safari-gold hover:text-black'">
                                          <ShieldCheck class="w-4 h-4 transition-transform group-hover/btn:scale-110" />
                                     </button>
                                     
                                     <!-- Delete User -->
                                     <button @click="deleteUser(user.id)" title="Decommission Operative" class="p-2.5 border border-white/10 text-white/30 rounded-sm hover:border-red-500 hover:bg-red-500 hover:text-white transition-all inline-flex items-center justify-center group/del shadow-xl">
                                          <Trash2 class="w-4 h-4 transition-transform group-hover/del:scale-110" />
                                     </button>
                                </td>
                           </tr>
                      </tbody>
                 </table>
            </div>

            <!-- Users Grid (Mobile Cards) -->
            <div v-if="users.data.length > 0" class="grid grid-cols-1 gap-6 md:hidden">
                 <div v-for="user in users.data" :key="user.id" class="bg-card border border-white/5 p-6 rounded-sm space-y-6">
                      <div class="flex items-center justify-between border-b border-white/5 pb-4">
                           <div class="flex items-center gap-4">
                                <img v-if="user.avatar" :src="user.avatar" class="w-10 h-10 rounded-sm object-cover border border-white/10" />
                                <div v-else class="w-10 h-10 rounded-sm bg-white/5 flex items-center justify-center border border-white/10 text-safari-gold text-xs font-black uppercase">
                                     {{ user.name.charAt(0) }}
                                </div>
                                <div>
                                     <p class="text-xs font-black uppercase tracking-widest text-white">{{ user.name }}</p>
                                     <p class="text-[10px] font-bold tracking-widest uppercase text-white/30 truncate max-w-[150px]">{{ user.email }}</p>
                                </div>
                           </div>
                           <span class="text-[10px] font-black text-white/20">#{{ user.id }}</span>
                      </div>

                      <div class="grid grid-cols-2 gap-4">
                           <div class="space-y-1">
                                <p class="text-[8px] font-black uppercase tracking-widest text-safari-gold/50">Demographics</p>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-white/60 truncate">{{ user.location || 'N/A' }}</p>
                           </div>
                           <div class="space-y-1">
                                <p class="text-[8px] font-black uppercase tracking-widest text-safari-gold/50">Induction</p>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-white/60">{{ formatDate(user.created_at) }}</p>
                           </div>
                      </div>

                      <div class="flex items-center justify-between pt-4">
                           <div class="flex flex-wrap gap-2">
                                <span v-if="user.is_admin" class="px-2 py-1 text-[8px] font-black uppercase tracking-widest bg-red-500/10 text-red-500 border border-red-500/20 rounded-sm">ADMIN</span>
                                <span v-if="user.google_id" class="px-2 py-1 text-[8px] font-black uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-sm">OAUTH</span>
                           </div>
                           <div class="flex gap-2">
                                <button @click="toggleAdmin(user)" class="p-2 border rounded-sm transition-all" :class="user.is_admin ? 'border-red-500/20 text-red-500' : 'border-safari-gold/20 text-safari-gold'">
                                     <ShieldCheck class="w-3.5 h-3.5" />
                                </button>
                                <button @click="deleteUser(user.id)" class="p-2 border border-white/10 text-white/30 rounded-sm">
                                     <Trash2 class="w-3.5 h-3.5" />
                                </button>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Tactical Pagination -->
            <div v-if="users.links?.length > 3" class="flex flex-wrap items-center justify-center gap-2 pt-10">
                 <template v-for="(link, key) in users.links" :key="key">
                      <div v-if="link.url === null" class="px-4 py-2 text-[10px] font-black uppercase tracking-widest opacity-20 border border-white/5 rounded-sm" v-html="link.label"></div>
                      <Link 
                           v-else 
                           :href="link.url" 
                           class="px-4 py-2 text-[10px] font-black uppercase tracking-widest border border-white/10 rounded-sm transition-all hover:border-safari-gold/50 hover:bg-safari-gold/5" 
                           :class="{ 'bg-safari-gold text-black border-safari-gold opacity-100': link.active, 'text-white/60 opacity-60': !link.active }" 
                           v-html="link.label"
                      />
                 </template>
            </div>

            <!-- Empty State -->
            <div v-if="users.data.length === 0" class="py-32 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center bg-white/[0.01]">
                 <Users class="w-12 h-12 opacity-10 mb-6 text-safari-gold" />
                 <h3 class="font-display text-xl font-black uppercase tracking-widest mb-2 text-white">No Operatives Found</h3>
                 <p class="text-sm opacity-80 max-w-xs mb-8 uppercase tracking-[0.3em] font-bold italic">The identity directory is currently empty or no matches found for your search.</p>
            </div>
        </div>
    </AppLayout>
</template>
