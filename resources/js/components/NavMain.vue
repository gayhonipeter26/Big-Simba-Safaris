<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubItem, SidebarMenuSubButton } from '@/components/ui/sidebar';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronRight, type LucideIcon } from 'lucide-vue-next';
import type { Component } from 'vue';

interface NavItem {
    title: string;
    url: string;
    icon?: Component | LucideIcon;
    isActive?: boolean;
    items?: {
        title: string;
        url: string;
    }[];
}

defineProps<{
    items: NavItem[];
}>();

const page = usePage<any>();

const isUrlActive = (url: string) => {
    return page.url === url || (url !== '#' && page.url.startsWith(url));
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Strategic Command</SidebarGroupLabel>
        <SidebarMenu>
            <Collapsible
                v-for="item in items"
                :key="item.title"
                as-child
                :default-open="item.isActive || item.items?.some(sub => isUrlActive(sub.url))"
                class="group/collapsible"
            >
                <SidebarMenuItem>
                    <CollapsibleTrigger as-child>
                        <SidebarMenuButton :tooltip="item.title" :is-active="isUrlActive(item.url)">
                             <Link v-if="!item.items?.length" :href="item.url" class="flex items-center w-full">
                                <component :is="item.icon" v-if="item.icon" />
                                <span>{{ item.title }}</span>
                            </Link>
                            <template v-else>
                                <component :is="item.icon" v-if="item.icon" />
                                <span>{{ item.title }}</span>
                                <ChevronRight class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                            </template>
                        </SidebarMenuButton>
                    </CollapsibleTrigger>
                    <CollapsibleContent v-if="item.items?.length">
                        <SidebarMenuSub>
                            <SidebarMenuSubItem v-for="subItem in item.items" :key="subItem.title">
                                <SidebarMenuSubButton as-child :is-active="isUrlActive(subItem.url)">
                                    <Link :href="subItem.url">
                                        <span>{{ subItem.title }}</span>
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </CollapsibleContent>
                </SidebarMenuItem>
            </Collapsible>
        </SidebarMenu>
    </SidebarGroup>
</template>
