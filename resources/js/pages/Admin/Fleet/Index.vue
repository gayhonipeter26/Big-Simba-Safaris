<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Plus, Trash2, Edit2, Car, Users, Gauge, Settings, ExternalLink } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Fleet Register', href: '/admin/fleet' },
];

defineProps<{
    fleet: any[];
}>();

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    name: '',
    image: '',
    capacity: '',
    drive_type: '4x4',
    features: '',
    is_available: true as boolean,
    drivers: '',
    suited_for: '',
    availability: '',
    price_per_hour: '' as string | number,
    price_per_day: '' as string | number,
    price_per_safari_drive: '' as string | number,
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
    form.image = item.image || '';
    form.capacity = item.capacity;
    form.drive_type = item.drive_type;
    form.features = item.features || '';
    form.drivers = item.drivers || '';
    form.suited_for = item.suited_for || '';
    form.availability = item.availability || '';
    form.price_per_hour = item.price_per_hour || '';
    form.price_per_day = item.price_per_day || '';
    form.price_per_safari_drive = item.price_per_safari_drive || '';
    form.is_available = !!item.is_available;
    showModal.value = true;
};

const submit = () => {
    if (isEditing.value && editingId.value) {
        form.put(route('admin.fleet.update', { fleet: editingId.value }), {
            onSuccess: () => {
                form.reset();
                showModal.value = false;
            },
        });
    } else {
        form.post(route('admin.fleet.store'), {
            onSuccess: () => {
                form.reset();
                showModal.value = false;
            },
        });
    }
};

const deleteItem = (id: number) => {
    if (confirm('Decommission this vehicle from the active fleet?')) {
        router.delete(route('admin.fleet.destroy', { fleet: id }), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Fleet Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="font-display text-3xl font-black uppercase tracking-tighter mb-2 text-safari-gold">Safari Fleet Register</h1>
                      <p class="text-xs opacity-90 uppercase tracking-widest italic">Maintain your expedition logistics</p>
                 </div>
                 <button @click="openCreateModal" class="px-8 py-4 bg-safari-gold text-black text-sm font-black uppercase tracking-widest hover:bg-white transition-all rounded-sm flex items-center gap-2 shadow-[0_0_30px_rgba(202,138,4,0.3)]">
                      <Plus class="w-3 h-3" /> Induct New Vehicle
                 </button>
            </div>

            <!-- Fleet Grid -->
            <div v-if="fleet.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10">
                 <div v-for="vehicle in fleet" :key="vehicle.id" class="group bg-card border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-700 flex flex-col shadow-2xl">
                      <div class="h-64 relative bg-white/5">
                           <img v-if="vehicle.image" :src="vehicle.image" class="absolute inset-0 h-full w-full object-cover transition-transform duration-1000 group-hover:scale-105" />
                           <div v-else class="absolute inset-0 flex items-center justify-center">
                                <Car class="w-16 h-16 opacity-10" />
                           </div>
                           <div class="absolute top-6 left-6">
                                <span :class="['px-4 py-1.5 text-xs font-black uppercase tracking-widest rounded-full border shadow-xl backdrop-blur-md', vehicle.is_available ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20']">
                                     {{ vehicle.is_available ? 'Active Pulse' : 'In Shop' }}
                                </span>
                           </div>
                      </div>

                      <div class="p-8 space-y-8">
                           <div class="flex items-center justify-between">
                                <h3 class="font-display text-2xl font-black uppercase tracking-tight group-hover:text-safari-gold transition-colors">{{ vehicle.name }}</h3>
                                <div class="px-3 py-1 bg-white/5 border border-white/10 rounded-sm text-xs font-black uppercase tracking-widest opacity-80">
                                     {{ vehicle.drive_type }}
                                </div>
                           </div>

                           <div class="grid grid-cols-2 gap-6 pb-8 border-b border-white/5">
                                <div class="flex items-center gap-4 text-white/40 group-hover:text-white/60 transition-colors">
                                     <Users class="w-4 h-4 text-safari-gold/40" />
                                     <span class="text-sm font-black uppercase tracking-widest">{{ vehicle.capacity }} Souls</span>
                                </div>
                                <div class="flex items-center gap-4 text-white/40 group-hover:text-white/60 transition-colors">
                                     <Gauge class="w-4 h-4 text-safari-gold/40" />
                                     <span class="text-sm font-black uppercase tracking-widest">AWD Logistics</span>
                                </div>
                           </div>

                           <div class="flex items-center gap-4">
                                <button @click="openEditModal(vehicle)" class="flex-1 py-4 border border-white/10 text-xs font-black uppercase tracking-widest hover:border-safari-gold hover:text-safari-gold transition-all flex items-center justify-center gap-3">
                                     <Edit2 class="w-3.5 h-3.5" /> Refine Setup
                                </button>
                                <button @click="deleteItem(vehicle.id)" class="px-6 py-4 border border-white/10 text-xs font-black uppercase tracking-widest hover:border-red-500 hover:text-red-500 transition-all">
                                     <Trash2 class="w-3.5 h-3.5" />
                                </button>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-44 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                 <Car class="w-16 h-16 opacity-5 mb-8" />
                 <h3 class="font-display text-2xl font-black uppercase tracking-widest mb-4">Garage is Silent</h3>
                 <p class="text-xs opacity-80 max-w-sm mb-12 uppercase tracking-widest leading-relaxed italic">Your expedition logistics registry is empty. Induct your 4x4 scouts and cruisers.</p>
                 <button @click="openCreateModal" class="px-12 py-5 border border-white/20 text-sm font-black uppercase tracking-[0.4em] hover:bg-white hover:text-black transition-all">
                      Induct Initial Scout
                 </button>
            </div>
        </div>

        <!-- Induct/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/95 backdrop-blur-xl animate-in fade-in duration-500">
             <div class="w-full max-w-2xl bg-card border border-white/5 rounded-sm p-10 space-y-8 shadow-[0_0_100px_rgba(0,0,0,0.8)] relative">
                  <button @click="showModal = false" class="absolute top-6 right-8 text-white/20 hover:text-white transition-colors">
                       <Plus class="w-8 h-8 rotate-45" />
                  </button>

                  <div class="text-center space-y-1 pb-6 border-b border-white/5">
                       <h2 class="font-display text-xl font-black uppercase tracking-tighter text-safari-gold">
                            {{ isEditing ? 'Refine Logistics Setup' : 'Fleet Induction' }}
                       </h2>
                       <p class="text-xs opacity-80 uppercase tracking-[0.4em] italic">- Logistics for the Safari Pride //</p>
                  </div>

                  <form @submit.prevent="submit" class="space-y-6 overflow-y-auto max-h-[70vh] pr-2 custom-scrollbar">
                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Vehicle Name</label>
                                 <input v-model="form.name" type="text" placeholder="E.G. LAND CRUISER VXR" class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" required />
                            </div>
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Drive Configuration</label>
                                 <input v-model="form.drive_type" type="text" placeholder="E.G. 4X4 OFF-ROAD" class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" required />
                            </div>
                       </div>

                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Capacity</label>
                                 <input v-model="form.capacity" type="text" placeholder="E.G. 7 SEATER" class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" required />
                            </div>
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Image Resource URL</label>
                                 <input v-model="form.image" type="text" placeholder="HTTPS://..." class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" />
                            </div>
                       </div>

                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Features (Comma Separated)</label>
                                 <input v-model="form.features" type="text" placeholder="WIFI, POP-UP ROOF, FRIDGE..." class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" />
                            </div>
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Personnel (Drivers)</label>
                                 <input v-model="form.drivers" type="text" placeholder="E.G. ABDUL, KEVIN, CHRIS..." class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" />
                            </div>
                       </div>

                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Mission Suitability</label>
                                 <input v-model="form.suited_for" type="text" placeholder="E.G. PHOTOGRAPHY..." class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" />
                            </div>
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Operational Window</label>
                                 <input v-model="form.availability" type="text" placeholder="E.G. ALL YEAR..." class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" />
                            </div>
                       </div>

                       <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Price / Hour (KES)</label>
                                 <input v-model="form.price_per_hour" type="number" step="0.01" placeholder="0.00" class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" />
                            </div>
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Price / Day (KES)</label>
                                 <input v-model="form.price_per_day" type="number" step="0.01" placeholder="0.00" class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" />
                            </div>
                            <div class="space-y-2">
                                 <label class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Safari Drive (KES)</label>
                                 <input v-model="form.price_per_safari_drive" type="number" step="0.01" placeholder="0.00" class="w-full bg-white/5 border border-white/10 px-6 py-3.5 text-sm font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" />
                            </div>
                       </div>

                       <div class="flex items-center gap-4">
                            <input v-model="form.is_available" type="checkbox" id="available" class="w-5 h-5 border border-white/10 bg-white/5 text-safari-gold focus:ring-0 cursor-pointer" />
                            <label for="available" class="text-xs font-black uppercase tracking-[0.3em] cursor-pointer">Active Pulse Status</label>
                       </div>

                       <div class="pt-4 flex flex-col items-center gap-4">
                            <button type="submit" :disabled="form.processing" class="w-full py-5 bg-safari-gold text-black text-sm font-black uppercase tracking-[0.5em] hover:bg-white transition-all shadow-[0_0_50px_rgba(202,138,4,0.1)]">
                                 {{ isEditing ? 'Sync Specifications' : 'Induct into Pride' }}
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
</style>
