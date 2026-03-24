<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import type { User } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LogOut, Settings, LayoutGrid, MapPin, Camera } from 'lucide-vue-next';

interface Props {
    user: User | null;
}

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup v-if="user">
        <DropdownMenuItem :as-child="true" v-if="!user.is_admin">
            <Link class="block w-full hover:text-safari-gold transition-colors" :href="route('dashboard')" as="button">
                <LayoutGrid class="inline-block mr-2 h-4 w-4" />
                Pride Center
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true" v-if="!user.is_admin">
            <Link class="block w-full hover:text-safari-gold transition-colors" :href="route('user.tours.index')" as="button">
                <MapPin class="inline-block mr-2 h-4 w-4" />
                Expeditions
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true" v-if="!user.is_admin">
            <Link class="block w-full hover:text-safari-gold transition-colors" :href="route('user.gallery.index')" as="button">
                <Camera class="inline-block mr-2 h-4 w-4" />
                Wild Gallery
            </Link>
        </DropdownMenuItem>

        <DropdownMenuItem :as-child="true" v-if="user.is_admin">
            <Link class="block w-full hover:text-safari-gold transition-colors" :href="route('admin.dashboard')" as="button">
                <LayoutGrid class="inline-block mr-2 h-4 w-4" />
                Operation Center
            </Link>
        </DropdownMenuItem>

        <DropdownMenuSeparator v-if="user" />

        <DropdownMenuItem :as-child="true">
            <Link class="block w-full hover:text-safari-gold transition-colors" :href="route('profile.edit')" as="button">
                <Settings class="inline-block mr-2 h-4 w-4" />
                Settings
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuGroup v-else>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full text-safari-gold font-bold" :href="route('login')" as="button">
                Log In to Pulse
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full" :href="route('register')" as="button">
                Join the Pride
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem v-if="user" :as-child="true">
        <Link class="block w-full text-red-500" method="post" :href="route('logout')" as="button">
            <LogOut class="mr-2 h-4 w-4" />
            Log out
        </Link>
    </DropdownMenuItem>
</template>
