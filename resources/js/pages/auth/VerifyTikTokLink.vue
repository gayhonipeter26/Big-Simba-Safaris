<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, ShieldAlert } from 'lucide-vue-next';

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('tiktok.link'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase 
        title="Verify TikTok Connection" 
        description="Provide your email to link your TikTok account or complete your registration."
    >
        <Head title="Verify TikTok Link" />

        <div class="mb-8 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-safari-gold/10 rounded-full flex items-center justify-center mb-6">
                <ShieldAlert class="w-8 h-8 text-safari-gold" />
            </div>
            <p class="text-sm font-black uppercase tracking-[0.3em] opacity-80 mb-2">Connect Your Account</p>
            <p class="text-neutral-400 text-xs max-w-sm">
                If you already have a Big Simba Safaris account, enter its email and password to link it. If you are new, enter your email and leave the password blank to register a new account.
            </p>
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email" class="text-sm font-black uppercase tracking-widest opacity-90">Email Address</Label>
                    <Input 
                        id="email" 
                        type="email" 
                        required 
                        autofocus
                        v-model="form.email" 
                        placeholder="email@example.com" 
                        class="bg-white/5 border-white/10" 
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="text-sm font-black uppercase tracking-widest opacity-90 flex items-center justify-between">
                        <span>Account Password</span>
                        <span class="text-[10px] text-neutral-400 font-normal lowercase tracking-normal">(only required to link existing accounts)</span>
                    </Label>
                    <Input 
                        id="password" 
                        type="password" 
                        v-model="form.password" 
                        placeholder="Enter password (optional)" 
                        class="bg-white/5 border-white/10" 
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <Button type="submit" class="mt-4 w-full bg-safari-gold hover:bg-white text-black text-sm font-black uppercase tracking-[0.2em]" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Verify & Connect
                </Button>
            </div>

            <a :href="route('login')" class="text-center text-sm font-black uppercase tracking-widest opacity-80 hover:opacity-100 transition-all">
                Cancel and back to login
            </a>
        </form>
    </AuthBase>
</template>
