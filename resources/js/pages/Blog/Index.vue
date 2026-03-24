<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Search, ArrowRight, Clock, User } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps<{
    posts: any[];
}>();

const page = usePage<any>();
const layout = computed(() => page.url.startsWith('/dashboard') ? AppHeaderLayout : GuestLayout);
const searchQuery = ref('');

const filteredPosts = computed(() => {
    if (!searchQuery.value) return props.posts;
    return props.posts.filter(post => 
        post.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        post.content.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <Head title="Safari Chronicles - Big Simba Safaris" />

    <component :is="layout" :full-bleed="true">
        <main class="max-w-[1600px] mx-auto px-8 md:px-20 py-44">
            <!-- Immersive Header -->
            <header class="mb-24 space-y-12">
                 <div class="space-y-4 text-center">
                      <p class="text-[10px] font-black tracking-[0.6em] uppercase text-safari-gold">The Scribe's Archive</p>
                      <h1 class="text-6xl md:text-9xl font-black uppercase tracking-tighter leading-[0.8]">Safari <br/>Chronicles.</h1>
                 </div>
                 
                 <div class="max-w-2xl mx-auto relative group">
                      <Search class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-safari-gold opacity-40 group-focus-within:opacity-100 transition-opacity" />
                      <input 
                           v-model="searchQuery"
                           type="text" 
                           placeholder="SEARCH THE SAGAS..." 
                           class="w-full bg-white/5 border border-white/10 rounded-sm pl-16 pr-6 py-6 text-[11px] font-black uppercase tracking-[0.3em] focus:border-safari-gold transition-all outline-none text-center" 
                      />
                 </div>
            </header>

            <!-- Stories Grid -->
            <div v-if="filteredPosts.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-24">
                 <div v-for="post in filteredPosts" :key="post.id" class="group relative flex flex-col gap-8">
                      <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.show' : 'blog.show', post.slug)" class="block overflow-hidden rounded-sm border border-white/5 aspect-[16/9] bg-white/5">
                           <img 
                                :src="post.image || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072'" 
                                class="w-full h-full object-cover grayscale-[0.4] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-1000"
                           />
                      </Link>
                      
                      <div class="space-y-6">
                           <div class="flex items-center gap-6 text-[9px] font-black uppercase tracking-[0.2em] opacity-40">
                                <div class="flex items-center gap-2">
                                     <User class="w-3 h-3 text-safari-gold" /> {{ post.user?.name }}
                                </div>
                                <div class="flex items-center gap-2">
                                     <Clock class="w-3 h-3" /> {{ formatDate(post.created_at) }}
                                </div>
                           </div>
                           
                           <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.show' : 'blog.show', post.slug)" class="block group/title">
                                <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight leading-tight group-hover/title:text-safari-gold transition-colors">
                                     {{ post.title }}
                                </h2>
                           </Link>

                           <p class="text-sm font-light opacity-50 leading-relaxed line-clamp-3">
                                {{ post.content.substring(0, 180) }}...
                           </p>

                           <div class="pt-4">
                                <Link :href="route(page.url.startsWith('/dashboard') ? 'user.blog.show' : 'blog.show', post.slug)" class="inline-flex items-center gap-4 text-[10px] font-black tracking-[0.4em] uppercase group/more">
                                     Read Saga
                                     <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover/more:bg-safari-gold group-hover/more:text-black transition-all">
                                          <ArrowRight class="w-4 h-4" />
                                     </div>
                                </Link>
                           </div>
                      </div>
                 </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-40 text-center space-y-10 border border-dashed border-white/5 rounded-sm">
                 <p class="text-[11px] font-black uppercase tracking-[0.5em] opacity-20">The scrolls are missing</p>
                 <h3 class="text-3xl font-black uppercase tracking-tighter leading-tight">No chronicles found <br/> matching your search.</h3>
                 <button @click="searchQuery = ''" class="px-12 py-5 border border-white/20 text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-black transition-all">Return to Archives</button>
            </div>
        </main>
    </component>
</template>
