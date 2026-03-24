<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Mail, Phone, MapPin, Send, Instagram, Twitter, Facebook, Globe } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    message: '',
});

const submit = () => {
    form.post(route('contact.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Contact Us - Big Simba Safaris" />

    <GuestLayout>
        <main class="py-44 container max-w-[1600px] mx-auto px-8 md:px-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-32">
                 <!-- Info Area -->
                 <div class="space-y-24">
                      <header class="space-y-10">
                           <div class="space-y-4">
                                <p class="text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold mb-4">Get In Touch</p>
                                <h1 class="text-6xl md:text-[7rem] font-black uppercase tracking-tighter leading-[0.85]">Let's Capture <br/> The <span class="text-safari-gold/20">Wild.</span></h1>
                           </div>
                           <p class="text-xl md:text-2xl font-light leading-relaxed max-w-xl opacity-60 italic">Have a question about a safari or want to build a custom itinerary? Our team of experts is ready to help you plan your journey of a lifetime.</p>
                      </header>

                      <div class="space-y-12">
                           <div class="flex items-center gap-10 group bg-white/[0.02] border border-white/5 p-8 rounded-sm hover:border-safari-gold/30 transition-all duration-700">
                                <div class="h-14 w-14 rounded-full bg-white/5 flex items-center justify-center border border-white/10 group-hover:bg-safari-gold group-hover:text-safari-brown transition-all duration-500">
                                     <Mail class="w-6 h-6" />
                                </div>
                                <div class="space-y-1">
                                     <p class="text-[9px] font-black uppercase tracking-[0.4em] opacity-40">Email Our Team</p>
                                     <a href="mailto:info@bigsimbasafaris.co.ke" class="text-lg font-black uppercase tracking-tight hover:text-safari-gold transition-colors">info@bigsimbasafaris.co.ke</a>
                                </div>
                           </div>
                           <div class="flex items-center gap-10 group bg-white/[0.02] border border-white/5 p-8 rounded-sm hover:border-safari-gold/30 transition-all duration-700">
                                <div class="h-14 w-14 rounded-full bg-white/5 flex items-center justify-center border border-white/10 group-hover:bg-safari-gold group-hover:text-safari-brown transition-all duration-500">
                                     <Phone class="w-6 h-6" />
                                </div>
                                <div class="space-y-1">
                                     <p class="text-[9px] font-black uppercase tracking-[0.4em] opacity-40">Call or WhatsApp</p>
                                     <a href="tel:+254726159925" class="text-lg font-black uppercase tracking-tight hover:text-safari-gold transition-colors">+254 726 159 925</a>
                                </div>
                           </div>
                           <div class="flex items-center gap-10 group bg-white/[0.02] border border-white/5 p-8 rounded-sm hover:border-safari-gold/30 transition-all duration-700">
                                <div class="h-14 w-14 rounded-full bg-white/5 flex items-center justify-center border border-white/10 group-hover:bg-safari-gold group-hover:text-safari-brown transition-all duration-500">
                                     <MapPin class="w-6 h-6" />
                                </div>
                                <div class="space-y-1">
                                     <p class="text-[9px] font-black uppercase tracking-[0.4em] opacity-40">Visit Our Den</p>
                                     <p class="text-lg font-black uppercase tracking-tight opacity-80">Nairobi City, Kenya</p>
                                </div>
                           </div>
                      </div>

                      <div class="pt-12 flex items-center gap-10 border-t border-white/5">
                           <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-30">Pulse Connect</p>
                           <div class="h-[1px] w-20 bg-white/10"></div>
                           <div class="flex gap-8">
                                <a href="#" class="h-10 w-10 border border-white/10 rounded-full flex items-center justify-center opacity-60 hover:opacity-100 hover:text-safari-gold hover:border-safari-gold transition-all duration-300">
                                     <Instagram class="w-4 h-4" />
                                </a>
                                <a href="#" class="h-10 w-10 border border-white/10 rounded-full flex items-center justify-center opacity-60 hover:opacity-100 hover:text-safari-gold hover:border-safari-gold transition-all duration-300">
                                     <Twitter class="w-4 h-4" />
                                </a>
                                <a href="#" class="h-10 w-10 border border-white/10 rounded-full flex items-center justify-center opacity-60 hover:opacity-100 hover:text-safari-gold hover:border-safari-gold transition-all duration-300">
                                     <Facebook class="w-4 h-4" />
                                </a>
                           </div>
                      </div>
                 </div>

                 <!-- Form Area -->
                 <div class="bg-card border border-white/5 rounded-sm p-16 space-y-12 shadow-2xl h-fit">
                        <div class="space-y-4">
                             <h3 class="text-3xl font-black uppercase tracking-tighter">Send Us A Message</h3>
                             <p class="text-[10px] opacity-40 uppercase tracking-[0.3em] font-black">Expect a response within 2 working hours.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-10">
                             <div class="space-y-4">
                                  <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1">Your Full Name</label>
                                  <input 
                                    v-model="form.name"
                                    type="text" 
                                    required 
                                    placeholder="ENTER YOUR NAME" 
                                    class="w-full bg-white/5 border border-white/10 rounded-sm p-6 text-xs font-black tracking-widest focus:border-safari-gold transition-all outline-none placeholder:opacity-20 uppercase"
                                  />
                             </div>
                             <div class="space-y-4">
                                  <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1">Email Address</label>
                                  <input 
                                    v-model="form.email"
                                    type="email" 
                                    required 
                                    placeholder="ENTER YOUR EMAIL" 
                                    class="w-full bg-white/5 border border-white/10 rounded-sm p-6 text-xs font-black tracking-widest focus:border-safari-gold transition-all outline-none placeholder:opacity-20 uppercase"
                                  />
                             </div>
                             <div class="space-y-4">
                                  <label class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 ml-1">Message Detail</label>
                                  <textarea 
                                    v-model="form.message"
                                    rows="6" 
                                    required 
                                    placeholder="DESCRIBE YOUR DREAM SAFARI..." 
                                    class="w-full bg-white/5 border border-white/10 rounded-sm p-6 text-xs font-light tracking-widest focus:border-safari-gold transition-all outline-none placeholder:opacity-20 uppercase leading-relaxed"
                                  ></textarea>
                             </div>
                             <button type="submit" class="w-full bg-safari-gold text-black font-black uppercase text-[12px] tracking-[0.4em] py-8 rounded-sm hover:bg-white transition-all shadow-xl flex items-center justify-center gap-6 group">
                                  Request Call Back
                                  <Send class="w-5 h-5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" />
                             </button>
                        </form>
                 </div>
            </div>
        </main>
    </GuestLayout>
</template>
