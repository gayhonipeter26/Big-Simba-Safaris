<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, Link } from '@inertiajs/vue3';
import { Mail, ShieldCheck, Trash2, Users, Search, MapPin, User, Calendar, Globe, Eye, Compass, Car, MessageSquare, LayoutGrid } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const expandedUserId = ref<number | null>(null);

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
            </div>            <!-- Users Table (Desktop) -->
            <div v-if="users.data.length > 0" class="hidden md:block overflow-x-auto border border-white/5 rounded-sm bg-card shadow-2xl">
                 <table class="w-full text-left border-collapse">
                      <thead>
                           <tr class="bg-black/50 border-b border-white/5">
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60 w-16">ID</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60">Operative Identity</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60">Operational Status</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60">Demographics</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60">Induction Date</th>
                                <th class="py-6 px-8 text-xs font-black uppercase tracking-[0.3em] text-safari-gold/60 text-right">Actions</th>
                           </tr>
                      </thead>
                      <tbody class="divide-y divide-white/5">
                           <template v-for="user in users.data" :key="user.id">
                                <tr class="group hover:bg-white/[0.02] transition-colors duration-300 cursor-pointer" @click="expandedUserId = expandedUserId === user.id ? null : user.id">
                                     <td class="py-6 px-8 text-sm font-black text-white/20">#{{ user.id.toString().padStart(3, '0') }}</td>
                                     <td class="py-6 px-8">
                                          <div class="flex items-center gap-5">
                                               <img v-if="user.avatar" :src="user.avatar" class="w-12 h-12 rounded-sm object-cover border border-white/10" />
                                               <div v-else class="w-12 h-12 rounded-sm bg-white/5 flex items-center justify-center border border-white/10 text-safari-gold text-sm font-black uppercase">
                                                    {{ user.name.charAt(0) }}
                                               </div>
                                               <div class="space-y-1">
                                                    <p class="text-sm font-black uppercase tracking-wider text-white flex items-center gap-2">
                                                        {{ user.name }}
                                                        <ShieldCheck v-if="user.is_admin" class="w-3.5 h-3.5 text-red-500" />
                                                    </p>
                                                    <p class="text-xs font-bold tracking-widest uppercase text-white/40 flex items-center gap-1.5"><Mail class="w-3 h-3" /> {{ user.email }}</p>
                                               </div>
                                          </div>
                                     </td>
                                     <td class="py-6 px-8">
                                          <div class="flex flex-wrap gap-2">
                                              <!-- Activity Counts -->
                                              <div class="px-2 py-1 bg-white/5 border border-white/5 rounded-sm flex items-center gap-2" title="Missions (Bookings)">
                                                  <Compass class="w-3 h-3 text-safari-gold" />
                                                  <span class="text-[10px] font-black">{{ user.bookings_count }}</span>
                                              </div>
                                              <div class="px-2 py-1 bg-white/5 border border-white/5 rounded-sm flex items-center gap-2" title="Logistics (Fleet Hires)">
                                                  <Car class="w-3 h-3 text-cyan-500" />
                                                  <span class="text-[10px] font-black">{{ user.fleet_hires_count }}</span>
                                              </div>
                                              <div class="px-2 py-1 bg-white/5 border border-white/5 rounded-sm flex items-center gap-2" title="Safari Pipeline (Enquiries)">
                                                  <MapPin class="w-3 h-3 text-purple-500" />
                                                  <span class="text-[10px] font-black">{{ user.tour_enquiries_count }}</span>
                                              </div>
                                              <div class="px-2 py-1 bg-white/5 border border-white/5 rounded-sm flex items-center gap-2" title="Strategic Reviews">
                                                  <MessageSquare class="w-3 h-3 text-green-500" />
                                                  <span class="text-[10px] font-black">{{ user.reviews_count }}</span>
                                              </div>
                                          </div>
                                     </td>
                                     <td class="py-6 px-8">
                                          <div class="space-y-2">
                                               <div class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.3em] text-white/40" title="Location">
                                                    <MapPin class="w-3 h-3 text-safari-gold/50" />
                                                    {{ user.location || 'GLOBAL' }}
                                               </div>
                                               <div class="flex items-center gap-6">
                                                   <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.3em] text-white/20" title="Clearance">
                                                        <ShieldCheck class="w-3 h-3 text-safari-gold/50" />
                                                        {{ user.is_admin ? 'SYS ADMIN' : 'STANDARD' }}
                                                   </div>
                                                   <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.3em] text-white/20" title="Auth Network">
                                                        <Globe class="w-3 h-3 text-safari-gold/50" />
                                                        {{ user.google_id ? 'OAUTH' : 'DIRECT' }}
                                                   </div>
                                               </div>
                                          </div>
                                     </td>
                                     <td class="py-6 px-8 text-sm font-bold uppercase tracking-widest text-white/30">
                                          {{ formatDate(user.created_at) }}
                                     </td>
                                     <td class="py-6 px-8 text-right space-x-3 whitespace-nowrap" @click.stop>
                                          <!-- Expanded View Button -->
                                          <button @click="expandedUserId = expandedUserId === user.id ? null : user.id" :title="expandedUserId === user.id ? 'Close Intel' : 'Intel Deep-Dive'" class="p-2.5 border border-white/10 text-white/30 rounded-sm hover:border-safari-gold hover:text-safari-gold transition-all inline-flex items-center justify-center group shadow-xl">
                                               <Eye class="w-4 h-4 transition-transform" :class="{'rotate-180 text-safari-gold': expandedUserId === user.id}" />
                                          </button>

                                          <!-- Grant/Revoke Admin -->
                                          <button @click="toggleAdmin(user)" :title="user.is_admin ? 'Revoke Clearance' : 'Grant Clearance'" class="p-2.5 border rounded-sm transition-all inline-flex items-center justify-center group/btn shadow-xl" :class="user.is_admin ? 'border-red-500/20 text-red-500' : 'border-safari-gold/20 text-safari-gold'">
                                               <ShieldCheck class="w-4 h-4" />
                                          </button>
                                          
                                          <!-- Delete User -->
                                          <button @click="deleteUser(user.id)" title="Decommission" class="p-2.5 border border-white/10 text-white/30 rounded-sm hover:border-red-500 hover:text-red-500 transition-all inline-flex items-center justify-center group/del shadow-xl">
                                               <Trash2 class="w-4 h-4" />
                                          </button>
                                     </td>
                                </tr>

                                <!-- Intelligence Deep-Dive Section -->
                                <tr v-if="expandedUserId === user.id" class="bg-black/40 animate-in fade-in slide-in-from-top-4 duration-500">
                                    <td colspan="6" class="p-0">
                                        <div class="border-y border-safari-gold/10 p-8 flex flex-col xl:flex-row gap-10">
                                            <!-- Activities Detail -->
                                            <div class="flex-1 space-y-6">
                                                <div class="flex items-center gap-4">
                                                    <p class="text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold">OPERATIVE INTELLIGENCE REPORT //</p>
                                                    <div class="h-px flex-1 bg-gradient-to-r from-safari-gold/20 to-transparent"></div>
                                                </div>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                                    <!-- Most Visited Pages -->
                                                    <div class="space-y-4">
                                                        <p class="text-[9px] font-black uppercase tracking-widest text-white/40 italic">HIGH FREQUENCY TARGETS (MOST VISITED)</p>
                                                        <div class="space-y-2">
                                                            <div v-for="page in user.most_visited_pages" :key="page.page" class="flex items-center justify-between p-3 bg-white/5 border border-white/5 rounded-sm group/page">
                                                                <span class="text-xs font-bold uppercase tracking-tight text-white/80 group-hover/page:text-safari-gold transition-colors">{{ page.page }}</span>
                                                                <span class="text-[10px] font-black text-safari-gold/40">{{ page.visits }} HITS</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Recent Activities -->
                                                    <div class="space-y-4">
                                                        <p class="text-[9px] font-black uppercase tracking-widest text-white/40 italic">LATEST MISSION ACTIVITIES</p>
                                                        <div class="space-y-2">
                                                            <div v-for="activity in user.recent_activity" :key="activity.time" class="flex items-center gap-4 p-3 bg-white/5 border border-white/5 rounded-sm">
                                                                <div class="w-8 h-8 rounded-sm flex items-center justify-center border border-white/10" :class="activity.type === 'recon' ? 'bg-cyan-500/10 text-cyan-500' : 'bg-safari-gold/10 text-safari-gold'">
                                                                    <LayoutGrid v-if="activity.type === 'recon'" class="w-3.5 h-3.5" />
                                                                    <Compass v-else class="w-3.5 h-3.5" />
                                                                </div>
                                                                <div class="flex-1">
                                                                    <p class="text-xs font-bold text-white/90">{{ activity.desc }}</p>
                                                                    <p class="text-[9px] font-black uppercase text-white/20 tracking-widest">{{ activity.time }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Metadata & Analysis -->
                                            <div class="xl:w-80 space-y-6 pt-10 xl:pt-0 xl:pl-10 xl:border-l border-white/5">
                                                <div class="space-y-4">
                                                    <p class="text-[9px] font-black uppercase tracking-widest text-white/40">SYSTEM METADATA</p>
                                                    <div class="space-y-3">
                                                        <div class="flex justify-between text-[10px] font-black uppercase tracking-widest">
                                                            <span class="text-white/20">TOTAL MISSIONS:</span>
                                                            <span class="text-safari-gold">{{ user.bookings_count + user.tour_enquiries_count }}</span>
                                                        </div>
                                                        <div class="flex justify-between text-[10px] font-black uppercase tracking-widest">
                                                            <span class="text-white/20">LAST RECON:</span>
                                                            <span class="text-white/60">{{ user.last_recon_at ? formatDate(user.last_recon_at) : 'UNKNOWN' }}</span>
                                                        </div>
                                                        <div class="flex justify-between text-[10px] font-black uppercase tracking-widest">
                                                            <span class="text-white/20">RECON IP:</span>
                                                            <span class="text-white/40 italic">{{ user.last_recon_ip || 'PROTECTED' }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="p-6 bg-safari-gold/5 border border-safari-gold/10 rounded-sm">
                                                    <p class="text-[8px] font-black uppercase tracking-[0.4em] text-safari-gold mb-3">// COMMAND ANALYSIS</p>
                                                    <p class="text-[10px] font-light italic leading-relaxed text-white/60">
                                                        Operative {{ user.name.split(' ')[0] }} shows consistent engagement with {{ user.most_visited_pages?.[0]?.page || 'primary targets' }}. Recommend prioritizing personalized mission offers based on high-frequency recon patterns.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                           </template>
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
                                <div @click="expandedUserId = expandedUserId === user.id ? null : user.id" class="cursor-pointer">
                                     <p class="text-xs font-black uppercase tracking-widest text-white flex items-center gap-2">
                                         {{ user.name }}
                                         <ShieldCheck v-if="user.is_admin" class="w-3 h-3 text-red-500" />
                                     </p>
                                     <p class="text-[10px] font-bold tracking-widest uppercase text-white/30 truncate max-w-[150px]">{{ user.email }}</p>
                                </div>
                           </div>
                           <span class="text-[10px] font-black text-white/20">#{{ user.id }}</span>
                      </div>

                      <div class="flex justify-between items-center bg-black/40 p-4 rounded-sm">
                          <div class="flex gap-4">
                              <div class="flex flex-col items-center">
                                  <Compass class="w-3.5 h-3.5 text-safari-gold mb-1" />
                                  <span class="text-[10px] font-black">{{ user.bookings_count }}</span>
                              </div>
                              <div class="flex flex-col items-center">
                                  <Car class="w-3.5 h-3.5 text-cyan-500 mb-1" />
                                  <span class="text-[10px] font-black">{{ user.fleet_hires_count }}</span>
                              </div>
                              <div class="flex flex-col items-center">
                                  <MapPin class="w-3.5 h-3.5 text-purple-500 mb-1" />
                                  <span class="text-[10px] font-black">{{ user.tour_enquiries_count }}</span>
                              </div>
                          </div>
                          <button @click="expandedUserId = expandedUserId === user.id ? null : user.id" class="text-[9px] font-black uppercase tracking-[0.2em] text-safari-gold border-b border-safari-gold/30">
                              {{ expandedUserId === user.id ? 'CLOSE INTEL' : 'VIEW INTEL' }}
                          </button>
                      </div>

                      <div v-if="expandedUserId === user.id" class="space-y-4 animate-in fade-in duration-300">
                          <div class="space-y-2">
                              <p class="text-[8px] font-black uppercase text-white/30 tracking-[0.2em]">TOP FREQUENCY TARGETS</p>
                              <div v-for="page in user.most_visited_pages" :key="page.page" class="flex justify-between p-2 bg-white/5 rounded-sm">
                                  <span class="text-[10px] font-bold text-white/70">{{ page.page }}</span>
                                  <span class="text-[9px] font-black text-safari-gold/40">{{ page.visits }}</span>
                              </div>
                          </div>
                      </div>

                      <div class="flex items-center justify-between pt-4 border-t border-white/5">
                           <div class="flex flex-wrap gap-2">
                                <span v-if="user.google_id" class="px-2 py-1 text-[8px] font-black uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-sm">OAUTH</span>
                                <span class="px-2 py-1 text-[8px] font-black uppercase tracking-widest bg-white/5 text-white/40 border border-white/10 rounded-sm">{{ user.location || 'GLOBAL' }}</span>
                           </div>
                           <div class="flex gap-2">
                                <button @click="toggleAdmin(user)" class="p-2 border rounded-sm transition-all shadow-xl" :class="user.is_admin ? 'border-red-500/20 text-red-500' : 'border-safari-gold/20 text-safari-gold'">
                                     <ShieldCheck class="w-3.5 h-3.5" />
                                </button>
                                <button @click="deleteUser(user.id)" class="p-2 border border-white/10 text-white/30 rounded-sm shadow-xl">
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
