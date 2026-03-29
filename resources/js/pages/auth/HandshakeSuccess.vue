<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ShieldCheck, Smartphone } from 'lucide-vue-next';
import AuthBase from '@/layouts/AuthLayout.vue';
</script>

<template>
    <AuthBase 
        title="Authorization Granted" 
        description="Your primary device has successfully validated this session. Access protocols are now initiating on your workstation."
    >
        <Head title="Success" />

        <div class="flex justify-center mb-8 gap-4 items-center">
            <Smartphone class="w-12 h-12 text-white/40 animate-pulse" />
            <div class="h-[1px] w-12 bg-safari-gold"></div>
            <ShieldCheck class="w-16 h-16 text-safari-gold" />
        </div>

        <div class="bg-safari-gold/5 border border-safari-gold/20 p-6 rounded-lg text-center">
            <p class="text-sm font-black uppercase tracking-[0.2em] text-safari-gold">Session Securely Linkage Completed</p>
            <p class="text-xs mt-2 opacity-90">You may now close this window on your mobile device. Your computer will proceed automatically.</p>
        </div>
    </AuthBase>
</template>
