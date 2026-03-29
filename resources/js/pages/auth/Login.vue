<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const isGoogleLoading = ref(false);
const loginWithGoogle = () => {
    isGoogleLoading.value = true;
    window.location.href = route('google.redirect');
};

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const showNativeForm = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase 
        :title="showNativeForm ? 'Sign in with Email' : 'Join the Pride'" 
        :description="showNativeForm ? 'Enter your credentials to access your account' : 'Access your safaris through your favorite platforms'"
    >
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div v-if="!showNativeForm" class="flex flex-col gap-4">
            <!-- Social Logins -->
            <Button 
                @click="loginWithGoogle"
                :disabled="isGoogleLoading"
                variant="outline" 
                class="h-12 w-full flex items-center justify-center gap-3 border-white/10 hover:bg-white/5 transition-all rounded-full group disabled:opacity-70 disabled:cursor-not-allowed"
            >
                <LoaderCircle v-if="isGoogleLoading" class="w-5 h-5 animate-spin text-safari-gold shrink-0" />
                <svg v-else viewBox="0 0 24 24" class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span class="text-sm font-black uppercase tracking-[0.2em] group-hover:text-safari-gold transition-colors">
                    {{ isGoogleLoading ? 'Connecting...' : 'Continue with Google' }}
                </span>
            </Button>

            <div class="grid grid-cols-2 gap-4">
                <Button variant="outline" class="h-12 w-full flex items-center justify-center gap-2 border-white/10 hover:bg-white/5 transition-all rounded-full group">
                    <svg viewBox="0 0 24 24" class="size-4 shrink-0 fill-[#1877F2]" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span class="text-xs font-black uppercase tracking-[0.1em] md:tracking-[0.2em] group-hover:text-[#1877F2] transition-colors">Facebook</span>
                </Button>

                <Button variant="outline" class="h-12 w-full flex items-center justify-center gap-2 border-white/10 hover:bg-white/5 transition-all rounded-full group">
                    <svg viewBox="0 0 24 24" class="size-4 shrink-0 fill-current" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 2.23-1.15 4.39-2.98 5.74-1.84 1.36-4.26 1.7-6.42 1.09-2.16-.62-3.95-2.23-4.71-4.32-.76-2.09-.54-4.52.61-6.44 1.14-1.92 3.25-3.21 5.48-3.45.18-.02.37-.02.55-.03v4.06c-1.55.15-2.99 1.19-3.61 2.61-.62 1.42-.39 3.08.57 4.27.96 1.19 2.59 1.7 4.09 1.28 1.49-.41 2.61-1.67 2.89-3.19.08-.42.12-.85.12-1.28V.02h-.65z"/>
                    </svg>
                    <span class="text-xs font-black uppercase tracking-[0.1em] md:tracking-[0.2em] group-hover:text-safari-gold transition-colors">TikTok</span>
                </Button>
            </div>

            <div class="flex items-center gap-4 my-4 opacity-10">
                <div class="h-px bg-white flex-1"></div>
                <span class="text-sm font-black uppercase tracking-widest leading-none">OR</span>
                <div class="h-px bg-white flex-1"></div>
            </div>

            <button @click="showNativeForm = true" class="text-sm font-black uppercase tracking-widest opacity-80 hover:opacity-100 hover:text-safari-gold transition-all">
                Legacy Sign In
            </button>
        </div>

        <form v-else @submit.prevent="submit" class="flex flex-col gap-6 animate-in fade-in slide-in-from-bottom-2 duration-500">
            <div class="grid gap-6">
                <!-- Native Fields ... -->
                <div class="grid gap-2">
                    <Label for="email" class="text-sm font-black uppercase tracking-widest opacity-90">Email address</Label>
                    <Input id="email" type="email" required autofocus tabindex="1" autocomplete="email" v-model="form.email" placeholder="email@example.com" class="bg-white/5 border-white/10" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password" class="text-sm font-black uppercase tracking-widest opacity-90">Password</Label>
                        <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm font-black uppercase tracking-widest opacity-80 hover:opacity-100 transition-all"> Forgot? </TextLink>
                    </div>
                    <Input id="password" type="password" required tabindex="2" autocomplete="current-password" v-model="form.password" placeholder="Password" class="bg-white/5 border-white/10" />
                    <InputError :message="form.errors.password" />
                </div>

                <Button type="submit" class="mt-4 w-full bg-safari-gold hover:bg-white text-black text-sm font-black uppercase tracking-[0.2em]" tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Access Pride
                </Button>
            </div>

            <button @click="showNativeForm = false" class="text-center text-sm font-black uppercase tracking-widest opacity-80 hover:opacity-100 transition-all">
                Back to Social Login
            </button>
        </form>

        <div class="text-center text-sm text-muted-foreground mt-8">
            Don't have an account?
            <TextLink :href="route('register')" class="text-safari-gold font-bold transition-all hover:underline underline-offset-4">Sign up</TextLink>
        </div>
    </AuthBase>
</template>
