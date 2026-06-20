<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, MapPin, Compass, Users, Newspaper, MessageSquare, Settings, Camera, Car, Info, Mail, Layers } from 'lucide-vue-next';
import { computed, type HTMLAttributes } from 'vue';
import AppLogo from './AppLogo.vue';

defineProps<{
    class?: HTMLAttributes['class'];
}>();

const page = usePage<any>();
const user = computed(() => page.props.auth.user);
const isAdminSection = computed(() => page.url.startsWith('/admin'));

const mainNavItems = computed(() => {
    if (user.value?.is_admin || isAdminSection.value) {
        return [
            {
                title: 'Operation Center',
                url: route('admin.dashboard'),
                icon: LayoutGrid,
            },
            {
                title: 'Core Operations',
                url: '#',
                icon: Compass,
                items: [
                    { title: 'Strategic Timeline', url: route('admin.strategic-events.index') },
                    { title: 'Managed Safaris', url: route('admin.tours.index') },
                    { title: 'Wild Destinations', url: route('admin.destinations.index') },
                    { title: 'Safari Pipeline', url: route('admin.tour-enquiries.index') },
                ],
            },
            {
                title: 'Logistics & Assets',
                url: '#',
                icon: Car,
                items: [
                    { title: 'Fleet Management', url: route('admin.fleet.index') },
                    { title: 'Hiring Orders', url: route('admin.fleet-hires.index') },
                    { title: 'Extended Services', url: route('admin.services.index') },
                ],
            },
            {
                title: 'Intelligence & Comms',
                url: '#',
                icon: MessageSquare,
                items: [
                    { title: 'Guest Inquiries', url: route('admin.contact-messages.index') },
                    { title: 'Strategic Reviews', url: route('admin.reviews.index') },
                    { title: 'Safari Chronicles', url: route('admin.blog.index') },
                    { title: 'Safari Story', url: route('admin.about.index') },
                ],
            },
            {
                title: 'Visual Logistics',
                url: '#',
                icon: Camera,
                items: [
                    { title: 'Wild Gallery', url: route('admin.gallery.index') },
                    { title: 'Hero Recon Slides', url: route('admin.hero-slides.index') },
                ],
            },
            {
                title: 'Personnel & Target',
                url: route('admin.users.index'),
                icon: Users,
            },
        ];
    }
    return [
        {
            title: 'My Bookings',
            url: route('dashboard'),
            icon: LayoutGrid,
        },
    ];
});

const footerNavItems = computed(() => {
    if (user.value?.is_admin || isAdminSection.value) {
        return [];
    }
    return [
         {
            title: 'Settings',
            url: route('profile.edit'),
            icon: Settings,
        },
    ];
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" :class="$props.class">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
