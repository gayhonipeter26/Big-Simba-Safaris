<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Clock, User, ArrowLeft, ArrowRight, Share2, Facebook, Twitter, Instagram } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    post: any;
    recentPosts: any[];
}>();

const page = usePage<any>();
const layout = computed(() => page.url.startsWith('/dashboard') ? AppHeaderLayout : GuestLayout);

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <Head :title="`${post.title} - Safari Chronicles`" />

    <component :is="layout" :full-bleed="true">
        <!-- Dynamic Header -->
        <header class="max-w-[1600px] mx-auto px-8 md:px-20 pt-44 pb-16 space-y-16">
             <div class="space-y-8 text-center">
                  <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.index' : 'blog.index')" class="text-[10px] font-black tracking-[0.6em] uppercase text-safari-gold opacity-60 hover:opacity-100 flex items-center justify-center gap-3">
                       <ArrowLeft class="w-3 h-3" /> The Archives
                  </Link>
                  <h1 class="text-5xl md:text-[7rem] font-black uppercase tracking-tighter leading-[0.85] text-center max-w-screen-xl mx-auto">
                       {{ post.title }}
                  </h1>
                  <div class="flex items-center justify-center gap-12 text-[10px] font-black uppercase tracking-[0.3em] opacity-40 border-y border-white/5 py-8 max-w-2xl mx-auto">
                       <div class="flex items-center gap-3">
                            <User class="w-4 h-4 text-safari-gold" /> {{ post.user?.name }}
                       </div>
                       <div class="h-1 w-1 rounded-full bg-safari-gold opacity-20"></div>
                       <div class="flex items-center gap-3">
                            <Clock class="w-4 h-4 text-safari-gold" /> {{ formatDate(post.created_at) }}
                       </div>
                  </div>
             </div>

             <!-- Cover Piece -->
             <div class="aspect-[21/9] rounded-sm overflow-hidden border border-white/5 bg-white/5 relative group shadow-2xl">
                  <img :src="post.image || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072'" class="w-full h-full object-cover grayscale-[0.2] transition-transform duration-[3000ms] group-hover:scale-110" />
                  <div class="absolute inset-0 bg-safari-gold/5 mix-blend-multiply"></div>
             </div>
        </header>

        <main class="max-w-screen-xl mx-auto px-8 md:px-20 py-24">
             <div class="prose prose-invert max-w-none">
                  <div class="text-xl md:text-2xl font-light opacity-50 leading-loose italic mb-16 border-l-2 border-safari-gold pl-12">
                       Journey into the heart of the wild as we explore the legends and lore of Kenya's most iconic landscapes through the eyes of our expert scouts and local storytellers.
                  </div>
                  <div class="text-lg md:text-2xl font-light opacity-80 leading-relaxed space-y-12 whitespace-pre-wrap tracking-wide first-letter:text-[7rem] first-letter:font-black first-letter:float-left first-letter:mr-6 first-letter:leading-[0.8] first-letter:text-safari-gold">
                       {{ post.content }}
                  </div>
             </div>

             <!-- Bottom Meta -->
             <div class="mt-32 pt-16 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-12">
                  <div class="flex items-center gap-8 bg-white/[0.02] border border-white/5 px-10 py-6 rounded-sm">
                       <p class="text-[10px] font-black uppercase tracking-[0.4em] opacity-30">Share Saga</p>
                       <div class="flex gap-6">
                            <button class="h-10 w-10 border border-white/10 hover:border-safari-gold hover:bg-safari-gold hover:text-black transition-all rounded-full flex items-center justify-center"><Facebook class="w-4 h-4" /></button>
                            <button class="h-10 w-10 border border-white/10 hover:border-safari-gold hover:bg-safari-gold hover:text-black transition-all rounded-full flex items-center justify-center"><Twitter class="w-4 h-4" /></button>
                            <button class="h-10 w-10 border border-white/10 hover:border-safari-gold hover:bg-safari-gold hover:text-black transition-all rounded-full flex items-center justify-center"><Instagram class="w-4 h-4" /></button>
                       </div>
                  </div>
                  <div class="text-right">
                       <p class="text-[9px] font-black uppercase tracking-[0.4em] opacity-20">Saga Sequence: #{{ post.id }}</p>
                       <p class="text-[8px] font-bold uppercase tracking-[0.2em] opacity-10 mt-2 italic">Authentic Big Simba Documentation</p>
                  </div>
             </div>
        </main>

        <!-- More Chronicles -->
        <section v-if="recentPosts.length > 0" class="max-w-[1600px] mx-auto px-8 md:px-20 py-44 border-t border-white/5 space-y-20">
             <div class="flex items-end justify-between border-b border-white/5 pb-12">
                  <div class="space-y-4">
                       <p class="text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold">Parallel Stories</p>
                       <h3 class="text-5xl font-black uppercase tracking-tighter leading-none">More Chronicles</h3>
                  </div>
                  <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.index' : 'blog.index')" class="text-[10px] font-black tracking-[0.4em] uppercase opacity-40 hover:opacity-100 hover:text-safari-gold transition-all border-b border-white/10 pb-2">View Full Archive</Link>
             </div>
             
             <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                  <div v-for="recent in recentPosts" :key="recent.id" class="group space-y-8">
                       <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.show' : 'blog.show', recent.slug)" class="block aspect-video overflow-hidden border border-white/5 rounded-sm relative bg-white/5 shadow-xl">
                            <img :src="recent.image" class="w-full h-full object-cover grayscale-[0.6] group-hover:grayscale-0 group-hover:scale-110 transition-all duration-[2000ms]" />
                            <div class="absolute inset-0 bg-safari-gold/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                       </Link>
                       <div class="space-y-4">
                            <div class="flex items-center gap-4 text-[9px] font-black uppercase tracking-widest opacity-30">
                                 <span>{{ formatDate(recent.created_at) }}</span>
                                 <span class="w-1 h-1 bg-safari-gold rounded-full"></span>
                                 <span>By {{ recent.user?.name }}</span>
                            </div>
                            <h4 class="text-2xl font-black uppercase tracking-tight group-hover:text-safari-gold transition-colors line-clamp-2 leading-tight">{{ recent.title }}</h4>
                            <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.show' : 'blog.show', recent.slug)" class="text-[9px] font-black uppercase tracking-[0.4em] flex items-center gap-3 opacity-40 group-hover:opacity-100 transition-opacity">
                                 Read More <ArrowRight class="w-3 h-3 group-hover:translate-x-1 transition-transform" />
                            </Link>
                       </div>
                  </div>
             </div>
        </section>
    </component>
</template>
