<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Edit2, Trash2, MapPin, Globe } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Destinations', href: '/admin/destinations' },
];

defineProps<{
    destinations: any[];
}>();

const deleteDestination = (id: number) => {
    if (confirm('Are you sure? This will delete all associated tours and bookings if not handled.')) {
        router.delete(route('admin.destinations.destroy', { destination: id }), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Manage Destinations" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="text-3xl font-black uppercase tracking-tighter mb-2">Safari Regions</h1>
                      <p class="text-xs opacity-50 uppercase tracking-widest">Manage your Kenyan geographic hubs</p>
                 </div>
                 <Link :href="route('admin.destinations.create')" class="px-8 py-3 bg-safari-gold text-black text-[10px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-sm flex items-center gap-2">
                      <Plus class="w-3 h-3" /> New Region
                 </Link>
            </div>

            <div v-if="destinations.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                 <div v-for="dest in destinations" :key="dest.id" class="group bg-card border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/30 transition-all duration-700">
                      <div class="h-48 relative overflow-hidden">
                           <img :src="dest.image || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072'" class="absolute inset-0 h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110" />
                           <div class="absolute inset-0 bg-black/40"></div>
                      </div>
                      <div class="p-8">
                           <h3 class="text-xl font-black uppercase tracking-tight mb-4">{{ dest.name }}</h3>
                           <p class="text-[10px] opacity-40 uppercase tracking-widest mb-8 line-clamp-2 leading-relaxed">{{ dest.overview }}</p>
                           
                           <div class="flex items-center justify-between pt-6 border-t border-white/5">
                                <div class="flex items-center gap-2">
                                     <Link :href="route('admin.destinations.edit', dest.id)" class="p-2 border border-white/5 hover:border-safari-gold/50 hover:bg-safari-gold/5 transition-all">
                                          <Edit2 class="w-3.5 h-3.5 opacity-40 hover:opacity-100" />
                                     </Link>
                                     <button @click="deleteDestination(dest.id)" class="p-2 border border-white/5 hover:border-red-500/50 hover:bg-red-500/5 transition-all">
                                          <Trash2 class="w-3.5 h-3.5 opacity-40 hover:opacity-100" />
                                     </button>
                                </div>
                                <span class="text-[8px] font-black uppercase tracking-widest opacity-20 hover:opacity-40 cursor-default">
                                     REGION: {{ dest.slug }}
                                </span>
                           </div>
                      </div>
                 </div>
            </div>

            <div v-else class="py-32 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                 <Globe class="w-12 h-12 opacity-10 mb-6" />
                 <h3 class="text-xl font-black uppercase tracking-widest mb-2">No Regions Found</h3>
                 <Link :href="route('admin.destinations.create')" class="px-10 py-4 border border-white/20 text-[10px] font-black uppercase tracking-[0.3em] hover:bg-white hover:text-black transition-all">
                      Add Your First Region
                 </Link>
            </div>
        </div>
    </AppLayout>
</template>
