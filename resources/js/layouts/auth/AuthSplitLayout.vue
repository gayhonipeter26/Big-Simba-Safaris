<script setup lang="ts">
import SimbaLogo from '@/components/SimbaLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowLeft, Quote } from 'lucide-vue-next';

const page = usePage<any>();
const authName = page.props.name || 'Big Simba Safaris';
const quote = page.props.quote || {
    message: "The only man I envy is the man who has not yet been to Africa - for he has so much to look forward to.",
    author: "Richard Mullin"
};

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative grid h-svh flex-col items-center justify-center bg-[#050505] selection:bg-safari-gold selection:text-black lg:max-w-none lg:grid-cols-2 lg:px-0">
        
        <!-- Navigation / Back Link -->
        <div class="absolute top-8 left-8 lg:left-auto lg:right-8 z-50">
            <Link 
                :href="route('home')" 
                class="flex items-center gap-2 text-sm font-black uppercase tracking-[0.3em] text-white opacity-40 hover:opacity-100 hover:text-safari-gold transition-all group"
            >
                <ArrowLeft class="size-5 lg:size-4 group-hover:-translate-x-1 transition-transform" />
                <span class="hidden lg:inline">Back to Home</span>
            </Link>
        </div>

        <!-- Visual Pane (Background on Mobile, Left Column on Desktop) -->
        <div class="absolute lg:relative inset-0 lg:inset-auto z-0 h-full flex-col p-8 lg:p-16 text-white flex border-r-0 lg:border-r border-white/5 overflow-hidden">
            <div class="absolute inset-0 z-0 transition-transform duration-[10s]">
                <img 
                    src="https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071" 
                    class="w-full h-full object-cover grayscale-[0.5] lg:grayscale-[0.2] contrast-[1.1] brightness-[0.3] lg:brightness-[0.6]"
                    alt="Safari Heritage"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/80 lg:via-transparent to-black/80 lg:to-black/40"></div>
                <div class="absolute inset-0 bg-safari-gold/5 mix-blend-overlay"></div>
            </div>

            <!-- Logo (Desktop Only) -->
            <Link :href="route('home')" class="relative z-20 hidden lg:block">
                <SimbaLogo size="md" />
            </Link>

            <!-- Brand Quote (Desktop Only) -->
            <div class="relative z-20 mt-auto max-w-lg space-y-8 hidden lg:block">
                <Quote class="size-12 text-safari-gold/40" />
                <blockquote class="space-y-6">
                    <p class="text-3xl font-black uppercase tracking-tighter leading-[1.1]">&ldquo;{{ quote.message }}&rdquo;</p>
                    <footer class="flex items-center gap-4">
                        <div class="h-px w-8 bg-safari-gold/40"></div>
                        <span class="text-xs font-black uppercase tracking-[0.4em] text-safari-gold/60">{{ quote.author }}</span>
                    </footer>
                </blockquote>
            </div>
        </div>

        <!-- Right Content Pane -->
        <div class="relative h-full flex items-center justify-center p-8 lg:p-16 overflow-hidden">
            <!-- Glow background -->
            <div class="absolute inset-0 pointer-events-none opacity-20">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-safari-gold blur-[120px] rounded-full"></div>
            </div>

            <div class="relative z-10 mx-auto flex w-full flex-col justify-center space-y-10 sm:w-[400px]">
                <div class="flex flex-col space-y-3 text-center lg:text-left">
                    <div class="lg:hidden flex justify-center mb-6">
                         <Link :href="route('home')">
                            <SimbaLogo size="md" />
                         </Link>
                    </div>
                    <h1 class="font-display text-4xl font-black uppercase tracking-tighter leading-none" v-if="title">{{ title }}</h1>
                    <p class="text-sm font-black uppercase tracking-[0.4em] text-safari-gold/60" v-if="description">{{ description }}</p>
                </div>
                <!-- Main Auth Slot -->
                <div class="backdrop-blur-sm">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
