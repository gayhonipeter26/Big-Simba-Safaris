<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Plus, Trash2, Edit2, Zap, Target, DollarSign, Clock, Layers, Image as ImageIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Strategic Services', href: '/admin/services' },
];

defineProps<{
    services: any[];
}>();

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const categories = [
    { value: 'game_drive', label: 'Wild Recon (Game Drive)' },
    { value: 'hostel', label: 'Tactical Base (Hostel)' },
    { value: 'transfer', label: 'Logistics Relay (Transfer)' },
    { value: 'other', label: 'Extended Mission' },
];

const form = useForm({
    name: '',
    category: 'game_drive',
    description: '',
    image: '',
    pricing_tier: '',
    starting_price: '' as string | number,
    features: '',
});

const openCreateModal = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    showModal.value = true;
};

const openEditModal = (item: any) => {
    isEditing.value = true;
    editingId.value = item.id;
    form.name = item.name;
    form.category = item.category;
    form.description = item.description || '';
    form.image = item.image || '';
    form.pricing_tier = item.pricing_tier || '';
    form.starting_price = item.starting_price || '';
    form.features = item.features || '';
    showModal.value = true;
};

const submit = () => {
    if (isEditing.value && editingId.value) {
        form.put(route('admin.services.update', { service: editingId.value }), {
            onSuccess: () => {
                form.reset();
                showModal.value = false;
            },
        });
    } else {
        form.post(route('admin.services.store'), {
            onSuccess: () => {
                form.reset();
                showModal.value = false;
            },
        });
    }
};

const deleteItem = (id: number) => {
    if (confirm('Decommission this service unit?')) {
        router.delete(route('admin.services.destroy', { service: id }), {
            preserveScroll: true,
        });
    }
};

const getCategoryLabel = (val: string) => {
    return categories.find(c => c.value === val)?.label || val;
};
</script>

<template>
    <Head title="Strategic Services Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="font-display text-3xl font-black uppercase tracking-tighter mb-2 text-safari-gold">Strategic Services Registry</h1>
                      <p class="text-xs opacity-90 uppercase tracking-widest italic">Manage extended mission support units</p>
                 </div>
                 <button @click="openCreateModal" class="px-8 py-4 bg-safari-gold text-black text-sm font-black uppercase tracking-widest hover:bg-white transition-all rounded-sm flex items-center gap-2 shadow-[0_0_30px_rgba(202,138,4,0.3)]">
                      <Plus class="w-3 h-3" /> Activate New Service
                 </button>
            </div>

            <!-- Services Grid -->
            <div v-if="services.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10">
                 <div v-for="service in services" :key="service.id" class="group bg-card border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-700 flex flex-col shadow-2xl">
                      <div class="h-56 relative bg-white/5">
                           <img v-if="service.image" :src="service.image" class="absolute inset-0 h-full w-full object-cover transition-transform duration-1000 group-hover:scale-105 opacity-90" />
                           <div v-else class="absolute inset-0 flex items-center justify-center">
                                <Layers class="w-12 h-12 opacity-10" />
                           </div>
                           <div class="absolute top-6 left-6">
                                <span class="px-4 py-1.5 bg-black/60 backdrop-blur-md border border-white/10 text-xs font-black uppercase tracking-widest text-safari-gold rounded-full shadow-2xl">
                                     {{ getCategoryLabel(service.category) }}
                                </span>
                           </div>
                      </div>

                      <div class="p-8 space-y-6 flex flex-col justify-between flex-1">
                           <div class="space-y-4">
                                <h3 class="font-display text-2xl font-black uppercase tracking-tight group-hover:text-safari-gold transition-colors">{{ service.name }}</h3>
                                <p class="text-sm font-normal text-gray-300 leading-relaxed italic line-clamp-3">{{ service.description }}</p>
                                
                                <div class="pt-6 border-t border-white/5 grid grid-cols-2 gap-4">
                                     <div class="flex items-center gap-3">
                                          <Target class="w-3.5 h-3.5 text-safari-gold opacity-80" />
                                          <span class="text-xs font-black uppercase tracking-widest opacity-80 italic">{{ service.pricing_tier }}</span>
                                     </div>
                                     <div class="flex items-center gap-3">
                                          <DollarSign class="w-3.5 h-3.5 text-safari-gold opacity-80" />
                                          <span class="text-xs font-black uppercase tracking-widest text-safari-gold">Ksh {{ parseFloat(service.starting_price).toLocaleString() }}</span>
                                     </div>
                                </div>
                           </div>

                           <div class="flex items-center gap-4 pt-6 border-t border-white/5">
                                <button @click="openEditModal(service)" class="flex-1 py-4 border border-white/10 text-xs font-black uppercase tracking-widest hover:border-safari-gold hover:text-safari-gold transition-all flex items-center justify-center gap-3">
                                     <Edit2 class="w-3.5 h-3.5" /> Recalibrate
                                </button>
                                <button @click="deleteItem(service.id)" class="px-6 py-4 border border-white/10 text-xs font-black uppercase tracking-widest hover:border-red-500 hover:text-red-500 transition-all">
                                     <Trash2 class="w-3.5 h-3.5" />
                                </button>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-44 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                 <Layers class="w-16 h-16 opacity-5 mb-8 text-safari-gold" />
                 <h3 class="font-display text-2xl font-black uppercase tracking-widest mb-4">Strategic Reserves Empty</h3>
                 <p class="text-xs opacity-80 max-w-sm mb-12 uppercase tracking-widest leading-relaxed italic">No extended service units are currently activated. Induct game drives, transfers, or tactical bases.</p>
                 <button @click="openCreateModal" class="px-12 py-5 border border-white/20 text-sm font-black uppercase tracking-[0.4em] hover:bg-white hover:text-black transition-all">
                      Induct Initial Service
                 </button>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/95 backdrop-blur-xl animate-in fade-in duration-500">
             <div class="w-full max-w-2xl bg-card border border-white/5 rounded-sm p-10 space-y-8 shadow-[0_0_100px_rgba(0,0,0,0.8)] relative">
                  <button @click="showModal = false" class="absolute top-6 right-8 text-white/20 hover:text-white transition-colors">
                       <Plus class="w-8 h-8 rotate-45" />
                  </button>

                  <div class="text-center space-y-1 pb-6 border-b border-white/5">
                       <h2 class="font-display text-xl font-black uppercase tracking-tighter text-safari-gold">
                            {{ isEditing ? 'Recalibrate Service Specs' : 'Service Unit Activation' }}
                       </h2>
                       <p class="text-xs opacity-80 uppercase tracking-[0.4em] italic">- Extended Safari Logistics //</p>
                  </div>

                  <form @submit.prevent="submit" class="space-y-6 overflow-y-auto max-h-[70vh] pr-2 custom-scrollbar text-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Service Name</label>
                                 <input v-model="form.name" type="text" placeholder="E.G. DAWN PATROL GAME DRIVE" class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20 text-white" required />
                            </div>
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Tactical Category</label>
                                 <select v-model="form.category" class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all appearance-none cursor-pointer text-white">
                                      <option v-for="cat in categories" :key="cat.value" :value="cat.value" class="bg-black text-white uppercase tracking-widest py-4">{{ cat.label }}</option>
                                 </select>
                            </div>
                       </div>

                       <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Mission Description</label>
                            <textarea v-model="form.description" rows="3" placeholder="DESCRIBE THE SERVICE PARAMETERS..." class="w-full bg-white/5 border border-white/10 p-6 text-sm font-light uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all placeholder:opacity-20 leading-relaxed text-white"></textarea>
                       </div>

                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Pricing Tier (E.G. PER PERSON)</label>
                                 <input v-model="form.pricing_tier" type="text" placeholder="E.G. PER MISSION" class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20 text-white" />
                            </div>
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Starting Rate (KES)</label>
                                 <input v-model="form.starting_price" type="number" step="0.01" placeholder="0.00" class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20 text-white" />
                            </div>
                       </div>

                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Visual Resource URL</label>
                                 <input v-model="form.image" type="text" placeholder="HTTPS://..." class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20 text-white" />
                            </div>
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Unit Features (COMMA SEP)</label>
                                 <input v-model="form.features" type="text" placeholder="WIFI, MEALS, GEAR INCLUDED..." class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20 text-white" />
                            </div>
                       </div>

                       <div class="pt-6 flex flex-col items-center gap-4">
                            <button type="submit" :disabled="form.processing" class="w-full py-5 bg-safari-gold text-black text-sm font-black uppercase tracking-[0.5em] hover:bg-white transition-all shadow-[0_0_50px_rgba(202,138,4,0.1)]">
                                 {{ isEditing ? 'Sync Service Parameters' : 'Activate Service Unit' }}
                            </button>
                            <button @click="showModal = false" type="button" class="text-xs font-black uppercase tracking-[0.4em] opacity-20 hover:opacity-100 transition-opacity">Abort Mission</button>
                       </div>
                  </form>
             </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.animate-in {
    animation-duration: 0.5s;
    animation-fill-mode: both;
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

select option {
    background: #000;
    color: #fff;
}
</style>
