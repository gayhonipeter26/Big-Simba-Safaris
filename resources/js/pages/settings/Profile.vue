<script setup lang="ts">
import { TransitionRoot } from '@headlessui/vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { Camera, Mail, User as UserIcon } from 'lucide-vue-next';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    className?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Wild Pulse Settings',
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as any;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};

const getInitials = (name: string) => {
    return name.split(' ').map(n => n[0]).join('').toUpperCase();
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Safari Identity Settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-12">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <!-- Avatar Display section -->
                    <div class="relative group">
                        <div class="w-24 h-24 md:w-32 md:h-32 bg-black/40 border-2 border-safari-gold/20 rounded-full overflow-hidden flex items-center justify-center shadow-2xl transition-all group-hover:border-safari-gold/50">
                            <img v-if="user.avatar" :src="user.avatar" class="w-full h-full object-cover grayscale-[0.3] group-hover:grayscale-0 transition-all" :alt="user.name" />
                            <div v-else class="text-4xl font-black text-safari-gold/20">{{ getInitials(user.name) }}</div>
                        </div>
                        
                        <!-- Overlay for "Syncing" or "Edit" -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 rounded-full">
                            <Camera class="w-6 h-6 text-safari-gold" />
                        </div>
                    </div>

                    <div class="space-y-2 flex-1 pt-2">
                        <h2 class="text-2xl font-black uppercase tracking-widest text-white">Safari Identity</h2>
                        <p class="text-xs font-black uppercase tracking-[0.3em] opacity-40">Your profile information is current tracked as:</p>
                        <div v-if="user.google_id" class="inline-flex items-center gap-2 px-3 py-1 bg-white/5 border border-white/10 rounded-full">
                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Synced with Google</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <HeadingSmall title="Identity Intel" description="Maintain your identification records within the pride." />

                    <form @submit.prevent="submit" class="space-y-8 max-w-2xl">
                        <div class="grid gap-3">
                            <Label for="name" class="text-[10px] font-black uppercase tracking-widest opacity-40 flex items-center gap-2">
                                <UserIcon class="w-3 h-3" /> Tracking Name
                            </Label>
                            <Input id="name" class="mt-1 block w-full bg-white/5 border-white/10 focus:border-safari-gold/50 text-sm font-bold tracking-wider" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="grid gap-3">
                            <Label for="email" class="text-[10px] font-black uppercase tracking-widest opacity-40 flex items-center gap-2">
                                <Mail class="w-3 h-3" /> Communication Frequency
                            </Label>
                            <Input
                                id="email"
                                type="email"
                                class="mt-1 block w-full bg-white/5 border-white/10 focus:border-safari-gold/50 text-sm font-bold tracking-wider"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                placeholder="Email address"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div v-if="mustVerifyEmail && !user.email_verified_at">
                            <p class="mt-2 text-sm text-neutral-800">
                                Your email address is unverified.
                                <Link
                                    :href="route('verification.send')"
                                    method="post"
                                    as="button"
                                    class="focus:outline-hidden rounded-md text-sm text-neutral-600 underline hover:text-neutral-900 focus:ring-2 focus:ring-offset-2"
                                >
                                    Click here to re-send the verification email.
                                </Link>
                            </p>

                            <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                                A new verification link has been sent to your email address.
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <Button :disabled="form.processing" class="bg-safari-gold/80 hover:bg-safari-gold text-black font-black uppercase text-[10px] tracking-widest px-8">Update Identity</Button>

                            <TransitionRoot
                                :show="form.recentlySuccessful"
                                enter="transition ease-in-out"
                                enter-from="opacity-0"
                                leave="transition ease-in-out"
                                leave-to="opacity-0"
                            >
                                <p class="text-[10px] font-black uppercase tracking-widest text-safari-gold animate-pulse">Records Updated.</p>
                            </TransitionRoot>
                        </div>
                    </form>
                </div>

                <div class="pt-12 border-t border-white/5">
                    <DeleteUser />
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>

