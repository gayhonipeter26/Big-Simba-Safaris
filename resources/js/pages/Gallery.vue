<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Camera, Video, Maximize2, X, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { ref, onMounted, computed } from 'vue';

defineProps<{
    items: any[];
}>();

const page = usePage<any>();
const auth = computed(() => page.props.auth);
const layout = computed(() => page.url.startsWith('/dashboard') ? AppHeaderLayout : GuestLayout);

const selectedItem = ref<any>(null);
const currentIndex = ref(-1);

const openLightbox = (item: any, index: number) => {
    selectedItem.value = item;
    currentIndex.value = index;
    document.body.style.overflow = 'hidden';
};

const closeLightbox = () => {
    selectedItem.value = null;
    currentIndex.value = -1;
    document.body.style.overflow = 'auto';
};

const nextItem = (items: any[]) => {
    if (currentIndex.value < items.length - 1) {
        currentIndex.value++;
        selectedItem.value = items[currentIndex.value];
    }
};

const prevItem = (items: any[]) => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
        selectedItem.value = items[currentIndex.value];
    }
};

onMounted(() => {
    window.addEventListener('keydown', (e) => {
        if (!selectedItem.value) return;
        if (e.key === 'Escape') closeLightbox();
    });
});
</script>

<template>
    <Head title="Wildlife Gallery | Big Simba Safaris" />

    <component :is="layout" :full-bleed="true">
        <div class="min-h-screen bg-[#050505] pt-24 pb-32">
            <!-- Header Section -->
            <div class="relative py-32 px-8 overflow-hidden">
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1516422213484-21437ef33ebb?q=80&w=2670" class="w-full h-full object-cover opacity-10 grayscale blur-sm scale-110" />
                    <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-[#050505] to-transparent"></div>
                </div>

                <div class="container mx-auto relative z-10 text-center space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000">
                    <p class="text-[10px] md:text-[12px] font-black tracking-[0.6em] uppercase text-safari-gold">- Visual Archives //</p>
                    <h1 class="text-6xl md:text-[8rem] font-black uppercase tracking-tighter leading-none text-white">THE WILD <span class="text-safari-gold italic block md:inline md:ml-4">GALLERY</span></h1>
                    <p class="max-w-xl mx-auto text-xs md:text-sm font-light opacity-40 uppercase tracking-[0.2em] leading-relaxed italic text-white/50">
                        A curated register of the savanna's most cinematic moments. Capture the spirit of Kenya through our lenses.
                    </p>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="container mx-auto px-8 md:px-16 mt-12">
                <div v-if="items.length > 0" class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-8 space-y-8">
                    <div 
                        v-for="(item, index) in items" 
                        :key="item.id" 
                        class="break-inside-avoid group relative overflow-hidden rounded-sm border border-white/5 cursor-pointer shadow-2xl transition-all duration-700 hover:border-safari-gold/30 hover:-translate-y-2"
                        @click="openLightbox(item, index)"
                    >
                        <!-- Visual Container -->
                        <div class="relative aspect-auto">
                            <img 
                                v-if="item.type === 'image'" 
                                :src="item.url" 
                                :alt="item.title"
                                class="w-full h-auto grayscale-[0.8] group-hover:grayscale-0 transition-all duration-[1500ms] group-hover:scale-110"
                            />
                            <div v-else class="aspect-video bg-black flex items-center justify-center relative">
                                <Video class="w-12 h-12 text-safari-gold opacity-40 group-hover:opacity-100 transition-opacity" />
                                <img :src="item.url" class="absolute inset-0 w-full h-full object-cover opacity-20 grayscale" />
                            </div>

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 flex flex-col justify-end p-8">
                                <div class="space-y-4 translate-y-4 group-hover:translate-y-0 transition-transform duration-700">
                                    <div class="flex items-center gap-2 text-[8px] font-black uppercase tracking-[0.3em] text-safari-gold">
                                        <Camera v-if="item.type === 'image'" class="w-3 h-3" />
                                        <Video v-else class="w-3 h-3" />
                                        {{ item.type }}
                                    </div>
                                    <h3 class="text-lg font-black uppercase tracking-tight leading-tight text-white">{{ item.title }}</h3>
                                    <p class="text-[9px] opacity-40 italic uppercase tracking-widest line-clamp-2 text-white/60">{{ item.description }}</p>
                                    <div class="flex items-center gap-2 pt-4">
                                        <div class="w-8 h-[1px] bg-safari-gold"></div>
                                        <span class="text-[8px] font-black uppercase tracking-[0.2em] text-white">Enlarge Archive</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="py-44 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                    <Camera class="w-16 h-16 opacity-5 mb-8 text-safari-gold" />
                    <h3 class="text-2xl font-black uppercase tracking-widest mb-4 text-white">Archives are Locked</h3>
                    <p class="text-xs opacity-40 max-w-sm mb-12 uppercase tracking-widest italic leading-relaxed text-white/50">The visual register is currently being curated. Return soon for new wilderness records.</p>
                    <Link href="/" class="px-12 py-5 border border-white/20 text-[10px] font-black uppercase tracking-[0.4em] text-white hover:bg-white hover:text-black transition-all">Return to Outpost</Link>
                </div>
            </div>
        </div>

        <!-- Lightbox -->
        <div v-if="selectedItem" class="fixed inset-0 z-[1000] bg-black/98 backdrop-blur-2xl flex items-center justify-center p-4 md:p-12 animate-in fade-in duration-300">
            <!-- Close Button -->
            <button @click="closeLightbox" class="absolute top-8 right-8 text-white/20 hover:text-white transition-all z-50 p-4 border border-white/5 rounded-full hover:bg-white/5">
                <X class="w-8 h-8" />
            </button>

            <!-- Navigation -->
            <button 
                v-if="currentIndex > 0"
                @click="prevItem(items)" 
                class="absolute left-8 top-1/2 -translate-y-1/2 text-white/20 hover:text-white transition-all z-50 p-4 hover:bg-white/5 rounded-full"
            >
                <ChevronLeft class="w-12 h-12" />
            </button>
            <button 
                v-if="currentIndex < items.length - 1"
                @click="nextItem(items)" 
                class="absolute right-8 top-1/2 -translate-y-1/2 text-white/20 hover:text-white transition-all z-50 p-4 hover:bg-white/5 rounded-full"
            >
                <ChevronRight class="w-12 h-12" />
            </button>

            <!-- Content -->
            <div class="w-full max-w-7xl h-full flex flex-col items-center justify-center space-y-12 pb-12">
                <div class="relative w-full h-[60vh] md:h-[70vh] flex items-center justify-center group/img">
                    <img 
                        v-if="selectedItem.type === 'image'" 
                        :src="selectedItem.url" 
                        class="max-w-full max-h-full object-contain shadow-[0_0_100px_rgba(0,0,0,0.5)] border border-white/5" 
                    />
                    <iframe 
                        v-else
                        :src="selectedItem.url.replace('watch?v=', 'embed/')"
                        class="w-full aspect-video md:w-3/4 max-h-full border border-white/5"
                        frameborder="0"
                        allowfullscreen
                    ></iframe>
                </div>

                <div class="text-center space-y-4 max-w-2xl animate-in slide-in-from-bottom duration-700">
                    <div class="flex flex-col items-center gap-2">
                        <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold">
                            <Camera v-if="selectedItem.type === 'image'" class="w-4 h-4" />
                            <Video v-else class="w-4 h-4" />
                            {{ selectedItem.type }}
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tight text-white">{{ selectedItem.title }}</h2>
                    </div>
                    <p class="text-sm font-light opacity-50 uppercase tracking-widest italic leading-relaxed text-white/50">{{ selectedItem.description }}</p>
                </div>
            </div>

            <!-- Progress Indicator -->
            <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex items-center gap-4 text-[10px] font-black uppercase tracking-widest opacity-20 text-white">
                <span>{{ currentIndex + 1 }}</span>
                <div class="w-20 h-[1px] bg-white/20">
                    <div class="h-full bg-safari-gold transition-all duration-500" :style="{ width: ((currentIndex + 1) / items.length * 100) + '%' }"></div>
                </div>
                <span>{{ items.length }}</span>
            </div>
        </div>
    </component>
</template>

<style scoped>
.animate-in {
    animation-duration: 0.8s;
    animation-fill-mode: both;
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slide-in-from-bottom-10 {
    from { transform: translateY(40px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>


<style scoped>
.animate-in {
    animation-duration: 0.8s;
    animation-fill-mode: both;
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slide-in-from-bottom-10 {
    from { transform: translateY(40px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>
