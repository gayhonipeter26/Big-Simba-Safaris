<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const showNativeForm = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase 
        :title="showNativeForm ? 'Join the Tribe' : 'Become Part of the Pride'" 
        :description="showNativeForm ? 'Enter your details to create a new account' : 'Start your journey with us using your favorite social account'"
    >
        <Head title="Register" />

        <div v-if="!showNativeForm" class="flex flex-col gap-4">
            <!-- Social Logins -->
            <a :href="route('google.redirect')" class="w-full">
                <Button variant="outline" class="h-12 w-full flex items-center justify-center gap-3 border-white/10 hover:bg-white/5 transition-all rounded-full group">
                    <svg viewBox="0 0 24 24" class="size-5" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] group-hover:text-safari-gold transition-colors">Register with Google</span>
                </Button>
            </a>

            <Button variant="outline" class="h-12 w-full flex items-center gap-3 border-white/10 hover:bg-white/5 transition-all rounded-full group">
                <svg viewBox="0 0 24 24" class="size-5 fill-current" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987s2.362.987 3.956.953c1.619-.026 2.673-1.478 3.659-2.924 1.139-1.666 1.611-3.277 1.636-3.359-.033-.014-3.141-1.206-3.174-4.782-.026-2.981 2.45-4.411 2.559-4.477-1.393-2.04-3.531-2.274-4.29-2.327-1.848-.15-3.111 1.04-3.96 1.04zM15.424 3.765a4.848 4.848 0 0 0 1.125-3.265c0-.181-.017-.361-.048-.539-1.503.06-3.333.999-4.41 2.254a4.57 4.57 0 0 0-1.222 3.078c0 .19.019.379.055.565.176.012.355.018.537.018a4.839 4.839 0 0 0 3.963-2.111z"/>
                </svg>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] group-hover:text-safari-gold transition-colors">Register with Apple</span>
            </Button>

            <div class="flex items-center gap-4 my-4 opacity-10">
                <div class="h-px bg-white flex-1"></div>
                <span class="text-[10px] font-black uppercase tracking-widest leading-none">OR</span>
                <div class="h-px bg-white flex-1"></div>
            </div>

            <button @click="showNativeForm = true" class="text-[10px] font-black uppercase tracking-widest opacity-40 hover:opacity-100 hover:text-safari-gold transition-all">
                Legacy Registration
            </button>
        </div>

        <form v-else @submit.prevent="submit" class="flex flex-col gap-6 animate-in fade-in slide-in-from-bottom-2 duration-500">
            <div class="grid gap-6">
                <!-- Native Fields ... -->
                <div class="grid gap-2">
                    <Label for="name" class="text-[10px] font-black uppercase tracking-widest opacity-60">Full Name</Label>
                    <Input id="name" type="text" required autofocus tabindex="1" autocomplete="name" v-model="form.name" placeholder="Full name" class="bg-white/5 border-white/10" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email" class="text-[10px] font-black uppercase tracking-widest opacity-60">Email address</Label>
                    <Input id="email" type="email" required tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" class="bg-white/5 border-white/10" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="text-[10px] font-black uppercase tracking-widest opacity-60">Password</Label>
                    <Input id="password" type="password" required tabindex="3" autocomplete="new-password" v-model="form.password" placeholder="Password" class="bg-white/5 border-white/10" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation" class="text-[10px] font-black uppercase tracking-widest opacity-60">Confirm Password</Label>
                    <Input id="password_confirmation" type="password" required tabindex="4" autocomplete="new-password" v-model="form.password_confirmation" placeholder="Confirm" class="bg-white/5 border-white/10" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-4 w-full bg-safari-gold hover:bg-white text-black text-[10px] font-black uppercase tracking-[0.2em]" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Claim My Spot
                </Button>
            </div>

            <button @click="showNativeForm = false" class="text-center text-[10px] font-black uppercase tracking-widest opacity-40 hover:opacity-100 transition-all">
                Back to Social Register
            </button>
        </form>

        <div class="text-center text-sm text-muted-foreground mt-8">
            Already have an account?
            <TextLink :href="route('login')" class="text-safari-gold font-bold transition-all hover:underline underline-offset-4">Log in</TextLink>
        </div>
    </AuthBase>
</template>
