<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus, Save, ArrowLeft, Camera, Trash2, ChevronRight, MapPin, DollarSign, Clock } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Inventory', href: '/admin/tours' },
    { title: 'Create Tour', href: '/admin/tours/create' },
];

const props = defineProps<{
    destinations: any[];
}>();

const form = useForm({
    destination_id: '',
    name: '',
    slug: '',
    price: '',
    duration: '',
    images: [''],
    itinerary: [
        { day: 1, title: '', description: '' }
    ],
    whats_included: [''],
    whats_excluded: ['']
});

// Auto-generate slug from name
watch(() => form.name, (newName) => {
    form.slug = newName.toLowerCase().replace(/[^\w\s-]/g, '').replace(/[\s_]+/g, '-').replace(/^-+|-+$/g, '');
});

const addImage = () => form.images.push('');
const removeImage = (index: number) => form.images.splice(index, 1);

const addItineraryDay = () => form.itinerary.push({ day: form.itinerary.length + 1, title: '', description: '' });
const removeItineraryDay = (index: number) => {
    form.itinerary.splice(index, 1);
    form.itinerary.forEach((day, i) => day.day = i + 1);
};

const addItem = (field: 'whats_included' | 'whats_excluded') => form[field].push('');
const removeItem = (field: 'whats_included' | 'whats_excluded', index: number) => form[field].splice(index, 1);

const submit = () => {
    form.post(route('admin.tours.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create New Tour" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-12 max-w-7xl mx-auto pb-44">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div class="flex items-center gap-6">
                      <Link :href="route('admin.tours.index')" class="p-4 border border-white/5 hover:border-white/20 transition-all rounded-sm bg-white/[0.02]">
                           <ArrowLeft class="w-4 h-4 opacity-50" />
                      </Link>
                      <div>
                           <h1 class="text-3xl font-black uppercase tracking-tighter mb-2">Build Expedition</h1>
                           <p class="text-xs opacity-50 uppercase tracking-widest">Create a new legendary Kenyan adventure</p>
                      </div>
                 </div>
                 <button @click="submit" :disabled="form.processing" class="px-10 py-4 bg-safari-gold text-black text-[10px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-sm flex items-center gap-4 group">
                      <Save class="w-4 h-4 transition-transform group-hover:scale-110" />
                      {{ form.processing ? 'Crafting...' : 'Save Expedition' }}
                 </button>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                 <!-- Main Settings -->
                 <div class="lg:col-span-2 space-y-12">
                      <div class="bg-card border border-white/5 p-10 rounded-sm space-y-8">
                           <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold mb-10">Core Identity</h3>
                           <div class="space-y-8">
                                <div class="grid grid-cols-1 gap-2">
                                     <label class="text-[10px] font-bold uppercase tracking-widest opacity-40">Expedition Name</label>
                                     <input v-model="form.name" type="text" class="bg-white/5 border border-white/10 rounded-sm px-6 py-4 outline-none focus:border-safari-gold transition-colors text-xs font-bold uppercase tracking-widest" placeholder="e.g., Ultimate Maasai Mara" />
                                     <p v-if="form.errors.name" class="text-xs text-red-500 mt-2">{{ form.errors.name }}</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                     <div class="grid gap-2">
                                          <label class="text-[10px] font-bold uppercase tracking-widest opacity-40">Access Slug</label>
                                          <input v-model="form.slug" type="text" class="bg-white/5 border border-white/10 rounded-sm px-6 py-4 outline-none focus:border-safari-gold transition-colors text-xs opacity-60" placeholder="ultimate-maasai-mara" />
                                          <p v-if="form.errors.slug" class="text-xs text-red-500 mt-2">{{ form.errors.slug }}</p>
                                     </div>
                                     <div class="grid gap-2">
                                          <label class="text-[10px] font-bold uppercase tracking-widest opacity-40">Destination Hub</label>
                                          <select v-model="form.destination_id" class="bg-white/5 border border-white/10 rounded-sm px-6 py-4 outline-none focus:border-safari-gold transition-colors text-xs font-bold uppercase tracking-widest appearance-none">
                                               <option value="" class="bg-neutral-900">Select Region</option>
                                               <option v-for="dest in destinations" :key="dest.id" :value="dest.id" class="bg-neutral-900">{{ dest.name }}</option>
                                          </select>
                                          <p v-if="form.errors.destination_id" class="text-xs text-red-500 mt-2">{{ form.errors.destination_id }}</p>
                                     </div>
                                </div>
                           </div>
                      </div>

                      <!-- Itinerary Builder -->
                      <div class="bg-card border border-white/5 p-10 rounded-sm space-y-8">
                           <div class="flex items-center justify-between mb-10">
                                <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold">Itinerary Architecture</h3>
                                <button type="button" @click="addItineraryDay" class="text-[9px] font-black uppercase tracking-widest opacity-40 hover:opacity-100 flex items-center gap-2">
                                     + Add Day
                                </button>
                           </div>

                           <div class="space-y-12">
                                <div v-for="(day, index) in form.itinerary" :key="index" class="relative pl-12 border-l border-white/10 pb-8 last:pb-0 overflow-visible">
                                     <!-- Day Marker -->
                                     <div class="absolute -left-3 top-0 w-6 h-6 rounded-full bg-safari-gold text-black text-[9px] font-black flex items-center justify-center shadow-lg">
                                          {{ day.day }}
                                     </div>
                                     <div class="space-y-4">
                                          <div class="flex items-center gap-4">
                                               <input v-model="day.title" type="text" class="bg-white/5 border border-white/10 rounded-sm px-6 py-3 outline-none focus:border-safari-gold transition-colors text-xs font-bold uppercase tracking-widest flex-1" placeholder="Major Milestone Title" />
                                               <button @click="removeItineraryDay(index)" type="button" class="p-3 opacity-20 hover:opacity-100 hover:text-red-500 transition-all"><Trash2 class="w-3.5 h-3.5" /></button>
                                          </div>
                                          <textarea v-model="day.description" rows="3" class="bg-white/5 border border-white/10 rounded-sm px-6 py-4 outline-none focus:border-safari-gold transition-colors text-xs opacity-60 w-full" placeholder="Describe the day's events..."></textarea>
                                     </div>
                                </div>
                           </div>
                      </div>
                 </div>

                 <!-- Physicals & Pricing -->
                 <div class="space-y-12">
                      <div class="bg-card border border-white/5 p-8 rounded-sm space-y-8">
                           <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold mb-8">Investment & Duration</h3>
                           <div class="space-y-6">
                                <div class="grid gap-2">
                                     <label class="text-[10px] font-bold uppercase tracking-widest opacity-40">Price (USD)</label>
                                     <div class="relative">
                                          <DollarSign class="absolute left-6 top-1/2 -translate-y-1/2 w-3.5 h-3.5 opacity-40" />
                                          <input v-model="form.price" type="number" class="bg-white/5 border border-white/10 rounded-sm pl-12 pr-6 py-4 w-full outline-none focus:border-safari-gold transition-colors text-xs font-bold" placeholder="0.00" />
                                     </div>
                                     <p v-if="form.errors.price" class="text-xs text-red-500 mt-2">{{ form.errors.price }}</p>
                                </div>
                                <div class="grid gap-2">
                                     <label class="text-[10px] font-bold uppercase tracking-widest opacity-40">Timeframe</label>
                                     <div class="relative">
                                          <Clock class="absolute left-6 top-1/2 -translate-y-1/2 w-3.5 h-3.5 opacity-40" />
                                          <input v-model="form.duration" type="text" class="bg-white/5 border border-white/10 rounded-sm pl-12 pr-6 py-4 w-full outline-none focus:border-safari-gold transition-colors text-xs font-bold uppercase tracking-widest" placeholder="7 Days / 6 Nights" />
                                     </div>
                                     <p v-if="form.errors.duration" class="text-xs text-red-500 mt-2">{{ form.errors.duration }}</p>
                                </div>
                           </div>
                      </div>

                      <div class="bg-card border border-white/5 p-8 rounded-sm">
                           <div class="flex items-center justify-between mb-8">
                                <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold">Expedition Gallery</h3>
                                <button type="button" @click="addImage" class="text-[9px] font-black uppercase tracking-widest opacity-40 hover:opacity-100">+ Add URL</button>
                           </div>
                           <div class="space-y-4">
                                <div v-for="(image, index) in form.images" :key="index" class="flex items-center gap-3">
                                     <div class="w-10 h-10 rounded-sm bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden flex-shrink-0">
                                          <img v-if="image" :src="image" class="w-full h-full object-cover" />
                                          <Camera v-else class="w-3.5 h-3.5 opacity-20" />
                                     </div>
                                     <input v-model="form.images[index]" type="text" class="bg-white/5 border border-white/10 rounded-sm px-4 py-3 outline-none focus:border-safari-gold transition-colors text-[10px] flex-1" placeholder="Unsplash URL" />
                                     <button @click="removeImage(index)" type="button" class="opacity-20 hover:opacity-100 hover:text-red-500 transition-all"><Trash2 class="w-3.5 h-3.5" /></button>
                                </div>
                                <p v-if="form.errors.images" class="text-xs text-red-500 mt-2">{{ form.errors.images }}</p>
                           </div>
                      </div>

                      <div class="bg-card border border-white/5 p-8 rounded-sm space-y-10">
                           <!-- What's Included -->
                           <div>
                                <div class="flex items-center justify-between mb-6">
                                     <h3 class="text-[9px] font-bold uppercase tracking-[0.2em] opacity-40">Included</h3>
                                     <button type="button" @click="addItem('whats_included')" class="text-[9px] font-bold opacity-40 hover:opacity-100">+</button>
                                </div>
                                <div class="space-y-3">
                                     <div v-for="(item, index) in form.whats_included" :key="index" class="flex gap-2">
                                          <input v-model="form.whats_included[index]" type="text" class="bg-white/5 border border-white/10 rounded-sm px-4 py-2 outline-none focus:border-safari-gold transition-colors text-[9px] flex-1" placeholder="e.g., Luxury Tented Camp" />
                                          <button @click="removeItem('whats_included', index)" type="button" class="opacity-20 hover:opacity-100"><Trash2 class="w-3 h-3" /></button>
                                     </div>
                                </div>
                           </div>
                           <!-- What's Excluded -->
                           <div>
                                <div class="flex items-center justify-between mb-6">
                                     <h3 class="text-[9px] font-bold uppercase tracking-[0.2em] opacity-40">Excluded</h3>
                                     <button type="button" @click="addItem('whats_excluded')" class="text-[9px] font-bold opacity-40 hover:opacity-100">+</button>
                                </div>
                                <div class="space-y-3">
                                     <div v-for="(item, index) in form.whats_excluded" :key="index" class="flex gap-2">
                                          <input v-model="form.whats_excluded[index]" type="text" class="bg-white/5 border border-white/10 rounded-sm px-4 py-2 outline-none focus:border-safari-gold transition-colors text-[9px] flex-1" placeholder="e.g., Flight Tickets" />
                                          <button @click="removeItem('whats_excluded', index)" type="button" class="opacity-20 hover:opacity-100"><Trash2 class="w-3 h-3" /></button>
                                     </div>
                                </div>
                           </div>
                      </div>
                 </div>
            </form>
        </div>
    </AppLayout>
</template>
