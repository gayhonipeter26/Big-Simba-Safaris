<script setup lang="ts">
import { ref } from 'vue';
import { 
    X, Smartphone, Loader2, CheckCircle2, AlertCircle, 
    ArrowRight, ShieldCheck, Lock 
} from 'lucide-vue-next';
import { 
    Dialog, DialogContent, DialogHeader, 
    DialogTitle, DialogDescription 
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps<{
    isOpen: boolean;
    orderId: number;
    orderType: 'booking' | 'fleet' | 'service' | 'enquiry';
    amount: number;
    reference: string;
}>();

const emit = defineEmits(['close', 'success']);

const phone = ref('');
const step = ref<'input' | 'processing' | 'success' | 'failed'>('input');
const errorMessage = ref('');
const loading = ref(false);

const initiatePayment = async () => {
    if (!phone.value || phone.value.length < 10) {
        errorMessage.value = 'Invalid tactical communication digits.';
        return;
    }

    loading.value = true;
    errorMessage.value = '';
    
    try {
        const response = await axios.post(route('mpesa.stkpush'), {
            phone: phone.value,
            amount: props.amount,
            reference: props.reference,
            order_id: props.orderId,
            order_type: props.orderType,
            description: `Payment for safari: ${props.reference}`
        });

        if (response.data.ResponseCode === '0') {
            step.value = 'processing';
            // In a real app, we would start polling here. 
            // For now, we'll inform the user to check their phone.
        } else {
            step.value = 'failed';
            errorMessage.value = response.data.ResponseDescription || 'Tactical mission failed.';
        }
    } catch (e: any) {
        step.value = 'failed';
        errorMessage.value = e.response?.data?.message || 'Connection lost during handshake.';
    } finally {
        loading.value = false;
    }
};

const close = () => {
    step.value = 'input';
    phone.value = '';
    emit('close');
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="close">
        <DialogContent class="bg-[#0a0a0a] border-white/10 text-white max-w-md p-0 overflow-hidden shadow-[0_0_100px_rgba(0,0,0,1)]">
            <!-- Cinematic Header -->
            <div class="relative h-44 w-full bg-black flex items-center justify-center overflow-hidden border-b border-white/5">
                <div class="absolute inset-0 opacity-20">
                    <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?q=80&w=800" class="w-full h-full object-cover grayscale" />
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] to-transparent"></div>
                <div class="relative z-10 text-center space-y-2">
                    <div class="w-16 h-16 bg-safari-gold/10 border border-safari-gold/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <Smartphone class="w-8 h-8 text-safari-gold" />
                    </div>
                    <DialogTitle class="text-[14px] font-black uppercase tracking-[0.4em] text-white">M-Pesa STK Push</DialogTitle>
                </div>
            </div>

            <div class="p-10 space-y-8">
                <!-- Step: Input -->
                <div v-if="step === 'input'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4">
                    <div class="space-y-4">
                        <p class="text-sm font-black uppercase tracking-[0.3em] opacity-30">Mission Details</p>
                        <div class="flex items-center justify-between py-4 border-b border-white/5">
                            <span class="text-sm font-bold opacity-50 uppercase tracking-widest">Target Reference</span>
                            <span class="text-sm font-black uppercase tracking-widest">{{ reference }}</span>
                        </div>
                        <div class="flex items-center justify-between py-4 border-b border-white/5">
                            <span class="text-sm font-bold opacity-50 uppercase tracking-widest">Tactical Investment</span>
                            <span class="text-2xl font-black text-safari-gold tracking-tighter">KES {{ amount.toLocaleString() }}</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-[0.3em] opacity-30">Phone Number (254...)</label>
                        <div class="relative group">
                            <Smartphone class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-safari-gold/40 group-focus-within:text-safari-gold transition-colors" />
                            <input 
                                v-model="phone"
                                type="text" 
                                placeholder="0712 345 678" 
                                class="w-full bg-white/[0.03] border border-white/5 pl-12 pr-4 py-5 text-sm font-black tracking-widest uppercase text-white outline-none focus:border-safari-gold/30 transition-all rounded-sm"
                            />
                        </div>
                        <p v-if="errorMessage" class="text-xs text-red-500 font-black uppercase tracking-widest italic animate-pulse">{{ errorMessage }}</p>
                    </div>

                    <Button 
                        @click="initiatePayment" 
                        :disabled="loading"
                        class="w-full bg-safari-gold text-black hover:bg-white py-8 text-sm font-black uppercase tracking-[0.4em] rounded-sm transition-all shadow-xl group"
                    >
                        <template v-if="loading">
                            <Loader2 class="w-4 h-4 animate-spin mr-2" />
                            Syncing Handshake...
                        </template>
                        <template v-else>
                            Initiate Payment <ArrowRight class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform" />
                        </template>
                    </Button>
                </div>

                <!-- Step: Processing -->
                <div v-if="step === 'processing'" class="text-center space-y-10 py-10 animate-in zoom-in duration-700">
                    <div class="relative w-24 h-24 mx-auto">
                        <div class="absolute inset-0 rounded-full border-2 border-safari-gold/10 animate-ping"></div>
                        <div class="relative w-full h-full bg-safari-gold/5 border border-safari-gold/30 rounded-full flex items-center justify-center">
                            <Loader2 class="w-10 h-10 text-safari-gold animate-spin" />
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h3 class="font-display text-2xl font-black uppercase tracking-tighter">Awaiting Signal</h3>
                        <p class="text-sm opacity-40 uppercase font-light leading-loose tracking-widest italic max-w-[280px] mx-auto">
                            "Check your handheld device and enter your M-Pesa PIN to complete the tactical transmission."
                        </p>
                        <!-- Local Dev Simulation Link -->
                        <div class="pt-6">
                            <a 
                                :href="`/api/test/mpesa-mock-success/${reference}`" 
                                target="_blank"
                                class="text-xs font-black uppercase tracking-[0.3em] text-safari-gold/40 hover:text-safari-gold transition-colors border-b border-safari-gold/20"
                            >
                                Simulate Deployment Success (Dev Only)
                            </a>
                        </div>
                    </div>
                    <Button @click="close" variant="ghost" class="text-xs font-black uppercase tracking-[0.4em] opacity-30 hover:opacity-100 hover:text-safari-gold transition-all">
                        Cancel Deployment
                    </Button>
                </div>

                <!-- Step: Failed -->
                <div v-if="step === 'failed'" class="text-center space-y-10 py-10 animate-in zoom-in duration-700 text-red-500">
                    <AlertCircle class="w-20 h-20 mx-auto" />
                    <div class="space-y-4">
                        <h3 class="font-display text-2xl font-black uppercase tracking-tighter">Mission Compromised</h3>
                        <p class="text-sm opacity-60 uppercase font-black tracking-widest italic">{{ errorMessage }}</p>
                    </div>
                    <Button @click="step = 'input'" class="bg-white/5 border border-white/10 text-white hover:bg-white hover:text-black py-6 text-xs font-black uppercase tracking-[0.4em] rounded-sm transition-all">
                        Retry Handshake
                    </Button>
                </div>
                
                <!-- Trust Badge -->
                <div class="flex items-center justify-center gap-4 py-4 border-t border-white/5 opacity-20 group hover:opacity-100 transition-opacity">
                    <Lock class="w-3 h-3" />
                    <span class="text-xs font-black uppercase tracking-widest">Secured by Daraja Protocol</span>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
