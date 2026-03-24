<script setup lang="ts">
import SimbaLogo from '@/components/SimbaLogo.vue';
import FloatingActions from '@/components/FloatingActions.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import { Search, Menu, X, User, LayoutGrid, Instagram, Twitter } from 'lucide-vue-next';
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
    { name: 'Home', href: route('home') },
    { name: 'Expeditions', href: route('tours.index') },
    { name: 'Destinations', href: route('destinations.index') },
    { name: 'Chronicles', href: route('blog.index') },
];
</script>

<template>
    <div class="min-h-screen bg-[#050505] text-white font-sans selection:bg-safari-gold selection:text-black">
        <!-- Persistent Premium Navigation -->
        <nav 
            class="fixed top-0 left-0 right-0 z-[100] transition-all duration-700 px-8 py-6 md:px-16"
            :class="[
                isScrolled ? 'bg-black/80 backdrop-blur-xl border-b border-white/5 py-4' : 'bg-transparent'
            ]"
        >
            <div class="max-w-[1600px] mx-auto flex items-center justify-between">
                <div class="flex items-center gap-16">
                    <Link :href="route('home')" class="hover:scale-105 transition-transform">
                        <SimbaLogo />
                    </Link>
                    
                    <div class="hidden lg:flex items-center gap-10">
                        <Link 
                            v-for="link in navLinks" 
                            :key="link.name" 
                            :href="link.href"
                            class="text-[10px] font-black tracking-[0.4em] uppercase transition-all hover:text-safari-gold"
                            :class="isCurrentRoute(link.href) ? 'text-safari-gold opacity-100' : 'opacity-60'"
                        >
                            {{ link.name }}
                        </Link>
                    </div>
                </div>

                <div class="flex items-center gap-8">
                    <!-- Nav Actions -->
                    <div class="flex items-center gap-8 border-l border-white/10 pl-8">
                        <template v-if="user">
                            <Link 
                                :href="route('dashboard')"
                                class="group flex items-center gap-3 text-[10px] font-black tracking-[0.3em] uppercase opacity-70 hover:opacity-100 transition-all hover:text-safari-gold"
                            >
                                <LayoutGrid class="w-3.5 h-3.5" />
                                <span>My Pride</span>
                            </Link>

                            <DropdownMenu>
                                <DropdownMenuTrigger :as-child="true">
                                    <Button
                                        variant="ghost"
                                        size="icon"
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
                            <div class="flex items-center gap-6 mr-4 opacity-40">
                                <a href="#" target="_blank" class="hover:text-safari-gold hover:opacity-100 transition-all">
                                    <Instagram class="w-4 h-4" />
                                </a>
                                <a href="#" target="_blank" class="hover:text-safari-gold hover:opacity-100 transition-all">
                                    <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 fill-current" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932 6.064-6.932zm-1.292 19.494h2.039L6.486 3.24H4.298l13.311 17.407z"/>
                                    </svg>
                                </a>
                                <a href="#" target="_blank" class="hover:text-safari-gold hover:opacity-100 transition-all">
                                    <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.525.023c.35 0 .7.127.973.38l.05.048c.099.098.242.155.39.155.15 0 .292-.057.391-.155l.05-.048c.273-.253.623-.38.973-.38s.701.127.974.38l.05.048c.098.098.242.155.39.155.15 0 .293-.057.391-.155l.05-.048c.273-.253.623-.38.973-.38s.701.127.974.38l.05.048c.098.098.241.155.39.155s.292-.057.39-.155l.05-.048c.273-.254.624-.381.974-.381 1.076 0 1.95.874 1.95 1.95V22.05c0 1.076-.874 1.95-1.95 1.95-.35 0-.7-.127-.974-.38l-.05-.048a.553.553 0 0 0-.39-.155.553.553 0 0 0-.39.155l-.05.048c-.273.253-.623.38-.973.38s-.701-.127-.974-.38l-.05-.048c-.098-.098-.242-.155-.39-.155-.15 0-.293.057-.39.155l-.05.048c-.274.253-.624.38-.974.38s-.701-.127-.974-.38l-.05-.048a.553.553 0 0 0-.39-.155.553.553 0 0 0-.39.155l-.05.048c-.273.253-.623.38-.974.38s-.701-.127-.973-.38l-.05-.048a.553.553 0 0 0-.39-.155.553.553 0 0 0-.391.155l-.05.048c-.273.253-.623.38-.973.38-1.076 0-1.95-.874-1.95-1.95V1.973C10.575.897 11.449.023 12.525.023zM21.24 7.2l-.004.004c-.035.035-.083.052-.132.052s-.096-.017-.132-.052l-.004-.004c-.073-.073-.191-.073-.264 0l-.004.004c-.036.035-.083.052-.132.052s-.097-.017-.132-.052l-.004-.004c-.073-.073-.192-.073-.264 0l-.004.004c-.036.036-.084.053-.133.053-.048 0-.096-.017-.131-.053l-.004-.004c-.073-.073-.192-.073-.265 0l-.004.004c-.035.035-.083.052-.132.052s-.096-.017-.132-.052l-.004-.004a.187.187 0 0 0-.264 0l-.004.004c-.036.035-.083.052-.132.052s-.097-.017-.132-.052l-.004-.004c-.073-.073-.191-.073-.264 0l-.004.004c-.036.036-.084.053-.133.053-.048 0-.096-.017-.131-.053l-.004-.004c-.073-.073-.191-.073-.264 0l-.004.004c-.036.036-.084.053-.133.053s-.097-.017-.132-.053l-.004-.004c-.073-.073-.191-.073-.264 0l-.004.004c-.036.036-.084.052-.132.052h-.001c-.048 0-.096-.017-.131-.052l-.004-.004c-.073-.073-.191-.073-.264 0l-.004.004c-.035.035-.083.052-.131.052s-.097-.017-.133-.052l-.004-.004c-.073-.073-.191-.073-.264 0l-.005.004c-.035.035-.083.052-.131.052s-.097-.017-.132-.052l-.005-.004c-.073-.073-.191-.073-.264 0V22.05v.001c0 .03.012.059.034.081l.001.001.05.047c.046.043.109.068.176.068a.243.243 0 0 0 .178-.071l.051-.05c.036-.036.084-.053.132-.053s.096.017.132.053l.051.05c.049.049.115.076.184.076a.257.257 0 0 0 .185-.078l.05-.046c.036-.035.084-.052.132-.052s.096.017.132.052l.05.046c.049.049.115.078.185.078s.136-.029.184-.076l.051-.05c.036-.036.084-.053.132-.053s.096.017.132.053l.051.05c.048.047.114.071.178.071s.13-.025.176-.068l.051-.048c.021-.022.034-.051.034-.081V1.973c0-.03-.013-.059-.034-.081l-.051-.048c-.046-.043-.109-.068-.176-.068a.243.243 0 0 0-.178.071l-.051.05c-.036.036-.084.053-.132.053s-.096-.017-.132-.053l-.051-.05c-.049-.049-.115-.076-.184-.076a.257.257 0 0 0-.185.078l-.05.046c-.036.035-.084.052-.132.052s-.096-.017-.132.052l-.05.046c-.049.049-.115.078-.185.078s-.136-.029-.184-.076l-.051-.05c-.036-.036-.084-.053-.132-.053s-.096.017-.132.053l-.051.05c-.048.047-.114.071-.178.071s-.13-.025-.176-.068l-.051-.048c-.021-.022-.034-.051-.034-.081v20.077l.004-.004c.036-.035.084-.052.132-.052s.097.017.133.052l.004.004c.073.073.191.073.264 0l.005-.004c.035-.035.083-.052.131-.052s.097.017.132.052l.005-.004c.073.073.191.073.264 0l.004-.004c.036-.035.084-.052.132-.052s.096.017.132.052l.004.004c.073.073.191.073.264 0l.004-.004c.036-.036.084-.053.133-.053.048 0 .096.017.131.053l.004.004c.073.073.191.073.264 0l.004-.004c.036-.036.084-.053.132-.053s.097.017.133.053l.004.004c.073.073.191.073.264 0l.004-.004c.036-.035.084-.052.132-.052s.097.017.133.052l.004.004c.073.073.192.073.264 0l.004-.004c.036-.036.084-.053.133-.053.048 0 .096.017.132.053l.004.004c.073.073.191.073.264 0l.004-.004c.036-.035.084-.052.132-.052s.096.017.132.052l.004.004c.073.073.191.073.264 0z"/>
                                    </svg>
                                </a>
                            </div>
                            <Link :href="route('register')" class="px-8 py-3 bg-safari-gold/10 border border-safari-gold/30 text-safari-gold text-[10px] font-black uppercase tracking-[0.3em] rounded-full hover:bg-safari-gold hover:text-black transition-all">Join Us</Link>
                        </template>
                    </div>

                    <!-- Mobile Toggle -->
                    <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden text-white opacity-60 hover:opacity-100">
                        <Menu v-if="!isMobileMenuOpen" class="w-6 h-6" />
                        <X v-else class="w-6 h-6" />
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
            <div v-if="isMobileMenuOpen" class="fixed inset-0 z-[90] bg-black/95 backdrop-blur-3xl pt-32 px-12 flex flex-col gap-12 lg:hidden border-b border-white/5">
                <div class="flex flex-col gap-8">
                    <Link 
                        v-for="link in navLinks" 
                        :key="link.name" 
                        :href="link.href"
                        @click="isMobileMenuOpen = false"
                        class="text-2xl font-black uppercase tracking-widest hover:text-safari-gold transition-colors"
                        :class="page.url === link.href ? 'text-safari-gold' : 'text-white/40'"
                    >
                        {{ link.name }}
                    </Link>
                </div>
                
                <div class="pt-12 border-t border-white/5 space-y-6">
                    <Link :href="route('dashboard')" v-if="user" class="block text-xl font-black uppercase tracking-widest text-safari-gold">Back to Pride</Link>
                    <template v-else>
                        <Link :href="route('register')" class="block text-xl font-black uppercase tracking-widest text-safari-gold">Join Us</Link>
                        <div class="flex items-center gap-8 pt-4 opacity-60">
                            <a href="#" target="_blank" class="hover:text-safari-gold"><Instagram class="w-6 h-6" /></a>
                            <a href="#" target="_blank" class="hover:text-safari-gold">
                                <svg viewBox="0 0 24 24" class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932 6.064-6.932zm-1.292 19.494h2.039L6.486 3.24H4.298l13.311 17.407z"/>
                                </svg>
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </transition>

        <!-- Page Content -->
        <slot />

        <!-- Consistent Footer -->
        <footer class="bg-[#020202] border-t border-white/5 pt-32 pb-16 px-8 md:px-16">
            <div class="max-w-[1600px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-20">
                <div class="md:col-span-2 space-y-10">
                    <SimbaLogo />
                    <p class="text-sm opacity-40 leading-relaxed font-light max-w-sm">
                        Born from the savanna, we provide the most authentic, luxurious, and impactful safari experiences in the heart of Kenya. Every journey supports local conservation efforts.
                    </p>
                    <div class="flex items-center gap-6 opacity-40">
                        <span class="text-[9px] font-black uppercase tracking-widest">Instagram</span>
                        <span class="text-[9px] font-black uppercase tracking-widest">Wildshots</span>
                        <span class="text-[9px] font-black uppercase tracking-widest">Expeditions</span>
                    </div>
                </div>

                <div class="space-y-8">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold">Expeditions</h4>
                    <div class="flex flex-col gap-4 text-[10px] font-bold uppercase tracking-widest opacity-40">
                        <Link :href="route('tours.index')">Maasai Mara</Link>
                        <Link :href="route('tours.index')">Amboseli Pride</Link>
                        <Link :href="route('tours.index')">Tsavo Dust</Link>
                        <Link :href="route('tours.index')">Samburu Sands</Link>
                    </div>
                </div>

                <div class="space-y-8">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold">The Pride</h4>
                    <div class="flex flex-col gap-4 text-[10px] font-bold uppercase tracking-widest opacity-40">
                        <Link :href="route('home') + '#about'">Our Story</Link>
                        <Link :href="route('home') + '#contact'">Reach Out</Link>
                        <Link :href="route('blog.index')">Chronicles</Link>
                        <Link :href="route('register')">Become Member</Link>
                    </div>
                </div>

                <div class="space-y-8">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold">Comms</h4>
                    <div class="flex flex-col gap-4 text-[10px] font-bold uppercase tracking-widest opacity-40">
                         <a href="mailto:info@bigsimbasafaris.co.ke" class="hover:text-safari-gold transition-colors">info@bigsimbasafaris.co.ke</a>
                         <a href="tel:+254726159925" class="hover:text-safari-gold transition-colors">+254 726 159 925</a>
                         <span>Nairobi, Kenya</span>
                    </div>
                </div>
            </div>

            <div class="max-w-[1600px] mx-auto mt-32 pt-16 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8 opacity-20">
                <p class="text-[9px] font-bold uppercase tracking-[0.3em]">© 2026 Big Simba Safaris. All Wild Rights Reserved.</p>
                <div class="flex items-center gap-12 text-[9px] font-bold uppercase tracking-[0.3em]">
                    <span>Terms of Pride</span>
                    <span>Wild Privacy</span>
                    <span>Conservation Pact</span>
                </div>
            </div>
        </footer>
        <FloatingActions />
    </div>
</template>

<style scoped>
.font-sans {
    font-family: 'Outfit', sans-serif;
}
</style>
