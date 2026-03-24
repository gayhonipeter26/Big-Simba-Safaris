<script setup lang="ts">
import SimbaLogo from '@/components/SimbaLogo.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-[#030303] text-white p-6 md:p-10 selection:bg-safari-gold selection:text-black relative overflow-hidden">
        <!-- Cinematic Background Image -->
        <div class="absolute inset-0 z-0">
            <img 
                src="https://images.unsplash.com/photo-1516422213484-21437ef33ebb?q=80&w=2670" 
                class="w-full h-full object-cover opacity-20 scale-105"
                alt="Safari Background"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-black via-black/80 to-black"></div>
        </div>

        <!-- Back Link -->
        <div class="absolute top-8 left-8 z-20">
            <Link 
                :href="route('home')" 
                class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.3em] opacity-40 hover:opacity-100 hover:text-safari-gold transition-all group"
            >
                <ArrowLeft class="size-4 group-hover:-translate-x-1 transition-transform" />
                Back to Savannah
            </Link>
        </div>

        <div class="w-full max-w-sm relative z-10">
            <div class="flex flex-col gap-10">
                <div class="flex flex-col items-center gap-6">
                    <Link :href="route('home')" class="flex flex-col items-center gap-4 group">
                        <SimbaLogo size="lg" />
                    </Link>
                    <div class="space-y-3 text-center">
                        <h1 class="text-3xl font-black uppercase tracking-tighter leading-none">{{ title }}</h1>
                        <p class="text-center text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold opacity-60">{{ description }}</p>
                    </div>
                </div>
                <!-- Main Auth Slot -->
                <div class="bg-black/60 border border-white/5 rounded-sm p-8 backdrop-blur-2xl shadow-2xl">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
