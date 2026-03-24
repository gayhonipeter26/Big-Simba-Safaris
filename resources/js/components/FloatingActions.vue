<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { ChevronUp, MessageCircle } from 'lucide-vue-next';

const isScrolled = ref(false);
const whatsappNumber = "254726159925";
const message = "Jambo Kennedy! I would like to inquire about a safari expedition with Big Simba Safaris.";

const handleScroll = () => {
    isScrolled.value = window.scrollY > 300;
};

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <div class="fixed bottom-8 right-8 z-[999] flex flex-col items-center gap-4">
        <!-- Scroll to Top -->
        <transition 
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0 translate-y-10 scale-50"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-10 scale-50"
        >
            <button 
                v-if="isScrolled"
                @click="scrollToTop"
                class="w-12 h-12 bg-black/80 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center text-safari-gold shadow-2xl hover:bg-safari-gold hover:text-black hover:border-safari-gold transition-all duration-500 group"
                aria-label="Scroll to top"
            >
                <ChevronUp class="w-5 h-5 group-hover:-translate-y-1 transition-transform" />
            </button>
        </transition>

        <!-- WhatsApp Float -->
        <a 
            :href="`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`"
            target="_blank"
            class="w-14 h-14 bg-safari-gold rounded-full flex items-center justify-center text-black shadow-xl hover:scale-110 transition-all duration-300 group relative"
            aria-label="Chat on WhatsApp"
        >
            <MessageCircle class="w-8 h-8 fill-current transition-transform" />
            
            <!-- Tooltip -->
            <span class="absolute right-full mr-4 px-4 py-2 bg-black border border-white/10 text-safari-gold text-[10px] font-black uppercase tracking-widest rounded-sm opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap hidden md:block">
                Chat With Us
            </span>
        </a>
    </div>
</template>
