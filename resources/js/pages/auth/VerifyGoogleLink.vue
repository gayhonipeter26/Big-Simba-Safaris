<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, ShieldAlert } from 'lucide-vue-next';

defineProps<{
    email: string;
}>();

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('auth.google.verify.store'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase 
        title="Verify Account" 
        description="To link your Google account, please confirm ownership of your existing account."
    >
        <Head title="Verify Google Link" />

        <div class="mb-8 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-safari-gold/10 rounded-full flex items-center justify-center mb-6">
                <ShieldAlert class="w-8 h-8 text-safari-gold" />
            </div>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40 mb-2">Existing Account Detected</p>
            <p class="text-white font-bold tracking-wider">{{ email }}</p>
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="password" class="text-[10px] font-black uppercase tracking-widest opacity-60">Account Password</Label>
                    <Input 
                        id="password" 
                        type="password" 
                        required 
                        autofocus
                        v-model="form.password" 
                        placeholder="Enter your current password" 
                        class="bg-white/5 border-white/10" 
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <Button type="submit" class="mt-4 w-full bg-safari-gold hover:bg-white text-black text-[10px] font-black uppercase tracking-[0.2em]" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Verify & Link Account
                </Button>
            </div>

            <a :href="route('login')" class="text-center text-[10px] font-black uppercase tracking-widest opacity-40 hover:opacity-100 transition-all">
                Cancel and back to login
            </a>
        </form>
    </AuthBase>
</template>
