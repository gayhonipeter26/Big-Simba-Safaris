<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Save, ArrowLeft, Globe } from 'lucide-vue-next';

const props = defineProps<{
    destination: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Destinations', href: '/admin/destinations' },
    { title: 'Edit Region', href: `/admin/destinations/${props.destination.id}/edit` },
];

const form = useForm({
    name: props.destination.name,
    slug: props.destination.slug,
    overview: props.destination.overview,
    image: props.destination.image
});

const submit = () => {
    form.patch(route('admin.destinations.update', props.destination.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Edit ${destination.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-12 max-w-3xl mx-auto pb-44">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div class="flex items-center gap-6">
                      <Link :href="route('admin.destinations.index')" class="p-4 border border-white/5 hover:border-white/20 transition-all rounded-sm bg-white/[0.02]">
                           <ArrowLeft class="w-4 h-4 opacity-50" />
                      </Link>
                      <div>
                           <h1 class="text-3xl font-black uppercase tracking-tighter mb-2">Edit Region</h1>
                           <p class="text-xs opacity-50 uppercase tracking-widest">Update the {{ destination.name }} legend</p>
                      </div>
                 </div>
                 <button @click="submit" :disabled="form.processing" class="px-10 py-4 bg-safari-gold text-black text-[10px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-sm flex items-center gap-4 group">
                      <Save class="w-4 h-4" />
                      {{ form.processing ? 'Syncing...' : 'Update Region' }}
                 </button>
            </div>

            <form @submit.prevent="submit" class="bg-card border border-white/5 p-10 rounded-sm space-y-10">
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                      <div class="space-y-2">
                           <label class="text-[10px] font-bold uppercase tracking-widest opacity-40">Region Name</label>
                           <input v-model="form.name" type="text" class="bg-white/5 border border-white/10 rounded-sm px-6 py-4 outline-none focus:border-safari-gold transition-colors text-xs font-bold uppercase tracking-widest w-full" placeholder="e.g., Samburu Reserve" />
                           <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                      </div>
                      <div class="space-y-2">
                           <label class="text-[10px] font-bold uppercase tracking-widest opacity-40">Map Slug</label>
                           <input v-model="form.slug" type="text" class="bg-white/5 border border-white/10 rounded-sm px-6 py-4 outline-none focus:border-safari-gold transition-colors text-xs opacity-60 w-full" placeholder="samburu-reserve" />
                           <p v-if="form.errors.slug" class="text-xs text-red-500">{{ form.errors.slug }}</p>
                      </div>
                 </div>

                 <div class="space-y-2">
                      <label class="text-[10px] font-bold uppercase tracking-widest opacity-40">Region Lore (Description)</label>
                      <textarea v-model="form.overview" rows="5" class="bg-white/5 border border-white/10 rounded-sm px-6 py-4 outline-none focus:border-safari-gold transition-colors text-xs opacity-60 w-full" placeholder="The red sands of Samburu hold secrets..."></textarea>
                      <p v-if="form.errors.overview" class="text-xs text-red-500">{{ form.errors.overview }}</p>
                 </div>

                 <div class="space-y-2">
                      <label class="text-[10px] font-bold uppercase tracking-widest opacity-40">Cover Image URL</label>
                      <div class="flex items-center gap-4">
                           <div class="w-16 h-16 rounded-sm bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden flex-shrink-0">
                                <img v-if="form.image" :src="form.image" class="w-full h-full object-cover" />
                                <Globe v-else class="w-4 h-4 opacity-20" />
                           </div>
                           <input v-model="form.image" type="text" class="bg-white/5 border border-white/10 rounded-sm px-6 py-4 outline-none focus:border-safari-gold transition-colors text-[10px] flex-1" placeholder="Unsplash Photo URL" />
                      </div>
                      <p v-if="form.errors.image" class="text-xs text-red-500">{{ form.errors.image }}</p>
                 </div>
            </form>
        </div>
    </AppLayout>
</template>
