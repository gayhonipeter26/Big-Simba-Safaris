<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus, Save, ArrowLeft, Camera, Newspaper } from 'lucide-vue-next';
import { watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Chronicles', href: route('admin.blog.index') },
    { title: 'New Scribe', href: '#' },
];

const form = useForm({
    title: '',
    slug: '',
    content: '',
    image: '',
    is_published: false
});

watch(() => form.title, (newVal) => {
    form.slug = newVal.toLowerCase().replace(/[^\w\s-]/g, '').replace(/[\s_]+/g, '-').replace(/^-+|-+$/g, '');
});

const submit = () => {
    form.post(route('admin.blog.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Scribe a New Story" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-12 max-w-screen-xl mx-auto pb-44">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div class="flex items-center gap-6">
                      <Link :href="route('admin.blog.index')" class="p-4 border border-white/5 hover:border-white/20 transition-all rounded-sm bg-white/[0.02]">
                           <ArrowLeft class="w-4 h-4 opacity-90" />
                      </Link>
                      <div>
                           <h1 class="font-display text-3xl font-black uppercase tracking-tighter mb-2">New Chronicle</h1>
                           <p class="text-xs opacity-90 uppercase tracking-widest">Scribe another chapter in the Simba saga</p>
                      </div>
                 </div>
                 <div class="flex items-center gap-6">
                      <div class="flex items-center gap-2 pr-6 border-r border-white/5">
                           <input type="checkbox" v-model="form.is_published" class="w-5 h-5 bg-black border border-white/10 rounded-sm text-safari-gold focus:ring-safari-gold focus:ring-offset-black" />
                           <label class="text-sm font-bold uppercase tracking-widest opacity-80">Publish Now</label>
                      </div>
                      <button @click="submit" :disabled="form.processing" class="px-10 py-4 bg-safari-gold text-black text-sm font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-sm flex items-center gap-4 group">
                           <Save class="w-4 h-4" />
                           {{ form.processing ? 'Recording...' : 'Store Story' }}
                      </button>
                 </div>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                 <div class="lg:col-span-2 space-y-10">
                      <div class="bg-card border border-white/5 p-10 rounded-sm space-y-10">
                           <div class="space-y-4">
                                <label class="text-sm font-bold uppercase tracking-widest opacity-80">Story Title</label>
                                <input v-model="form.title" type="text" class="bg-white/5 border border-white/10 rounded-sm px-6 py-4 outline-none focus:border-safari-gold transition-all text-sm font-bold uppercase tracking-widest w-full" placeholder="e.g., The Great Migration: 2026 Guide" />
                                <p v-if="form.errors.title" class="text-xs text-red-500">{{ form.errors.title }}</p>
                           </div>

                           <div class="space-y-4">
                                <label class="text-sm font-bold uppercase tracking-widest opacity-80">Permanent URL (Slug)</label>
                                <input v-model="form.slug" type="text" class="bg-white/5 border border-white/10 rounded-sm px-6 py-3 outline-none focus:border-safari-gold transition-all text-xs opacity-90 w-full" />
                                <p v-if="form.errors.slug" class="text-xs text-red-500">{{ form.errors.slug }}</p>
                           </div>

                           <div class="space-y-4">
                                <label class="text-sm font-bold uppercase tracking-widest opacity-80">Chronicle Data (Content)</label>
                                <textarea v-model="form.content" rows="15" class="bg-white/5 border border-white/10 rounded-sm px-6 py-4 outline-none focus:border-safari-gold transition-all text-sm opacity-90 leading-relaxed w-full font-light" placeholder="Once upon a savanna..."></textarea>
                                <p v-if="form.errors.content" class="text-xs text-red-500">{{ form.errors.content }}</p>
                           </div>
                      </div>
                 </div>

                 <div class="lg:col-span-1 space-y-10">
                      <div class="bg-card border border-white/5 p-8 rounded-sm space-y-6">
                           <h3 class="font-display text-sm font-black uppercase tracking-widest opacity-80 mb-6">Gallery Piece</h3>
                           <div class="aspect-square bg-white/5 border border-white/5 rounded-sm overflow-hidden flex items-center justify-center group relative">
                                <img v-if="form.image" :src="form.image" class="w-full h-full object-cover transition-opacity duration-500" />
                                <Newspaper v-else class="w-8 h-8 opacity-10" />
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-6 text-center">
                                     <p class="text-xs font-black uppercase tracking-widest">Update URL Below</p>
                                </div>
                           </div>
                           <div class="space-y-4 mt-6">
                                <label class="text-xs font-bold uppercase tracking-widest opacity-80">Cover Image URL</label>
                                <input v-model="form.image" type="text" class="bg-white/5 border border-white/10 rounded-sm px-6 py-3 outline-none focus:border-safari-gold transition-all text-sm w-full" placeholder="Unsplash/Media Link" />
                                <p v-if="form.errors.image" class="text-xs text-red-500">{{ form.errors.image }}</p>
                           </div>
                      </div>

                      <div class="p-8 bg-safari-gold/5 border border-safari-gold/20 rounded-sm">
                           <h4 class="font-display text-xs font-black uppercase tracking-widest mb-4">Scribe Tip</h4>
                           <p class="text-sm opacity-80 leading-relaxed font-light italic">"Stories with high-quality cover images receive 3x more engagement from the pride."</p>
                      </div>
                 </div>
            </form>
        </div>
    </AppLayout>
</template>
