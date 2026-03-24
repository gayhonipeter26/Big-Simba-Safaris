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
                title: 'Safari Story',
                url: route('admin.about.index'),
                icon: Info,
            },
            {
                title: 'Managed Safaris',
                url: route('admin.tours.index'),
                icon: MapPin,
            },
            {
                title: 'Safari Pipeline',
                url: route('admin.bookings.index'),
                icon: Compass,
            },
            {
                title: 'Wild Destinations',
                url: route('admin.destinations.index'),
                icon: MapPin,
            },
            {
                title: 'Platform Users',
                url: route('admin.users.index'),
                icon: Users,
            },
            {
                title: 'Wild Gallery',
                url: route('admin.gallery.index'),
                icon: Camera,
            },
            {
                title: 'Strategic Services',
                url: '#',
                icon: Layers,
                items: [
                    { title: 'Fleet Management', url: route('admin.fleet.index') },
                    { title: 'Hiring Orders', url: route('admin.fleet-hires.index') },
                    { title: 'Extended Services', url: route('admin.services.index') },
                ],
            },
            {
                title: 'Safari Chronicles',
                url: route('admin.blog.index'),
                icon: Newspaper,
            },
            {
                title: 'Hero Recon',
                url: route('admin.hero-slides.index'),
                icon: LayoutGrid,
            },
            {
                title: 'Strategic Reviews',
                url: route('admin.reviews.index'),
                icon: MessageSquare,
            },
            {
                title: 'Guest Inquiries',
                url: route('admin.contact-messages.index'),
                icon: Mail,
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
