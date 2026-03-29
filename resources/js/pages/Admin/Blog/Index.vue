<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Edit2, Trash2, Newspaper, Eye } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: route('admin.dashboard') },
    { title: 'Safari Chronicles', href: route('admin.blog.index') },
];

defineProps<{
    posts: any[];
}>();

const deletePost = (id: number) => {
    if (confirm('Are you sure you want to delete this story?')) {
        router.delete(route('admin.blog.destroy', { blog: id }), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Manage Chronicles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="font-display text-3xl font-black uppercase tracking-tighter mb-2">Safari Chronicles</h1>
                      <p class="text-sm opacity-80 uppercase tracking-widest">Manage your blog and news articles</p>
                 </div>
                 <Link :href="route('admin.blog.create')" class="px-8 py-3 bg-safari-gold text-black text-sm font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-sm flex items-center gap-2">
                      <Plus class="w-3 h-3" /> New Chronicle
                 </Link>
            </div>

            <div v-if="posts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                 <div v-for="post in posts" :key="post.id" class="group bg-card border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-700">
                      <div class="h-48 relative overflow-hidden bg-white/5">
                           <img v-if="post.image" :src="post.image" class="absolute inset-0 h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-70" />
                           <div class="absolute inset-0 bg-black/40"></div>
                           <div class="absolute top-4 left-4">
                                <span :class="['px-2 py-1 text-xs font-black uppercase tracking-widest rounded-sm border', post.is_published ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-orange-500/10 text-orange-500 border-orange-500/20']">
                                     {{ post.is_published ? 'Published' : 'Draft' }}
                                </span>
                           </div>
                      </div>
                      <div class="p-8">
                           <h3 class="font-display text-lg font-black uppercase tracking-tight mb-4 group-hover:text-safari-gold transition-colors line-clamp-2 leading-tight">{{ post.title }}</h3>
                           <p class="text-sm opacity-70 uppercase tracking-[0.2em] mb-8 line-clamp-2">By Master {{ post.user?.name }}</p>
                           
                           <div class="flex items-center justify-between pt-6 border-t border-white/5">
                                <div class="flex items-center gap-2">
                                     <Link :href="route('admin.blog.edit', { blog: post.id })" class="p-2 border border-white/5 hover:border-safari-gold/50 hover:bg-safari-gold/5 transition-all group/btn">
                                          <Edit2 class="w-3.5 h-3.5 opacity-80 group-hover/btn:opacity-100" />
                                     </Link>
                                     <button @click="deletePost(post.id)" class="p-2 border border-white/5 hover:border-red-500/50 hover:bg-red-500/5 transition-all group/btn">
                                          <Trash2 class="w-3.5 h-3.5 opacity-80 group-hover/btn:opacity-100" />
                                     </button>
                                </div>
                                <Link :href="route('blog.show', post.slug)" target="_blank" class="p-2 border border-white/5 hover:border-white/20 transition-all opacity-80 hover:opacity-100">
                                     <Eye class="w-3.5 h-3.5" />
                                </Link>
                           </div>
                      </div>
                 </div>
            </div>

            <div v-else class="py-32 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                 <Newspaper class="w-12 h-12 opacity-10 mb-6" />
                 <h3 class="font-display text-xl font-black uppercase tracking-widest mb-2">The Records are Quiet</h3>
                 <Link :href="route('admin.blog.create')" class="px-10 py-4 border border-white/20 text-sm font-black uppercase tracking-[0.3em] hover:bg-white hover:text-black transition-all">
                      Scribe a Story
                 </Link>
            </div>
        </div>
    </AppLayout>
</template>
