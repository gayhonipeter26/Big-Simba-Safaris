<script setup lang="ts">
import SimbaLogo from '@/components/SimbaLogo.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
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
import CurrencySwitcher from '@/components/CurrencySwitcher.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItem, NavItem } from '@/types';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { Compass, LayoutGrid, MapPin, Menu, Search, Newspaper, MessageSquare, Camera, ImageIcon, Bell, Settings, Users, Car, Phone, Info, CheckCircle, AlertTriangle } from 'lucide-vue-next';

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

const notifications = ref([
    { id: 1, title: 'Mission Approved', message: 'Your expedition to Maasai Mara has been cleared by Command.', type: 'success', time: '2H AGO', icon: CheckCircle },
    { id: 2, title: 'Tactical Update', message: 'Seasonal weather shift detected in Tsavo Sector. Recon advised.', type: 'info', time: '5H AGO', icon: Info },
    { id: 3, title: 'Fleet Alert', message: 'Assigned 4x4 Land Cruiser in maintenance. Alternate asset deployed.', type: 'warning', time: '1D AGO', icon: AlertTriangle }
]);

const unreadCount = computed(() => notifications.value.length);
</script>

<template>
    <nav class="fixed top-0 left-0 right-0 z-[100] bg-black/80 backdrop-blur-2xl border-b border-white/10 px-4 md:px-10 py-2 md:py-3 select-none">
        <div class="max-w-[1800px] mx-auto flex items-center justify-between">
            <div class="flex items-center gap-6 md:gap-10">
                <Link :href="route('dashboard')" class="hover:scale-105 transition-transform shrink-0" aria-label="Big Simba Safaris Dashboard">
                    <SimbaLogo aria-hidden="true" />
                </Link>

                <!-- Site Identity & Regional Context -->
                <div class="hidden lg:flex items-center gap-8 animate-in slide-in-from-left duration-700">
                    <div class="flex flex-col">
                        <Link :href="route('dashboard')" class="flex items-center gap-3 group/brand">
                             <span class="text-sm font-black tracking-[0.4em] uppercase text-safari-gold select-none group-hover/brand:drop-shadow-[0_0_10px_rgba(202,138,4,0.3)] transition-all">BIG SIMBA SAFARIS</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Dashboard Nav -->
            <div class="hidden lg:flex items-center gap-6 md:gap-8 border-l border-white/10 pl-6 md:pl-8">
                <Link 
                    v-for="item in mainNavItems" 
                    :key="item.title" 
                    :href="item.url"
                    prefetch
                    :aria-label="`Navigate to ${item.title}`"
                    class="text-[10px] lg:text-xs xl:text-sm font-black uppercase tracking-[0.2em] xl:tracking-[0.3em] transition-all hover:text-safari-gold flex items-center gap-2"
                    :class="isCurrentRoute(item.url) ? 'text-safari-gold opacity-100' : 'opacity-60 text-white/60 hover:opacity-100'"
                >
                    <component :is="item.icon" class="w-3.5 h-3.5 xl:w-4 xl:h-4" aria-hidden="true" />
                    <span>{{ item.title }}</span>
                </Link>

                <div class="h-6 w-[1px] bg-white/5 mx-2"></div>

                <div class="flex items-center gap-3">
                    <!-- Contact Uplink -->
                    <a href="tel:+254758544040" class="hidden lg:flex items-center gap-3 px-5 py-2.5 bg-safari-gold/10 border border-safari-gold/20 rounded-full hover:bg-safari-gold transition-all group/phone whitespace-nowrap">
                         <Phone class="w-4 h-4 text-safari-gold group-hover/phone:text-black transition-colors" />
                         <span class="text-[10px] font-black tracking-[0.25em] text-white/90 group-hover/phone:text-black transition-colors uppercase">+254 758 544 040</span>
                    </a>

                    <div class="h-6 w-[1px] bg-white/5 mx-2 hidden lg:block"></div>

                    <!-- Currency Terminal -->
                    <div class="hidden xl:block">
                         <CurrencySwitcher />
                    </div>

                    <div class="h-6 w-[1px] bg-white/5 mx-2 hidden xl:block"></div>

                    <!-- Functionalized Notifications -->
                    <DropdownMenu>
                         <DropdownMenuTrigger as-child>
                             <button 
                                class="relative size-10 rounded-full flex items-center justify-center text-white/30 hover:text-safari-gold hover:bg-safari-gold/10 transition-all group"
                                aria-label="Open Wild Alerts Center"
                             >
                                 <Bell class="w-5 h-5 group-hover:scale-110 transition-transform" aria-hidden="true" />
                                 <span v-if="unreadCount > 0" class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-black shadow-[0_0_10px_rgba(239,68,68,0.5)]"></span>
                             </button>
                         </DropdownMenuTrigger>
                         <DropdownMenuContent align="end" class="w-80 bg-[#080808]/95 backdrop-blur-3xl border-white/5 p-4 shadow-3xl text-white">
                             <DropdownMenuLabel class="text-[10px] font-black tracking-[0.5em] uppercase text-safari-gold p-4 px-2">// WILD ALERTS CENTER</DropdownMenuLabel>
                             <DropdownMenuSeparator class="bg-white/5 mx-2" />
                             <div class="max-h-[400px] overflow-y-auto py-2">
                                 <DropdownMenuItem v-for="note in notifications" :key="note.id" class="p-4 rounded-sm hover:bg-white/[0.03] flex items-start gap-4 cursor-pointer group mb-1 border-b border-white/[0.02] last:border-0">
                                     <div class="mt-1 w-8 h-8 rounded-sm flex items-center justify-center bg-black border border-white/5 group-hover:border-safari-gold/30 transition-all">
                                         <component :is="note.icon" class="w-4 h-4 opacity-40 group-hover:opacity-100 transition-opacity" :class="{ 'text-green-500': note.type === 'success', 'text-safari-gold': note.type === 'info', 'text-orange-500': note.type === 'warning' }" />
                                     </div>
                                     <div class="flex-1 space-y-1">
                                         <div class="flex items-center justify-between">
                                             <p class="text-[10px] font-black uppercase tracking-widest text-white/80 group-hover:text-safari-gold transition-colors">{{ note.title }}</p>
                                             <span class="text-[8px] font-black opacity-20 group-hover:opacity-40">{{ note.time }}</span>
                                         </div>
                                         <p class="text-[11px] leading-relaxed text-white/40 group-hover:text-white/60 line-clamp-2 italic font-light">{{ note.message }}</p>
                                     </div>
                                 </DropdownMenuItem>
                             </div>
                             <DropdownMenuSeparator class="bg-white/5 mx-2 mt-2" />
                             <DropdownMenuItem class="justify-center py-4 text-[9px] font-black uppercase tracking-[0.3em] text-white/20 hover:text-safari-gold transition-colors cursor-pointer">CLEAR ALL INTEL LOGS</DropdownMenuItem>
                         </DropdownMenuContent>
                    </DropdownMenu>

                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                aria-label="User Account Menu"
                                class="relative size-10 rounded-full p-1 border border-white/5 hover:border-safari-gold group transition-all hover:bg-white/5"
                            >
                                <Avatar class="size-8 overflow-hidden rounded-full border border-white/10 group-hover:border-transparent transition-all">
                                    <AvatarImage :src="auth.user?.avatar" :alt="auth.user?.name || 'Guest Avatar'" />
                                    <AvatarFallback class="bg-neutral-800 font-semibold text-white text-xs">
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
            <!-- Mobile Action Matrix -->
            <div class="lg:hidden flex items-center gap-3">
                  <!-- Tactical Phone Uplink -->
                  <a href="tel:+254758544040" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-white/60 hover:text-safari-gold transition-all mr-2 whitespace-nowrap" aria-label="Tactical Phone Uplink">
                       <Phone class="w-3.5 h-3.5 text-safari-gold" aria-hidden="true" />
                       <span class="inline">+254 758 544 040</span>
                  </a>

                  <!-- Command Recon Alerts -->
                  <div class="flex items-center gap-4 flex-nowrap">
                       <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                 <button 
                                    class="relative flex items-center justify-center text-white/40 hover:text-safari-gold transition-all"
                                    aria-label="Open Wild Alerts Center"
                                 >
                                     <Bell class="w-5 h-5" aria-hidden="true" />
                                     <span v-if="unreadCount > 0" class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border border-black shadow-[0_0_5px_rgba(239,68,68,0.5)]"></span>
                                 </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-80 bg-[#080808]/95 backdrop-blur-3xl border-white/5 p-4 shadow-3xl text-white">
                                 <DropdownMenuLabel class="text-[10px] font-black tracking-[0.5em] uppercase text-safari-gold p-4 px-2">// MOBILE ALERTS</DropdownMenuLabel>
                                 <DropdownMenuSeparator class="bg-white/5 mx-2" />
                                 <div class="max-h-[300px] overflow-y-auto py-2">
                                     <DropdownMenuItem v-for="note in notifications" :key="note.id" class="p-3 rounded-sm hover:bg-white/[0.03] flex items-start gap-4 cursor-pointer group mb-1 border-b border-white/[0.02] last:border-0 text-white">
                                          <div class="flex-1 space-y-1">
                                               <p class="text-[10px] font-black uppercase tracking-widest text-white/80 group-hover:text-safari-gold">{{ note.title }}</p>
                                               <p class="text-[10px] leading-relaxed text-white/40 line-clamp-2 italic font-light">{{ note.message }}</p>
                                          </div>
                                     </DropdownMenuItem>
                                 </div>
                            </DropdownMenuContent>
                       </DropdownMenu>

                       <!-- User Recon Avatar -->
                       <DropdownMenu>
                           <DropdownMenuTrigger as-child>
                               <button class="relative flex items-center justify-center hover:scale-105 transition-transform" aria-label="Operative Dossier">
                                   <Avatar class="size-8 overflow-hidden rounded-full border border-white/10 group-hover:border-safari-gold transition-all">
                                       <AvatarImage :src="auth.user?.avatar" :alt="auth.user?.name" />
                                       <AvatarFallback class="bg-neutral-800 text-[10px] font-black text-white">
                                           {{ getInitials(auth.user?.name || 'G') }}
                                       </AvatarFallback>
                                   </Avatar>
                               </button>
                           </DropdownMenuTrigger>
                           <DropdownMenuContent align="end" class="w-60 bg-black/95 backdrop-blur-3xl border-white/10 text-white shadow-2xl">
                               <UserMenuContent :user="auth.user" />
                           </DropdownMenuContent>
                       </DropdownMenu>

                       <!-- Mission Command Sheet -->
                       <Sheet>
                           <SheetTrigger as-child>
                              <Button variant="ghost" size="icon" aria-label="Open Mission Menu" class="text-white opacity-90 hover:opacity-100 size-9 border border-white/10 rounded-full shrink-0">
                                   <Menu class="w-4 h-4" aria-hidden="true" />
                              </Button>
                          </SheetTrigger>
                          <SheetContent side="right" class="w-full bg-black/95 backdrop-blur-3xl border-l border-white/10 p-8 pt-24 min-h-screen overflow-y-auto">
                               <div class="flex flex-col items-center h-full text-center">
                                  <!-- Tactical Mission Quote (Top) -->
                                  <div class="mb-16 animate-in fade-in slide-in-from-top-4 duration-1000">
                                       <p class="text-[10px] font-black tracking-[0.4em] text-safari-gold mb-4 uppercase">// COMMAND PARAMETERS</p>
                                       <p class="text-xs font-light italic text-white/40 max-w-[240px] leading-relaxed mx-auto">
                                          "Lead with absolute authority. The pride relies on your tactical navigation today."
                                       </p>
                                  </div>

                                  <!-- Centralized Command Matrix -->
                                  <div class="flex flex-col items-center gap-2 w-full max-w-[300px] mb-12">
                                       <Link 
                                           v-for="item in mainNavItems" 
                                           :key="item.title" 
                                           :href="item.url"
                                           prefetch
                                           class="text-2xl font-black uppercase tracking-[0.2em] py-4 transition-all hover:text-safari-gold w-full text-center border-b border-white/5 last:border-0"
                                           :class="isCurrentRoute(item.url) ? 'text-safari-gold' : 'text-white'"
                                       >
                                           {{ item.title }}
                                       </Link>
                                  </div>

                                  <!-- Bottom Intelligence Context -->
                                  <div class="mt-auto pt-12 border-t border-white/5 w-full space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-1000">
                                       <div class="space-y-6">
                                            <div class="flex items-center justify-center gap-4">
                                                 <a href="tel:+254758544040" class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-safari-gold transition-all">
                                                      <Phone class="w-3.5 h-3.5 text-safari-gold" />
                                                      +254 758 544 040
                                                 </a>
                                            </div>
                                            <Link :href="route('logout')" method="post" as="button" class="text-[10px] font-black uppercase tracking-[0.5em] text-red-500 hover:text-white transition-all underline decoration-red-500/30 underline-offset-8">ABORT SESSION</Link>
                                       </div>

                                       <div class="space-y-4 pt-4">
                                            <p class="text-[10px] font-black tracking-[0.4em] text-white/20 uppercase">// ASSET INTEGRITY</p>
                                            <p class="text-[9px] font-light italic text-white/20 max-w-[200px] leading-relaxed mx-auto uppercase">
                                               "The king knows every hill and river. Complete the mission."
                                            </p>
                                       </div>

                                       <div class="flex flex-col items-center gap-3 opacity-40 pb-12">
                                            <div class="h-[60px] w-[1px] bg-gradient-to-b from-safari-gold/50 to-transparent"></div>
                                            <p class="text-[8px] font-black tracking-[0.5em] text-white/80 uppercase">Big Simba Command // Sector Alpha</p>
                                       </div>
                                  </div>
                               </div>
                          </SheetContent>
                       </Sheet>
                  </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
nav {
    font-family: 'Outfit', sans-serif;
}
</style>
