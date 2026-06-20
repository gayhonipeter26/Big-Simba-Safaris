<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Edit2, Trash2, Calendar, MapPin, Clock } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Strategic Events', href: '/admin/strategic-events' },
];

defineProps<{
    events: any[];
}>();

const monthNames = ["JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"];

const deleteEvent = (id: number) => {
    if (confirm('Erase this event from the timeline? This action is permanent.')) {
        router.delete(route('admin.strategic-events.destroy', { strategic_event: id }), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Strategic Timeline" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="font-display text-3xl font-black uppercase tracking-tighter mb-2">Strategic Timeline</h1>
                      <p class="text-xs opacity-90 uppercase tracking-widest">Manage yearly tactical operations</p>
                 </div>
                 <Link :href="route('admin.strategic-events.create')" class="px-8 py-3 bg-safari-gold text-black text-sm font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-sm flex items-center gap-2">
                      <Plus class="w-3 h-3" /> Schedule Event
                 </Link>
            </div>

            <!-- Events Grid -->
            <div v-if="events.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                 <div v-for="event in events" :key="event.id" class="group bg-card border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/30 transition-all duration-700 flex flex-col relative">
                      
                      <!-- Status Banner -->
                      <div v-if="event.status === 'draft'" class="absolute -right-12 top-6 bg-red-600/90 text-white text-[10px] font-black uppercase px-12 py-1 rotate-45 z-10 tracking-widest border border-red-500/50 shadow-lg">
                          DRAFT
                      </div>

                      <div class="aspect-video relative overflow-hidden bg-black/50">
                           <img v-if="event.image" :src="event.image" class="absolute inset-0 h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale-[0.2]" />
                           <div v-else class="absolute inset-0 flex items-center justify-center opacity-10">
                               <Calendar class="w-16 h-16" />
                           </div>
                           <div class="absolute top-4 left-4 flex gap-2">
                                <span :class="`px-3 py-1 bg-black/80 backdrop-blur-md ${event.color.replace('bg-', 'text-')} text-[10px] font-black uppercase tracking-widest border border-white/10`">
                                     {{ event.label }}
                                </span>
                                <span class="px-3 py-1 bg-white/10 backdrop-blur-md text-[10px] font-black uppercase tracking-widest border border-white/10">
                                    {{ monthNames[event.month] }}
                                </span>
                           </div>
                           <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90"></div>
                      </div>

                      <div class="p-8 flex-1 flex flex-col justify-between relative">
                           <!-- Background Accent -->
                           <div :class="`absolute top-0 right-0 w-32 h-32 blur-[100px] opacity-10 ${event.color} rounded-full`"></div>

                           <div class="relative z-10">
                                <h3 class="font-display text-xl font-black uppercase tracking-tight mb-2 group-hover:text-safari-gold transition-colors">{{ event.name }}</h3>
                                <p class="text-xs uppercase tracking-widest opacity-70 mb-6 font-bold truncate">{{ event.occasion }}</p>

                                <div class="grid grid-cols-1 gap-4 mb-8 border-l border-white/10 pl-4 py-2 opacity-80">
                                     <div class="flex items-center gap-3 text-xs font-bold uppercase tracking-wider">
                                          <Calendar class="w-3.5 h-3.5 text-safari-gold/50" />
                                          Days: {{ event.days.join(', ') }}
                                     </div>
                                     <div class="flex items-center gap-3 text-xs font-bold uppercase tracking-wider">
                                          <MapPin class="w-3.5 h-3.5 text-safari-gold/50" />
                                          {{ event.location }}
                                     </div>
                                </div>
                                
                           </div>

                           <div class="flex items-center justify-between pt-6 border-t border-white/5 relative z-10">
                                <div class="flex items-center gap-2">
                                     <Link :href="route('admin.strategic-events.edit', { strategic_event: event.id })" class="p-2 border border-white/5 hover:border-safari-gold/50 hover:bg-safari-gold/5 transition-all group/btn">
                                          <Edit2 class="w-3.5 h-3.5 opacity-80 group-hover/btn:opacity-100 group-hover/btn:text-safari-gold" />
                                     </Link>
                                     <button @click="deleteEvent(event.id)" class="p-2 border border-white/5 hover:border-red-500/50 hover:bg-red-500/5 transition-all group/btn">
                                          <Trash2 class="w-3.5 h-3.5 opacity-80 group-hover/btn:opacity-100 group-hover/btn:text-red-500" />
                                     </button>
                                </div>
                                <div class="text-[10px] font-black text-right uppercase tracking-[0.2em] opacity-50">
                                    {{ event.asset_class }}
                                </div>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-32 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center relative overflow-hidden">
                 <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:24px_24px] opacity-20"></div>
                 <Calendar class="w-12 h-12 opacity-20 mb-6 relative z-10 text-safari-gold" />
                 <h3 class="font-display text-xl font-black uppercase tracking-widest mb-2 relative z-10 text-white">Timeline Clear</h3>
                 <p class="text-xs opacity-60 max-w-sm mx-auto mb-8 uppercase tracking-widest leading-relaxed relative z-10">No strategic operations or seasonal events have been mapped to the calendar.</p>
                 <Link :href="route('admin.strategic-events.create')" class="px-10 py-4 border border-safari-gold/30 text-safari-gold text-xs font-black uppercase tracking-[0.3em] hover:bg-safari-gold hover:text-black transition-all relative z-10">
                      Initialize Timeline
                 </Link>
            </div>
        </div>
    </AppLayout>
</template>
