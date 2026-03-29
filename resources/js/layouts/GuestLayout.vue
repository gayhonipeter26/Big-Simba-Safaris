<script setup lang="ts">
import SimbaLogo from '@/components/SimbaLogo.vue';
import FloatingActions from '@/components/FloatingActions.vue';
import Toast from '@/components/Toast.vue';
import CurrencySwitcher from '@/components/CurrencySwitcher.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { useForm, router, usePage, Link } from '@inertiajs/vue3';
import SimbaFooter from '@/components/SimbaFooter.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import { Search, Menu, X, User, LayoutGrid, Instagram, Twitter, Star, Send, Phone, Mail, MapPin, ChevronDown } from 'lucide-vue-next';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const isCurrentRoute = (routeUrl: string) => {
    // Strip query strings from both sides for comparison
    const currentPath = page.url.split('?')[0];

    // Extract path from routeUrl in case it's a full URL (Ziggy default)
    let targetPath = routeUrl.split('?')[0];
    if (targetPath.startsWith('http')) {
        try {
            targetPath = new URL(targetPath).pathname;
        } catch (e) {
            // Fallback to original string if URL parsing fails
        }
    }

    // Exact match OR current path starts with target (for nested routes)
    // Avoid matching '/' against everything
    if (targetPath === '/') return currentPath === '/';
    return currentPath === targetPath || currentPath.startsWith(targetPath + '/');
};

const page = usePage<any>();
const user = computed(() => page.props.auth?.user);
const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);
const expandedMobileLinks = ref<Record<string, boolean>>({});

const toggleMobileLink = (name: string) => {
    expandedMobileLinks.value[name] = !expandedMobileLinks.value[name];
};

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const navLinks = [
    { 
        name: 'Home', 
        href: route('home'),
        sections: [
            { name: 'Safari Story', hash: '#about' },
            { name: 'Strategic Locales', hash: '#locales' },
            { name: 'Elite Expeditions', hash: '#expeditions' },
            { name: 'Tactical Fleet', hash: '#logistics' },
            { name: 'Mission Support', hash: '#support' },
            { name: 'Wildlife Gallery', hash: '#gallery' },
            { name: 'Witness Reports', hash: '#debriefings' },
            { name: 'Situation Report', hash: '#contact' },
        ]
    },
    { name: 'Expeditions', href: route('tours.index') },
    { name: 'Destinations', href: route('destinations.index') },
    { name: 'Chronicles', href: route('blog.index') },
];

const reviewForm = useForm({
    tour_id: '',
    rating: 5,
    comment: '',
    name: user.value?.name || '',
    email: user.value?.email || '',
});

const submitReview = () => {
    if (!reviewForm.tour_id) return;

    reviewForm.post(route('reviews.store', { tour: reviewForm.tour_id }), {
        preserveScroll: true,
        onSuccess: () => {
             reviewForm.reset();
             // Optional: Show success toast
        },
     });
};

const tours = computed(() => page.props.tours || []);
</script>

<template>
    <div class="min-h-screen bg-[#050505] text-white font-sans selection:bg-safari-gold selection:text-black">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[1001] focus:bg-safari-gold focus:text-black focus:px-6 focus:py-3 focus:font-black focus:uppercase focus:tracking-widest focus:rounded-sm shadow-2xl">
            Skip to Tactical Content
        </a>
        <!-- Persistent Premium Navigation -->
        <nav 
            class="fixed top-0 left-0 right-0 z-[100] transition-all duration-700 px-4 md:px-10 py-3 md:py-5"
            :class="[
                isScrolled ? 'bg-black/90 backdrop-blur-2xl border-b border-white/10 py-2.5 md:py-3.5' : 'bg-transparent'
            ]"
        >
            <div class="w-full flex items-center justify-between">
                <div class="flex items-center gap-6 md:gap-12">
                    <Link :href="route('home')" class="hover:scale-105 transition-transform shrink-0" aria-label="Big Simba Safaris Home">
                        <SimbaLogo aria-hidden="true" />
                    </Link>
                    
                    <div class="hidden lg:flex items-center gap-6 md:gap-8">
                        <div 
                            v-for="link in navLinks" 
                            :key="link.name"
                            class="relative group"
                        >
                            <Link 
                                :href="link.href"
                                :aria-label="`Navigate to ${link.name}`"
                                class="text-xs xl:text-sm font-black tracking-[0.3em] uppercase transition-all hover:text-safari-gold flex items-center gap-2"
                                :class="isCurrentRoute(link.href) ? 'text-safari-gold opacity-100' : 'opacity-70 text-white/70'"
                            >
                                {{ link.name }}
                                <ChevronDown v-if="link.sections" class="w-3.5 h-3.5 group-hover:rotate-180 transition-transform opacity-50 group-hover:opacity-100" aria-hidden="true" />
                            </Link>
                            
                            <!-- Mega Dropdown for Home or any link with sections -->
                            <div v-if="link.sections" class="absolute top-full -left-4 xl:-left-20 pt-6 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-700 translate-y-6 group-hover:translate-y-0 z-50">
                                <div class="bg-black/90 backdrop-blur-3xl border border-white/5 p-8 w-[500px] shadow-[0_40px_100px_rgba(0,0,0,0.8)] rounded-sm relative overflow-hidden group/menu">
                                    <!-- Background tactical texture -->
                                    <div class="absolute inset-0 opacity-5 pointer-events-none">
                                        <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-safari-gold to-transparent"></div>
                                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-safari-gold/20 via-transparent to-transparent blur-3xl opacity-20"></div>
                                    </div>

                                    <div class="relative z-10">
                                        <div class="flex items-center gap-4 mb-8 border-b border-white/10 pb-4">
                                             <div class="w-1.5 h-1.5 rounded-full bg-safari-gold animate-pulse"></div>
                                             <p class="text-[10px] font-black uppercase tracking-[0.5em] text-safari-gold/80">Mission Parameters // Quick Navigation</p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-x-12 gap-y-6">
                                            <Link 
                                                v-for="section in link.sections" 
                                                :key="section.hash"
                                                :href="route('home') + section.hash"
                                                class="group/item flex items-center gap-4 text-[11px] xl:text-xs font-black uppercase tracking-[0.3em] transition-all"
                                            >
                                                <span class="w-4 h-[1px] bg-white/10 group-hover/item:bg-safari-gold group-hover/item:w-8 transition-all duration-500"></span>
                                                <span class="text-white/40 group-hover/item:text-white group-hover/item:translate-x-1 transition-all duration-300">
                                                    {{ section.name }}
                                                </span>
                                            </Link>
                                        </div>

                                        <div class="mt-10 pt-6 border-t border-white/10 flex items-center justify-between opacity-30">
                                             <span class="text-[8px] font-black tracking-[0.4em] uppercase">Big Simba Command // Sector Alpha</span>
                                             <div class="flex gap-2">
                                                  <div class="h-1 w-1 bg-white"></div>
                                                  <div class="h-1 w-1 bg-white"></div>
                                                  <div class="h-1 w-1 bg-white"></div>
                                             </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-8">
                    <!-- Kenya Flag -->
                    <div class="hidden md:block w-12 h-8 rounded-sm overflow-hidden shadow-md shadow-black/50 shrink-0 border border-white/10 group cursor-default">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/49/Flag_of_Kenya.svg" alt="Republic of Kenya Flag" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                    </div>

                    <!-- Nav Actions -->
                    <div class="flex items-center gap-2 md:gap-8 border-l border-white/10 pl-2 md:pl-8">
                        <template v-if="user">
                            <Link 
                                :href="route('dashboard')"
                                aria-label="Access Member Dashboard"
                                class="group flex items-center gap-2 text-[10px] font-black tracking-[0.2em] uppercase opacity-90 hover:opacity-100 transition-all hover:text-safari-gold"
                            >
                                <LayoutGrid class="w-3 h-3" aria-hidden="true" />
                                <span class="hidden sm:inline">My Pride</span>
                            </Link>
                            <CurrencySwitcher />

                            <DropdownMenu>
                                <DropdownMenuTrigger :as-child="true">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        aria-label="User Mission Menu"
                                        class="relative size-10 w-auto rounded-full p-1 focus-within:ring-2 focus-within:ring-safari-gold"
                                    >
                                        <Avatar class="size-8 overflow-hidden rounded-full border border-white/10 group-hover:border-safari-gold transition-colors">
                                            <AvatarImage :src="user.avatar" :alt="user.name" />
                                            <AvatarFallback class="rounded-lg bg-neutral-800 font-semibold text-white">
                                                {{ getInitials(user.name) }}
                                            </AvatarFallback>
                                        </Avatar>
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end" class="w-56 bg-black/95 backdrop-blur-3xl border-white/10 text-white shadow-2xl">
                                    <UserMenuContent :user="user" />
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </template>
                        <template v-else>
                            <div class="flex items-center gap-2 md:gap-6 mr-1 md:mr-4">
                                <CurrencySwitcher class="origin-right" />
                                <a href="tel:+254726159925" class="flex items-center gap-1.5 text-[8px] md:text-[11px] font-black uppercase tracking-[0.1em] md:tracking-[0.15em] text-white/60 hover:text-safari-gold transition-all shrink-0" aria-label="Call Big Simba Safaris Operations">
                                    <Phone class="w-2.5 h-2.5 md:w-3.5 md:h-3.5 text-safari-gold shrink-0" aria-hidden="true" />
                                    <span class="hidden sm:inline">+254 726 159 925</span>
                                    <span class="sm:hidden">CALL</span>
                                </a>
                            </div>
                            <Link :href="route('register')" class="px-3 md:px-8 py-1.5 md:py-3 bg-safari-gold/20 border border-safari-gold/30 text-safari-gold text-[9px] md:text-xs font-black uppercase tracking-[0.1em] rounded-sm hover:bg-safari-gold hover:text-black transition-all whitespace-nowrap">Join Us</Link>
                        </template>
                    </div>

                    <!-- Mobile Toggle -->
                    <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden text-white opacity-90 hover:opacity-100 p-1.5 ml-1 bg-white/5 border border-white/20 rounded-sm" aria-label="Toggle Mission Menu">
                        <Menu v-if="!isMobileMenuOpen" class="w-4 h-4" aria-hidden="true" />
                        <X v-else class="w-4 h-4" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Overlay -->
        <transition 
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0 -translate-y-10"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-10"
        >
            <div v-if="isMobileMenuOpen" class="fixed inset-0 z-[90] bg-black/95 backdrop-blur-3xl pt-24 px-8 flex flex-col items-center justify-center lg:hidden border-b border-white/5 min-h-screen overflow-y-auto">
                <!-- Tactical Mission Quote (Top) -->
                <div class="mb-16 text-center animate-in fade-in slide-in-from-top-4 duration-1000">
                     <p class="text-[10px] font-black tracking-[0.4em] text-safari-gold mb-4 opacity-100 uppercase">// SAVANNA MANDATE</p>
                     <p class="text-xs font-light italic text-white/40 max-w-[240px] leading-relaxed mx-auto">
                        "In the wild, consistency is not just a choice—it's survival. Lead the pride with absolute authority."
                     </p>
                </div>

                <!-- Centralized Triangle Navigation Matrix -->
                <div class="flex flex-col items-center gap-0 w-full max-w-[300px]">
                    <div 
                        v-for="link in navLinks" 
                        :key="link.name"
                        class="flex flex-col items-center w-full"
                    >
                        <div class="flex items-center justify-center gap-4 py-4 w-full">
                            <Link 
                                :href="link.href"
                                @click="isMobileMenuOpen = false"
                                class="text-2xl font-black uppercase tracking-[0.2em] hover:text-safari-gold transition-colors text-center"
                                :class="isCurrentRoute(link.href) ? 'text-safari-gold' : 'text-white'"
                            >
                                {{ link.name }}
                            </Link>
                            <button 
                                v-if="link.sections" 
                                @click="toggleMobileLink(link.name)"
                                class="p-1 text-white/50 hover:text-safari-gold transition-all"
                                :class="{ 'rotate-180 text-safari-gold': expandedMobileLinks[link.name] }"
                            >
                                <ChevronDown class="w-4 h-4" />
                            </button>
                        </div>
                        
                        <div 
                            v-if="link.sections && expandedMobileLinks[link.name]" 
                            class="flex flex-col items-center gap-4 pt-4 pb-4 border-y border-white/5 w-full bg-white/[0.02] animate-in zoom-in-95 duration-300"
                        >
                            <Link 
                                v-for="section in link.sections" 
                                :key="section.hash"
                                :href="route('home') + section.hash"
                                @click="isMobileMenuOpen = false"
                                class="text-[10px] font-black uppercase tracking-[0.2em] text-white/50 hover:text-safari-gold transition-colors py-1"
                            >
                                - {{ section.name }} -
                            </Link>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Intelligence Context -->
                <div class="mt-16 pt-12 border-t border-white/5 space-y-8 text-center animate-in fade-in slide-in-from-bottom-4 duration-1000">
                    <Link :href="route('dashboard')" v-if="user" class="block text-xl font-black uppercase tracking-[0.2em] text-safari-gold tracking-widest decoration-1 underline decoration-safari-gold/30">My Command Center</Link>
                    
                    <div class="space-y-4">
                        <p class="text-[10px] font-black tracking-[0.4em] text-white/20 uppercase opacity-100">// OPERATIONAL QUOTE</p>
                        <p class="text-[9px] font-light italic text-white/20 max-w-[200px] leading-relaxed mx-auto uppercase">
                           "The king does not look behind him. He knows the pride follows."
                        </p>
                    </div>

                    <div class="flex flex-col items-center gap-3 opacity-40">
                         <div class="h-[60px] w-[1px] bg-gradient-to-b from-safari-gold/50 to-transparent"></div>
                         <p class="text-[8px] font-black tracking-[0.5em] text-white/80 uppercase">Big Simba Command // Alpha</p>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Page Content -->
        <main id="main-content">
            <slot />
        </main>

        <!-- Consistent Footer -->
        <SimbaFooter />
        <FloatingActions />
        <Toast />
    </div>
</template>

<style scoped>
.font-sans {
    font-family: 'Outfit', sans-serif;
}
</style>
