<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import axios from 'axios';
import { LoaderCircle, ShieldCheck, Smartphone } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import AuthBase from '@/layouts/AuthLayout.vue';

defineProps<{
    email: string;
}>();

const form = useForm({
    code: '',
});

const isHandshaked = ref(false);
let pollInterval: any = null;

const submit = () => {
    form.post(route('otp.verify'), {
        onFinish: () => form.reset('code'),
    });
};

const checkHandshake = async () => {
    try {
        const response = await axios.get(route('otp.status'));
        if (response.data.status === 'approved') {
            isHandshaked.value = true;
            clearInterval(pollInterval);
            submit(); // Auto-login
        }
    } catch (e) {
        // Silently fail polling
    }
};

onMounted(() => {
    pollInterval = setInterval(checkHandshake, 2000);
});

onUnmounted(() => {
    if (pollInterval) clearInterval(pollInterval);
});
</script>

<template>
    <AuthBase 
        title="Operations Clearance" 
        :description="isHandshaked ? 'Authorization confirmed via primary device. Establishing secure link...' : 'A specialized verification code has been dispatched to your reconnaissance email. Please provide it to establish a secure link.'"
    >
        <Head title="Verify Access" />

        <div class="flex justify-center mb-8 relative">
            <div 
                class="p-4 bg-safari-gold/10 border border-safari-gold/20 rounded-full transition-all duration-500"
                :class="isHandshaked ? 'scale-125 border-safari-gold bg-safari-gold/20' : 'animate-pulse'"
            >
                <ShieldCheck v-if="!isHandshaked" class="w-12 h-12 text-safari-gold" />
                <LoaderCircle v-else class="w-12 h-12 text-safari-gold animate-spin" />
            </div>
            
            <div v-if="!isHandshaked" class="absolute -right-4 bottom-0 p-2 bg-black border border-white/10 rounded-lg animate-bounce">
                <Smartphone class="w-4 h-4 text-white/40" />
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <Label for="code" class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Clearance Code</Label>
                <div class="relative">
                    <Input
                        id="code"
                        type="text"
                        class="h-14 bg-white/5 border-white/10 text-center text-2xl font-black tracking-[0.5em] focus:border-safari-gold/50 transition-all"
                        v-model="form.code"
                        :disabled="form.processing || isHandshaked"
                        required
                        autofocus
                        autocomplete="one-time-code"
                        placeholder="······"
                    />
                </div>
                <InputError class="mt-2 text-center" :message="form.errors.code" />
            </div>

            <div class="text-center text-[10px] font-black uppercase tracking-widest opacity-40">
                Authorized for: {{ email }}
            </div>

            <Button type="submit" class="w-full h-12 bg-safari-gold hover:bg-white text-black text-[10px] font-black uppercase tracking-[0.2em]" :disabled="form.processing || isHandshaked">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                <span v-if="isHandshaked">Handshake Verified</span>
                <span v-else>Validate Credentials</span>
            </Button>
        </form>

        <div class="mt-8 text-center flex flex-col gap-4">
            <p v-if="!isHandshaked" class="text-[9px] uppercase tracking-widest opacity-30">
                Waiting for mobile handshake or manual code...
            </p>
            <button @click="() => router.get(route('login'))" class="text-[10px] font-black uppercase tracking-widest opacity-40 hover:opacity-100 transition-all hover:text-safari-gold">
                Cancel Access Request
            </button>
        </div>
    </AuthBase>
</template>
