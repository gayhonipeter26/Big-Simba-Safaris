<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Plus, Trash2, Camera, Video, ExternalLink, Image as ImageIcon, Star, Edit2 } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Wildlife Gallery', href: '/admin/gallery' },
];

const props = defineProps<{
    items: any[];
}>();

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    title: '',
    type: 'image',
    url: '',
    description: '',
    is_featured: false,
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
    form.title = item.title;
    form.type = item.type;
    form.url = item.url;
    form.description = item.description;
    form.is_featured = !!item.is_featured;
    showModal.value = true;
};

const submit = () => {
    if (isEditing.value && editingId.value) {
        form.put(route('admin.gallery.update', { gallery: editingId.value }), {
            onSuccess: () => {
                form.reset();
                showModal.value = false;
            },
        });
    } else {
        form.post(route('admin.gallery.store'), {
            onSuccess: () => {
                form.reset();
                showModal.value = false;
            },
        });
    }
};

const deleteItem = (id: number) => {
    if (confirm('Permanently remove this asset from the gallery?')) {
        router.delete(route('admin.gallery.destroy', { gallery: id }), {
            preserveScroll: true,
        });
    }
};

const toggleFeatured = (item: any) => {
    router.put(route('admin.gallery.update', { gallery: item.id }), {
        title: item.title,
        type: item.type,
        url: item.url,
        description: item.description,
        is_featured: !item.is_featured,
    }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Manage Wildlife Gallery" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="font-display text-3xl font-black uppercase tracking-tighter mb-2 text-safari-gold">Wildlife Gallery Register</h1>
                      <p class="text-xs opacity-90 uppercase tracking-widest italic">Curate the visual narrative of the wild</p>
                 </div>
                 <button @click="openCreateModal" class="px-8 py-4 bg-safari-gold text-black text-sm font-black uppercase tracking-widest hover:bg-white transition-all rounded-sm flex items-center gap-2 shadow-[0_0_30px_rgba(202,138,4,0.3)]">
                      <Plus class="w-3 h-3" /> Register New Asset
                 </button>
            </div>

            <!-- Gallery Grid - Enlarged Cards -->
            <div v-if="items.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-12">
                 <div v-for="item in items" :key="item.id" class="group relative aspect-video bg-card border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/30 transition-all duration-700 flex flex-col shadow-2xl">
                      <div class="absolute inset-0">
                           <img v-if="item.type === 'image'" :src="item.url" class="absolute inset-0 h-full w-full object-cover transition-transform duration-1000 group-hover:scale-105 grayscale-[0.3] group-hover:grayscale-0" />
                           <div v-else class="absolute inset-0 bg-black/80 flex items-center justify-center">
                                <Video class="w-16 h-16 text-safari-gold opacity-80 group-hover:opacity-100 transition-opacity" />
                           </div>
                           <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                      </div>

                      <!-- Control Overlays -->
                      <div class="absolute top-6 right-6 flex items-center gap-3 z-20">
                           <button @click="toggleFeatured(item)" class="p-3 bg-black/80 backdrop-blur-md rounded-full border border-white/10 hover:border-safari-gold/50 transition-all shadow-xl">
                                <Star :class="['w-4 h-4', item.is_featured ? 'text-safari-gold fill-safari-gold' : 'text-white/40']" />
                           </button>
                      </div>

                      <div class="absolute bottom-0 left-0 right-0 p-10 z-20 space-y-6 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                           <div>
                                <div class="flex items-center gap-3 text-xs font-black uppercase tracking-[0.4em] text-safari-gold mb-3">
                                     <ImageIcon v-if="item.type === 'image'" class="w-3.5 h-3.5" />
                                     <Video v-else class="w-3.5 h-3.5" />
                                     {{ item.type }}
                                </div>
                                <h3 class="font-display text-2xl font-black uppercase tracking-tight leading-tight mb-2 group-hover:text-safari-gold transition-colors">{{ item.title }}</h3>
                                <p class="text-xs opacity-90 line-clamp-1 italic max-w-md">{{ item.description }}</p>
                           </div>

                           <div class="flex items-center gap-4 pt-6 border-t border-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                                <button @click="openEditModal(item)" class="flex-1 py-3 bg-white text-black text-xs font-black uppercase tracking-[0.3em] hover:bg-safari-gold transition-all flex items-center justify-center gap-2">
                                     <Edit2 class="w-3 h-3" /> Edit
                                </button>
                                <button @click="deleteItem(item.id)" class="px-6 py-3 border border-red-500/30 text-red-500 text-xs font-black uppercase tracking-[0.3em] hover:bg-red-500 hover:text-white transition-all">
                                     <Trash2 class="w-3.5 h-3.5" />
                                </button>
                                <a :href="item.url" target="_blank" class="px-6 py-3 bg-white/5 border border-white/10 text-white text-xs font-black uppercase tracking-[0.3em] hover:bg-white/10 transition-all flex items-center gap-2">
                                     Final <ExternalLink class="w-3 h-3" />
                                </a>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-44 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                 <Camera class="w-16 h-16 opacity-5 mb-8" />
                 <h3 class="font-display text-2xl font-black uppercase tracking-widest mb-4">Gallery is Vacant</h3>
                 <p class="text-xs opacity-80 max-w-sm mb-12 uppercase tracking-widest leading-relaxed italic">The pride's visual register is currently empty. Start by adding images or video links.</p>
                 <button @click="openCreateModal" class="px-12 py-5 border border-white/20 text-sm font-black uppercase tracking-[0.4em] hover:bg-white hover:text-black transition-all">
                      Register Initial Asset
                 </button>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/95 backdrop-blur-xl animate-in fade-in duration-500">
             <div class="w-full max-w-2xl bg-card border border-white/5 rounded-sm p-12 space-y-12 shadow-[0_0_100px_rgba(0,0,0,0.8)] relative">
                  <button @click="showModal = false" class="absolute top-8 right-8 text-white/20 hover:text-white transition-colors">
                       <Plus class="w-8 h-8 rotate-45" />
                  </button>

                  <div class="text-center space-y-2 pb-8 border-b border-white/5">
                       <h2 class="font-display text-2xl font-black uppercase tracking-tighter text-safari-gold">
                            {{ isEditing ? 'Refine Gallery Asset' : 'New Asset Registration' }}
                       </h2>
                       <p class="text-sm opacity-80 uppercase tracking-[0.4em] italic">- Curating the Visual Experience //</p>
                  </div>

                  <form @submit.prevent="submit" class="space-y-10">
                       <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-4">
                                 <label class="text-sm font-black uppercase tracking-[0.3em] opacity-80">Asset Title</label>
                                 <input v-model="form.title" type="text" placeholder="E.G. LION KING OF THE MARA" class="w-full bg-white/5 border border-white/10 px-8 py-4 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" required />
                            </div>
                            <div class="space-y-4">
                                 <label class="text-sm font-black uppercase tracking-[0.3em] opacity-80">Asset Type</label>
                                 <select v-model="form.type" class="w-full bg-white/5 border border-white/10 px-8 py-4 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all cursor-pointer">
                                      <option value="image">Still Image</option>
                                      <option value="video">Motion Video</option>
                                 </select>
                            </div>
                       </div>

                       <div class="space-y-4">
                            <label class="text-sm font-black uppercase tracking-[0.3em] opacity-80">Resource URL (Direct Link)</label>
                            <input v-model="form.url" type="url" placeholder="HTTPS://IMAGES.UNSPLASH.COM/..." class="w-full bg-white/5 border border-white/10 px-8 py-4 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" required />
                       </div>

                       <div class="space-y-4">
                            <label class="text-sm font-black uppercase tracking-[0.3em] opacity-80">Description</label>
                            <textarea v-model="form.description" rows="3" placeholder="PROVIDE CONTEXT FOR THIS VISUAL NARRATIVE..." class="w-full bg-white/5 border border-white/10 px-8 py-4 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20"></textarea>
                       </div>

                       <div class="flex items-center gap-4">
                            <input v-model="form.is_featured" type="checkbox" id="featured" class="w-6 h-6 border border-white/10 bg-white/5 text-safari-gold focus:ring-0 cursor-pointer" />
                            <label for="featured" class="text-sm font-black uppercase tracking-[0.3em] cursor-pointer">Feature on Homepage</label>
                       </div>

                       <div class="pt-8 flex flex-col items-center gap-6">
                            <button type="submit" :disabled="form.processing" class="w-full py-6 bg-safari-gold text-black text-base font-black uppercase tracking-[0.5em] hover:bg-white transition-all shadow-[0_0_50px_rgba(202,138,4,0.2)]">
                                 {{ isEditing ? 'Sync Changes' : 'Finalize Registration' }}
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
    background-color: #050505;
    color: white;
}
</style>
