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
const layout = computed(() => (page.props.auth?.user || page.url.startsWith('/dashboard')) ? AppHeaderLayout : GuestLayout);

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <Head :title="`${post.title} - Safari Chronicles`" />

    <component :is="layout" :full-bleed="true">
        <!-- Dynamic Header -->
        <header class="max-w-[1600px] mx-auto px-5 md:px-20 pt-32 md:pt-44 pb-12 md:pb-16 space-y-12 md:space-y-16">
             <div class="space-y-8 text-center">
                  <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.index' : 'blog.index')" class="text-sm font-black tracking-[0.6em] uppercase text-safari-gold opacity-90 hover:opacity-100 flex items-center justify-center gap-3">
                       <ArrowLeft class="w-3 h-3" /> The Archives
                  </Link>
                  <h1 class="font-display text-3xl sm:text-5xl md:text-[7rem] font-black uppercase tracking-tighter leading-[0.9] md:leading-[0.85] text-center max-w-screen-xl mx-auto text-white">
                       {{ post.title }}
                  </h1>
                  <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-12 text-[10px] md:text-sm font-black uppercase tracking-[0.3em] opacity-80 border-y border-white/5 py-6 md:py-8 max-w-2xl mx-auto text-white">
                       <div class="flex items-center gap-2 md:gap-3">
                            <User class="w-3.5 h-3.5 md:w-4 md:h-4 text-safari-gold" /> {{ post.user?.name }}
                       </div>
                       <div class="hidden sm:block h-1 w-1 rounded-full bg-safari-gold opacity-20"></div>
                       <div class="flex items-center gap-2 md:gap-3">
                            <Clock class="w-3.5 h-3.5 md:w-4 md:h-4 text-safari-gold" /> {{ formatDate(post.created_at) }}
                       </div>
                  </div>
             </div>

             <!-- Cover Piece -->
             <div class="aspect-[21/9] rounded-sm overflow-hidden border border-white/5 bg-white/5 relative group shadow-2xl">
                  <img 
                        :src="post.image || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072'" 
                        class="w-full h-full object-cover grayscale-[0.5] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-[1500ms]"
                        loading="lazy"
                   />
                  <div class="absolute inset-0 bg-safari-gold/5 opacity-40 group-hover:opacity-0 transition-opacity"></div>
                  <div class="absolute inset-0 bg-gradient-to-t from-[#050505]/40 to-transparent"></div>
             </div>
        </header>

        <main class="max-w-[1600px] mx-auto px-5 md:px-20 pt-32 pb-24 md:py-44">
             <div class="prose prose-invert max-w-none">
                  <div class="text-base sm:text-xl md:text-2xl font-light opacity-90 leading-relaxed md:leading-loose italic mb-10 md:mb-16 border-l-2 border-safari-gold pl-4 md:pl-12 text-white/80">
                       Journey into the heart of the wild as we explore the legends and lore of Kenya's most iconic landscapes through the eyes of our expert scouts and local storytellers.
                  </div>
                  <div class="text-base sm:text-lg md:text-2xl font-normal text-gray-300 leading-relaxed space-y-8 md:space-y-12 whitespace-pre-wrap tracking-wide first-letter:text-5xl sm:first-letter:text-[7rem] first-letter:font-black first-letter:float-left first-letter:mr-4 sm:first-letter:mr-6 first-letter:leading-[0.8] first-letter:text-safari-gold">
                       {{ post.content }}
                  </div>
             </div>

             <!-- Bottom Meta -->
             <div class="mt-32 pt-16 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-12">
                  <div class="flex items-center gap-4 sm:gap-8 bg-white/[0.02] border border-white/5 px-6 md:px-10 py-4 md:py-6 rounded-sm">
                       <p class="text-[10px] md:text-sm font-black uppercase tracking-[0.4em] opacity-70 text-white">Share Saga</p>
                       <div class="flex gap-4 md:gap-6">
                            <button class="h-8 w-8 md:h-10 md:w-10 border border-white/10 hover:border-safari-gold hover:bg-safari-gold hover:text-black transition-all rounded-full flex items-center justify-center text-white"><Facebook class="w-3.5 h-3.5 md:w-4 md:h-4" /></button>
                            <button class="h-8 w-8 md:h-10 md:w-10 border border-white/10 hover:border-safari-gold hover:bg-safari-gold hover:text-black transition-all rounded-full flex items-center justify-center text-white"><Twitter class="w-3.5 h-3.5 md:w-4 md:h-4" /></button>
                            <button class="h-8 w-8 md:h-10 md:w-10 border border-white/10 hover:border-safari-gold hover:bg-safari-gold hover:text-black transition-all rounded-full flex items-center justify-center text-white"><Instagram class="w-3.5 h-3.5 md:w-4 md:h-4" /></button>
                       </div>
                  </div>
                  <div class="text-right">
                       <p class="text-xs font-black uppercase tracking-[0.4em] opacity-20">Saga Sequence: #{{ post.id }}</p>
                       <p class="text-xs font-bold uppercase tracking-[0.2em] opacity-10 mt-2 italic">Authentic Big Simba Documentation</p>
                  </div>
             </div>
        </main>

        <!-- More Chronicles -->
        <section v-if="recentPosts.length > 0" class="max-w-[1600px] mx-auto px-5 md:px-20 py-24 md:py-44 border-t border-white/5 space-y-12 md:space-y-20">
             <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-white/5 pb-8 md:pb-12 gap-6">
                  <div class="space-y-3 md:space-y-4">
                       <p class="text-[10px] md:text-sm font-black tracking-[0.4em] uppercase text-safari-gold">Parallel Stories</p>
                       <h3 class="font-display text-4xl md:text-5xl font-black uppercase tracking-tighter leading-none text-white">More Chronicles</h3>
                  </div>
                  <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.index' : 'blog.index')" class="text-[10px] md:text-sm font-black tracking-[0.4em] uppercase opacity-80 hover:opacity-100 hover:text-safari-gold transition-all border-b border-white/10 pb-2 w-fit text-white">View Full Archive</Link>
             </div>
             
             <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                  <div v-for="recent in recentPosts" :key="recent.id" class="group space-y-8">
                       <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.show' : 'blog.show', recent.slug)" class="block aspect-video overflow-hidden border border-white/5 rounded-sm relative bg-white/5 shadow-xl">
                            <img :src="recent.image" class="w-full h-full object-cover grayscale-[0.6] group-hover:grayscale-0 group-hover:scale-110 transition-all duration-[2000ms]" loading="lazy" />
                            <div class="absolute inset-0 bg-safari-gold/10 opacity-40 group-hover:opacity-0 transition-opacity"></div>
                       </Link>
                       <div class="space-y-4">
                            <div class="flex items-center gap-4 text-xs font-black uppercase tracking-widest opacity-70">
                                 <span>{{ formatDate(recent.created_at) }}</span>
                                 <span class="w-1 h-1 bg-safari-gold rounded-full"></span>
                                 <span>By {{ recent.user?.name }}</span>
                            </div>
                            <h4 class="font-display text-2xl font-black uppercase tracking-tight group-hover:text-safari-gold transition-colors line-clamp-2 leading-tight">{{ recent.title }}</h4>
                            <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.show' : 'blog.show', recent.slug)" class="text-xs font-black uppercase tracking-[0.4em] flex items-center gap-3 opacity-80 group-hover:opacity-100 transition-opacity">
                                 Read More <ArrowRight class="w-3 h-3 group-hover:translate-x-1 transition-transform" />
                            </Link>
                       </div>
                  </div>
             </div>
        </section>
    </component>
</template>
