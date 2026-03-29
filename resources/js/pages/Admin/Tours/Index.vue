<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Edit2, Trash2, MapPin, Clock, DollarSign, Camera, ArrowRight, ExternalLink } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Manage Tours', href: '/admin/tours' },
];

defineProps<{
    tours: any[];
}>();

const deleteTour = (id: number) => {
    if (confirm('Are you sure you want to delete this tour? This action cannot be undone.')) {
        router.delete(route('admin.tours.destroy', { tour: id }), {
            preserveScroll: true,
        });
    }
};

const formatPrice = (price: string | number) => {
    return new Intl.NumberFormat('en-KE', { style: 'currency', currency: 'KES' }).format(parseFloat(price.toString()));
};
</script>

<template>
    <Head title="Manage Tours" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="font-display text-3xl font-black uppercase tracking-tighter mb-2">Safari Inventory</h1>
                      <p class="text-xs opacity-90 uppercase tracking-widest">Manage your Kenyan expedition packages</p>
                 </div>
                 <Link :href="route('admin.tours.create')" class="px-8 py-3 bg-safari-gold text-black text-sm font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-sm flex items-center gap-2">
                      <Plus class="w-3 h-3" /> Create New Tour
                 </Link>
            </div>

            <!-- Tours Grid -->
            <div v-if="tours.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                 <div v-for="tour in tours" :key="tour.id" class="group bg-card border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/30 transition-all duration-700 flex flex-col">
                      <div class="aspect-video relative overflow-hidden">
                           <img :src="tour.images?.[0] || 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071'" class="absolute inset-0 h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale-[0.2]" />
                           <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-black/80 backdrop-blur-md text-safari-gold text-xs font-black uppercase tracking-widest rounded-full border border-safari-gold/20">
                                     {{ tour.destination?.name }}
                                </span>
                           </div>
                           <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90"></div>
                      </div>

                      <div class="p-8 flex-1 flex flex-col justify-between">
                           <div>
                                <h3 class="font-display text-xl font-black uppercase tracking-tight mb-6 group-hover:text-safari-gold transition-colors">{{ tour.name }}</h3>
                                <div class="grid grid-cols-2 gap-4 mb-8">
                                     <div class="flex items-center gap-2 text-sm font-bold uppercase tracking-wider opacity-90">
                                          <Clock class="w-3.5 h-3.5 text-safari-gold/50" />
                                          {{ tour.duration }}
                                     </div>
                                     <div class="flex items-center gap-2 text-sm font-black uppercase tracking-wider text-safari-gold">
                                          <DollarSign class="w-3.5 h-3.5" />
                                          {{ parseFloat(tour.price).toLocaleString() }}
                                     </div>
                                </div>
                           </div>

                           <div class="flex items-center justify-between pt-6 border-t border-white/5">
                                <div class="flex items-center gap-2">
                                     <Link :href="route('admin.tours.edit', { tour: tour.id })" class="p-2 border border-white/5 hover:border-safari-gold/50 hover:bg-safari-gold/5 transition-all group/btn">
                                          <Edit2 class="w-3.5 h-3.5 opacity-80 group-hover/btn:opacity-100 group-hover/btn:text-safari-gold" />
                                     </Link>
                                     <button @click="deleteTour(tour.id)" class="p-2 border border-white/5 hover:border-red-500/50 hover:bg-red-500/5 transition-all group/btn">
                                          <Trash2 class="w-3.5 h-3.5 opacity-80 group-hover/btn:opacity-100 group-hover/btn:text-red-500" />
                                     </button>
                                </div>
                                <Link :href="route('tours.show', tour.slug)" target="_blank" class="text-xs font-black uppercase tracking-widest opacity-80 hover:opacity-100 flex items-center gap-2">
                                     Preview <ExternalLink class="w-2.5 h-2.5" />
                                </Link>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-32 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                 <Camera class="w-12 h-12 opacity-10 mb-6" />
                 <h3 class="font-display text-xl font-black uppercase tracking-widest mb-2">No Expeditions Found</h3>
                 <p class="text-xs opacity-80 max-w-xs mb-8 uppercase tracking-widest">Your wilderness catalog is currently empty.</p>
                 <Link :href="route('admin.tours.create')" class="px-10 py-4 border border-white/20 text-sm font-black uppercase tracking-[0.3em] hover:bg-white hover:text-black transition-all">
                      Add Your First Tour
                 </Link>
            </div>
        </div>
    </AppLayout>
</template>
