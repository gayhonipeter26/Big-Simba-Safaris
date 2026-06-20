<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Save, Ban, CalendarDays, MapPin, Tag, Flag } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Strategic Events', href: '/admin/strategic-events' },
    { title: 'Schedule Operation', href: '/admin/strategic-events/create' },
];

const form = useForm({
    name: '',
    occasion: '',
    month: 0,
    days: [] as number[],
    color: 'bg-safari-gold',
    label: 'Event',
    location: '',
    region: '',
    time: '',
    description: '',
    asset_class: 'TACTICAL SURVEY',
    image: '',
    status: 'published',
});

const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const colorOptions = ['bg-safari-gold', 'bg-green-500', 'bg-red-500', 'bg-blue-500', 'bg-orange-500', 'bg-purple-500', 'bg-emerald-500'];

const saveEvent = () => {
    // Process days string to array of numbers
    if (typeof form.days === 'string') {
        form.days = (form.days as string).split(',').map((d: string) => parseInt(d.trim(), 10)).filter((d: number) => !isNaN(d));
    }
    
    form.post(route('admin.strategic-events.store'));
};
</script>

<template>
    <Head title="Schedule Event" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-6 py-12 md:p-12 max-w-4xl mx-auto space-y-8">
             <div class="border-b border-white/10 pb-6 flex items-center justify-between">
                  <div>
                       <h1 class="font-display text-2xl font-black uppercase tracking-widest text-safari-gold">New Operation</h1>
                       <p class="text-xs opacity-60 uppercase tracking-widest mt-2">Map a seasonal target to the administrative timeline.</p>
                  </div>
                  <CalendarDays class="w-8 h-8 opacity-20" />
             </div>

             <form @submit.prevent="saveEvent" class="space-y-8 p-8 border border-white/5 bg-black/40 backdrop-blur-md rounded-sm shadow-2xl">
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                       <!-- Left Column -->
                       <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest opacity-80 flex gap-2 items-center"><Flag class="w-3 h-3 text-safari-gold" /> OPERATION CODENAME</label>
                                <input v-model="form.name" type="text" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm placeholder-white/20 uppercase tracking-wider" placeholder="e.g. Mara Migration" required />
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest opacity-80">REAL-WORLD OCCASION</label>
                                <input v-model="form.occasion" type="text" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm placeholder-white/20 tracking-wider" placeholder="e.g. Great Migration Crossing" />
                                <div v-if="form.errors.occasion" class="text-red-500 text-xs mt-1">{{ form.errors.occasion }}</div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                     <label class="text-[10px] font-black uppercase tracking-widest opacity-80 flex items-center gap-2"><CalendarDays class="w-3 h-3 text-safari-gold" /> MONTH</label>
                                     <select v-model="form.month" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm uppercase tracking-wider [&>option]:bg-zinc-900" required>
                                          <option v-for="(m, i) in monthNames" :key="i" :value="i">{{ m }}</option>
                                     </select>
                                </div>
                                <div class="space-y-2">
                                     <label class="text-[10px] font-black uppercase tracking-widest opacity-80">ACTIVE DAYS</label>
                                     <input v-model="form.days" type="text" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm placeholder-white/20 tracking-wider" placeholder="e.g. 12, 13, 14" required />
                                </div>
                            </div>
                            <div v-if="form.errors.days || form.errors.month" class="text-red-500 text-xs mt-1">{{ form.errors.days || form.errors.month }}</div>
                       </div>

                       <!-- Right Column -->
                       <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                     <label class="text-[10px] font-black uppercase tracking-widest opacity-80 flex items-center gap-2"><MapPin class="w-3 h-3 text-safari-gold" /> LOCATION</label>
                                     <input v-model="form.location" type="text" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm placeholder-white/20 tracking-wider" placeholder="Park/Reserve" required />
                                </div>
                                <div class="space-y-2">
                                     <label class="text-[10px] font-black uppercase tracking-widest opacity-80">REGION</label>
                                     <input v-model="form.region" type="text" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm placeholder-white/20 tracking-wider" placeholder="Province/County" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                     <label class="text-[10px] font-black uppercase tracking-widest opacity-80 flex items-center gap-2"><Tag class="w-3 h-3 text-safari-gold" /> INTEL LABEL</label>
                                     <input v-model="form.label" type="text" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm placeholder-white/20 tracking-wider uppercase" placeholder="Peak, Event..." required />
                                </div>
                                <div class="space-y-2">
                                     <label class="text-[10px] font-black uppercase tracking-widest opacity-80">ASSET CLASS</label>
                                     <input v-model="form.asset_class" type="text" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm placeholder-white/20 tracking-wider uppercase" placeholder="e.g. TACTICAL SURVEY" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest opacity-80">OPERATIONAL HOURS</label>
                                <input v-model="form.time" type="text" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm placeholder-white/20 tracking-wider" placeholder="06:00 - 18:30 HRS" />
                            </div>
                       </div>
                  </div>

                  <!-- Full Title Rows -->
                  <div class="space-y-6 pt-6 border-t border-white/10">
                       <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest opacity-80">SATELLITE INTEL (DESCRIPTION)</label>
                            <textarea v-model="form.description" rows="3" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm placeholder-white/20 tracking-wider resize-none"></textarea>
                       </div>

                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                 <label class="text-[10px] font-black uppercase tracking-widest opacity-80">HERO IMAGE URL</label>
                                 <input v-model="form.image" type="url" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm placeholder-white/20 tracking-wider" placeholder="https://" />
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-3">
                                     <label class="text-[10px] font-black uppercase tracking-widest opacity-80">RADAR COLOR</label>
                                     <div class="flex items-center gap-3">
                                         <button type="button" v-for="c in colorOptions" :key="c" @click="form.color = c" :class="`w-8 h-8 rounded-full border-2 transition-all ${c} ${form.color === c ? 'border-white scale-110 shadow-lg' : 'border-transparent opacity-50 hover:opacity-100'}`"></button>
                                     </div>
                                </div>
                                <div class="space-y-2">
                                     <label class="text-[10px] font-black uppercase tracking-widest opacity-80">STATUS</label>
                                     <select v-model="form.status" class="w-full bg-white/5 border border-white/10 px-4 py-3 text-sm focus:border-safari-gold focus:ring-1 focus:ring-safari-gold transition-all rounded-sm uppercase tracking-wider [&>option]:bg-zinc-900" required>
                                          <option value="published">DEPLOYED</option>
                                          <option value="draft">DRAFT MODE</option>
                                     </select>
                                </div>
                            </div>
                       </div>
                  </div>

                  <div class="flex items-center justify-end gap-4 pt-8 border-t border-white/10">
                       <Link :href="route('admin.strategic-events.index')" class="px-6 py-3 border border-white/20 text-xs font-black uppercase tracking-widest hover:bg-white/10 transition-all flex items-center gap-2">
                            <Ban class="w-3.5 h-3.5" /> Abort
                       </Link>
                       <button type="submit" :disabled="form.processing" class="px-8 py-3 bg-safari-gold text-black text-xs font-black uppercase tracking-widest hover:bg-white transition-all shadow-[0_0_15px_rgba(212,175,55,0.3)] disabled:opacity-50 flex items-center gap-3">
                            <Save class="w-4 h-4" /> {{ form.processing ? 'DECRYPTING...' : 'INITIATE OPERATION' }}
                       </button>
                  </div>
             </form>
        </div>
    </AppLayout>
</template>
