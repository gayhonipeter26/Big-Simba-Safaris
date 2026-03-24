<script setup lang="ts">
import SimbaLogo from '@/components/SimbaLogo.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItem, NavItem } from '@/types';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { Compass, LayoutGrid, MapPin, Menu, Search, Newspaper, MessageSquare, Camera, ImageIcon, Bell, Settings, Users, Car } from 'lucide-vue-next';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage<any>();
const auth = computed(() => page.props.auth);
const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const isCurrentRoute = (routeUrl: string) => {
    const currentPath = page.url.split('?')[0];
    let targetPath = routeUrl.split('?')[0];
    if (targetPath.startsWith('http')) {
        try {
            targetPath = new URL(targetPath).pathname;
        } catch (e) {}
    }
    if (targetPath === '/') return currentPath === '/';
    return currentPath === targetPath || currentPath.startsWith(targetPath + '/');
};

const mainNavItems = computed<NavItem[]>(() => {
    if (auth.value?.user?.is_admin) {
        return [
            { title: 'Operation Center', url: route('admin.dashboard'), icon: LayoutGrid },
            { title: 'Safaris', url: route('admin.tours.index'), icon: MapPin },
            { title: 'Bookings', url: route('admin.bookings.index'), icon: Compass },
            { title: 'Destinations', url: route('admin.destinations.index'), icon: Compass },
            { title: 'Gallery', url: route('admin.gallery.index'), icon: Camera },
            { title: 'Chronicles', url: route('admin.blog.index'), icon: Newspaper },
            { title: 'Users', url: route('admin.users.index'), icon: Users },
            { title: 'Logistics', url: route('admin.fleet.index'), icon: Car },
        ];
    }
    return [
        { title: 'Pride Center', url: route('dashboard'), icon: LayoutGrid },
        { title: 'Expeditions', url: route('user.tours.index'), icon: MapPin },
        { title: 'Destinations', url: route('user.destinations.index'), icon: Compass },
        { title: 'Gallery', url: route('user.gallery.index'), icon: Camera },
        { title: 'Chronicles', url: route('user.blog.index'), icon: Newspaper },
        { title: 'Logistics', url: route('fleet-hire.index'), icon: Car },
    ];

});
</script>

<template>
    <nav 
        class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500 px-6 py-4"
        :class="[
            isScrolled ? 'bg-black/90 backdrop-blur-2xl border-b border-white/5 py-4' : 'bg-transparent'
        ]"
    >
        <div class="max-w-[1800px] mx-auto flex items-center justify-between">
            <div class="flex items-center gap-12">
                <Link :href="route('dashboard')" class="hover:scale-105 transition-transform flex items-center gap-4 group">
                    <div class="w-10 h-10 bg-safari-gold rounded-full flex items-center justify-center group-hover:shadow-[0_0_20px_rgba(202,138,4,0.4)] transition-all">
                        <SimbaLogo :show-text="false" class="scale-50 fill-black" />
                    </div>
                </Link>

                <!-- Site Identity & User Context -->
                <Link :href="route('dashboard')" class="hidden md:flex items-center gap-6 animate-in slide-in-from-left duration-700 hover:opacity-80 transition-opacity group/brand">
                    <span class="text-[11px] font-black tracking-[0.4em] uppercase text-safari-gold select-none group-hover/brand:drop-shadow-[0_0_10px_rgba(202,138,4,0.3)] transition-all">BIG SIMBA SAFARIS</span>
                    <div class="h-4 w-[1px] bg-white/10 shadow-[0_0_10px_rgba(255,255,255,0.05)]"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-safari-gold/40 animate-pulse"></div>
                        <span class="text-[10px] font-black tracking-[0.2em] uppercase text-white/30 italic truncate max-w-[150px] group-hover/brand:text-white/60 transition-colors">{{ auth.user?.name }}</span>
                    </div>
                </Link>
            </div>

            <!-- Dashboard Nav -->
            <div class="hidden lg:flex items-center gap-8">
                <Link 
                    v-for="item in mainNavItems" 
                    :key="item.title" 
                    :href="item.url"
                    class="text-[10px] font-black tracking-[0.3em] uppercase transition-all px-4 py-2 rounded-full flex items-center gap-3 relative group"
                    :class="isCurrentRoute(item.url) ? 'text-safari-gold' : 'text-white/60 hover:text-white'"
                >
                    <component :is="item.icon" class="w-3.5 h-3.5" :class="isCurrentRoute(item.url) ? 'opacity-100' : 'opacity-40 group-hover:opacity-100'" />
                    <span>{{ item.title }}</span>
                    <span v-if="isCurrentRoute(item.url)" class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-4 h-0.5 bg-safari-gold rounded-full shadow-[0_0_10px_rgba(202,138,4,0.8)]"></span>
                </Link>

                <div class="h-6 w-[1px] bg-white/10 mx-4"></div>

                <div class="flex items-center gap-4">
                    <TooltipProvider :delay-duration="0">
                        <Tooltip>
                            <TooltipTrigger>
                                <Button variant="ghost" size="icon" class="text-white/40 hover:text-safari-gold hover:bg-safari-gold/10 rounded-full transition-all">
                                    <Bell class="w-5 h-5" />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent class="bg-black border-white/10 text-white">Wild Alerts</TooltipContent>
                        </Tooltip>
                    </TooltipProvider>

                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative size-10 rounded-full p-1 border border-white/5 hover:border-safari-gold/50 transition-all hover:bg-white/5"
                            >
                                <Avatar class="size-8 overflow-hidden rounded-full">
                                    <AvatarImage :src="auth.user?.avatar" :alt="auth.user?.name || 'Guest'" />
                                    <AvatarFallback class="bg-neutral-800 font-semibold text-white text-[10px]">
                                        {{ getInitials(auth.user?.name || 'G') }}
                                    </AvatarFallback>
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-60 bg-black/95 backdrop-blur-3xl border-white/10 text-white shadow-2xl">
                            <UserMenuContent :user="auth.user" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>

            <!-- Mobile Action -->
            <div class="lg:hidden">
                 <Sheet>
                    <SheetTrigger as-child>
                        <Button variant="ghost" size="icon" class="text-white opacity-60 hover:opacity-100">
                             <Menu class="w-6 h-6" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="right" class="w-[300px] bg-black border-l border-white/10 p-10 pt-20">
                         <div class="flex flex-col gap-10">
                            <Link 
                                v-for="item in mainNavItems" 
                                :key="item.title" 
                                :href="item.url"
                                class="text-xl font-black uppercase tracking-widest"
                                :class="isCurrentRoute(item.url) ? 'text-safari-gold' : 'text-white/40'"
                            >
                                {{ item.title }}
                            </Link>
                            <div class="h-[1px] bg-white/5 w-full"></div>
                            <Link :href="route('logout')" method="post" as="button" class="text-left text-xl font-black uppercase tracking-widest text-red-500/60">Logout</Link>
                         </div>
                    </SheetContent>
                 </Sheet>
            </div>
        </div>
    </nav>
</template>

<style scoped>
nav {
    font-family: 'Outfit', sans-serif;
}
</style>

