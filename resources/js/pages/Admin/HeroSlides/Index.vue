<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Image, Plus, Trash2, Eye, EyeOff, GripVertical, ExternalLink } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Hero Slides', href: '/admin/hero-slides' },
];

const props = defineProps<{
    slides: {
        id: number;
        title: string;
        image_url: string;
        is_active: boolean;
        sort_order: number;
    }[];
}>();

// --- Add form ---
const addForm = useForm({
    title: '',
    image_url: '',
    is_active: true as boolean,
    sort_order: props.slides.length,
});

const showAddForm = ref(false);

const addSlide = () => {
    addForm.post(route('admin.hero-slides.store'), {
        preserveScroll: true,
        onSuccess: () => {
            addForm.reset();
            showAddForm.value = false;
        },
    });
};

// --- Edit form ---
const editingId = ref<number | null>(null);
const editForm = useForm({
    title: '',
    image_url: '',
    is_active: true as boolean,
    sort_order: 0,
});

const startEdit = (slide: typeof props.slides[0]) => {
    editingId.value = slide.id;
    editForm.title = slide.title;
    editForm.image_url = slide.image_url;
    editForm.is_active = slide.is_active;
    editForm.sort_order = slide.sort_order;
};

const cancelEdit = () => {
    editingId.value = null;
    editForm.reset();
};

const saveEdit = (id: number) => {
    editForm.put(route('admin.hero-slides.update', { hero_slide: id }), {
        preserveScroll: true,
        onSuccess: () => {
            editingId.value = null;
        },
    });
};

// --- Destroy ---
const deleteSlide = (id: number) => {
    if (!confirm('Remove this hero slide?')) return;
    router.delete(route('admin.hero-slides.destroy', { hero_slide: id }), { preserveScroll: true });
};

// --- Toggle active inline ---
const toggleActive = (slide: typeof props.slides[0]) => {
    router.put(route('admin.hero-slides.update', { hero_slide: slide.id }), {
        title: slide.title,
        image_url: slide.image_url,
        is_active: !slide.is_active,
        sort_order: slide.sort_order,
    }, { preserveScroll: true });
};
</script>

<template>
    <Head title="Hero Slides" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1400px] mx-auto">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-sidebar-border/30 pb-10">
                <div>
                    <p class="text-sm font-black uppercase tracking-[0.4em] text-safari-gold mb-3">Welcome Page</p>
                    <h1 class="font-display text-4xl font-black uppercase tracking-tighter">Hero <span class="text-safari-gold/30">Slides</span></h1>
                    <p class="text-xs opacity-80 mt-2 uppercase tracking-widest">Manage the rotating background images on the home page hero section</p>
                </div>
                <button
                    @click="showAddForm = !showAddForm"
                    class="flex items-center gap-3 px-6 py-3 bg-safari-gold text-black text-xs font-black uppercase tracking-widest hover:bg-white transition-all rounded-sm"
                >
                    <Plus class="w-4 h-4" />
                    Add Slide
                </button>
            </div>

            <!-- Add Form -->
            <Transition enter-active-class="transition-all duration-300" enter-from-class="opacity-0 -translate-y-4" leave-active-class="transition-all duration-200" leave-to-class="opacity-0 -translate-y-4">
                <form v-if="showAddForm" @submit.prevent="addSlide" class="bg-safari-gold/5 border border-safari-gold/20 rounded-sm p-8 space-y-6">
                    <p class="text-sm font-black uppercase tracking-[0.4em] text-safari-gold">New Slide</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-black uppercase tracking-widest opacity-90">Slide Title</label>
                            <input v-model="addForm.title" type="text" placeholder="e.g. Maasai Mara" class="w-full bg-white/5 border border-white/10 rounded-sm px-4 py-3 text-sm outline-none focus:border-safari-gold transition-all" />
                            <p v-if="addForm.errors.title" class="text-red-400 text-sm">{{ addForm.errors.title }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black uppercase tracking-widest opacity-90">Image URL</label>
                            <input v-model="addForm.image_url" type="url" placeholder="https://..." class="w-full bg-white/5 border border-white/10 rounded-sm px-4 py-3 text-sm outline-none focus:border-safari-gold transition-all" />
                            <p v-if="addForm.errors.image_url" class="text-red-400 text-sm">{{ addForm.errors.image_url }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black uppercase tracking-widest opacity-90">Sort Order</label>
                            <input v-model.number="addForm.sort_order" type="number" min="0" class="w-full bg-white/5 border border-white/10 rounded-sm px-4 py-3 text-sm outline-none focus:border-safari-gold transition-all" />
                        </div>
                        <div class="flex items-center gap-4 pt-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input v-model="addForm.is_active" type="checkbox" class="accent-safari-gold w-4 h-4" />
                                <span class="text-sm font-black uppercase tracking-widest opacity-90">Active</span>
                            </label>
                        </div>
                    </div>
                    <!-- Preview -->
                    <div v-if="addForm.image_url" class="aspect-[21/7] rounded-sm overflow-hidden border border-white/10 relative">
                        <img :src="addForm.image_url" class="w-full h-full object-cover brightness-50" alt="Preview" />
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-sm font-black uppercase tracking-widest opacity-90">Preview</span>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <button type="submit" :disabled="addForm.processing" class="px-8 py-3 bg-safari-gold text-black text-sm font-black uppercase tracking-widest hover:bg-white transition-all rounded-sm disabled:opacity-90">
                            {{ addForm.processing ? 'Saving...' : 'Save Slide' }}
                        </button>
                        <button type="button" @click="showAddForm = false" class="px-8 py-3 border border-white/10 text-sm font-black uppercase tracking-widest hover:border-white/30 transition-all rounded-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </Transition>

            <!-- Slides List -->
            <div v-if="slides.length > 0" class="space-y-4">
                <div
                    v-for="slide in slides"
                    :key="slide.id"
                    class="border border-white/5 rounded-sm overflow-hidden hover:border-white/10 transition-all group"
                >
                    <!-- View row -->
                    <template v-if="editingId !== slide.id">
                        <div class="flex items-center gap-6 p-4">
                            <!-- Thumbnail -->
                            <div class="w-32 h-20 rounded-sm overflow-hidden flex-shrink-0 border border-white/10 relative">
                                <img :src="slide.image_url" :alt="slide.title" class="w-full h-full object-cover" />
                                <div v-if="!slide.is_active" class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                    <EyeOff class="w-4 h-4 text-white/40" />
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0 space-y-1">
                                <p class="text-sm font-black uppercase tracking-tight truncate">{{ slide.title }}</p>
                                <a :href="slide.image_url" target="_blank" class="flex items-center gap-1 text-sm opacity-70 hover:opacity-90 hover:text-safari-gold transition-all truncate group/link max-w-sm">
                                    <ExternalLink class="w-3 h-3 flex-shrink-0" />
                                    <span class="truncate">{{ slide.image_url }}</span>
                                </a>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-black uppercase tracking-widest opacity-70">Order: {{ slide.sort_order }}</span>
                                    <span :class="['text-xs font-black uppercase tracking-widest', slide.is_active ? 'text-green-500' : 'text-red-400/60']">
                                        {{ slide.is_active ? '● Active' : '○ Hidden' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="toggleActive(slide)" :title="slide.is_active ? 'Deactivate' : 'Activate'" class="p-2 border border-white/10 hover:border-safari-gold/40 rounded-sm transition-all">
                                    <Eye v-if="!slide.is_active" class="w-4 h-4 text-safari-gold" />
                                    <EyeOff v-else class="w-4 h-4 opacity-80" />
                                </button>
                                <button @click="startEdit(slide)" class="px-4 py-2 border border-white/10 text-xs font-black uppercase tracking-widest hover:border-safari-gold/40 hover:text-safari-gold transition-all rounded-sm">
                                    Edit
                                </button>
                                <button @click="deleteSlide(slide.id)" class="p-2 border border-white/10 hover:border-red-500/40 hover:text-red-400 rounded-sm transition-all">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Edit row -->
                    <template v-else>
                        <form @submit.prevent="saveEdit(slide.id)" class="p-6 space-y-4 bg-safari-gold/5 border-t border-safari-gold/20">
                            <p class="text-sm font-black uppercase tracking-[0.4em] text-safari-gold">Editing Slide</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-sm font-black uppercase tracking-widest opacity-90">Title</label>
                                    <input v-model="editForm.title" type="text" class="w-full bg-white/5 border border-white/10 rounded-sm px-4 py-2.5 text-sm outline-none focus:border-safari-gold transition-all" />
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-black uppercase tracking-widest opacity-90">Image URL</label>
                                    <input v-model="editForm.image_url" type="url" class="w-full bg-white/5 border border-white/10 rounded-sm px-4 py-2.5 text-sm outline-none focus:border-safari-gold transition-all" />
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-black uppercase tracking-widest opacity-90">Sort Order</label>
                                    <input v-model.number="editForm.sort_order" type="number" min="0" class="w-full bg-white/5 border border-white/10 rounded-sm px-4 py-2.5 text-sm outline-none focus:border-safari-gold transition-all" />
                                </div>
                                <div class="flex items-center gap-3 pt-5">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input v-model="editForm.is_active" type="checkbox" class="accent-safari-gold w-4 h-4" />
                                        <span class="text-sm font-black uppercase tracking-widest opacity-90">Active</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Preview -->
                            <div v-if="editForm.image_url" class="aspect-[21/7] rounded-sm overflow-hidden border border-white/10 relative">
                                <img :src="editForm.image_url" class="w-full h-full object-cover brightness-50" alt="Preview" />
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-sm font-black uppercase tracking-widest opacity-90">Preview</span>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" :disabled="editForm.processing" class="px-6 py-2 bg-safari-gold text-black text-xs font-black uppercase tracking-widest hover:bg-white transition-all rounded-sm disabled:opacity-90">
                                    {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                                </button>
                                <button type="button" @click="cancelEdit" class="px-6 py-2 border border-white/10 text-xs font-black uppercase tracking-widest hover:border-white/30 transition-all rounded-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </template>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else class="py-32 text-center border border-dashed border-white/5 rounded-sm">
                <Image class="w-12 h-12 mx-auto opacity-10 mb-6" />
                <p class="text-sm font-black uppercase tracking-[0.5em] opacity-20">No hero slides configured</p>
                <p class="text-sm opacity-20 mt-2">Add your first slide above to get started</p>
            </div>

        </div>
    </AppLayout>
</template>
