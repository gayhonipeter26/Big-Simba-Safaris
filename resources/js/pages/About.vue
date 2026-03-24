<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Camera, MapPin, Compass, Shield, Heart, Globe } from 'lucide-vue-next';
</script>

<template>
    <Head title="About Us - Big Simba Safaris" />

    <GuestLayout>
        <main class="py-44 container max-w-[1600px] mx-auto px-8 md:px-16 space-y-44">
            <!-- Hero section -->
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
                 <div class="space-y-12 order-2 lg:order-1">
                      <div class="space-y-4">
                           <p class="text-[10px] font-black tracking-[0.4em] uppercase text-safari-gold mb-4">The Pride of Kenya</p>
                           <h1 class="text-6xl md:text-[7rem] font-black uppercase tracking-tighter leading-[0.85]">Beyond The <br/> <span class="text-safari-gold/20">Horizon.</span></h1>
                      </div>
                      <p class="text-xl md:text-2xl font-light leading-relaxed max-w-xl opacity-60 italic">"Big Simba Safaris was founded by a team of local experts who have lived and breathed the Kenyan savanna for decades. We don't just show you the animals; we tell you the stories of the land."</p>
                      
                      <div class="pt-12 grid grid-cols-1 md:grid-cols-3 gap-12 border-t border-white/5">
                           <div class="space-y-3">
                                <h3 class="text-5xl font-black text-safari-gold tracking-tighter leading-none">15+</h3>
                                <p class="text-[9px] font-bold uppercase tracking-widest opacity-40">Years of Experience</p>
                           </div>
                           <div class="space-y-3">
                                <h3 class="text-5xl font-black text-safari-gold tracking-tighter leading-none">2500+</h3>
                                <p class="text-[9px] font-bold uppercase tracking-widest opacity-40">Successful Safaris</p>
                           </div>
                           <div class="space-y-3">
                                <h3 class="text-5xl font-black text-safari-gold tracking-tighter leading-none">100%</h3>
                                <p class="text-[9px] font-bold uppercase tracking-widest opacity-40">Sustainability Pledge</p>
                           </div>
                      </div>
                 </div>

                 <div class="order-1 lg:order-2">
                       <div class="relative group aspect-[4/5] rounded-sm overflow-hidden border border-white/5 shadow-2xl">
                             <img 
                                src="https://images.unsplash.com/photo-1516422213484-21437ef33ebb?q=80&w=2670" 
                                alt="Kenyan Sunset"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-[2000ms] group-hover:scale-110 grayscale-[0.3] group-hover:grayscale-0 opacity-80"
                            />
                            <div class="absolute inset-0 bg-safari-gold/5 mix-blend-overlay"></div>
                       </div>
                 </div>
            </section>

             <!-- Values -->
             <section class="grid grid-cols-1 md:grid-cols-3 gap-12 py-32 border-y border-white/5">
                  <div class="p-16 bg-white/[0.02] rounded-sm border border-white/5 hover:border-safari-gold/30 transition-all duration-700 group">
                        <Compass class="w-10 h-10 text-safari-gold mb-10 group-hover:scale-110 transition-transform" />
                        <h3 class="text-2xl font-black uppercase tracking-tight mb-6">Authenticity</h3>
                        <p class="text-sm font-light leading-relaxed opacity-50">Every safari is a unique journey. We avoid the crowds to give you a personal and genuine connection with nature. We focus on the raw, unfiltered beauty of the wild.</p>
                  </div>
                  <div class="p-16 bg-white/[0.02] rounded-sm border border-white/5 hover:border-safari-gold/30 transition-all duration-700 group">
                        <Shield class="w-10 h-10 text-safari-gold mb-10 group-hover:scale-110 transition-transform" />
                        <h3 class="text-2xl font-black uppercase tracking-tight mb-6">Responsibility</h3>
                        <p class="text-sm font-light leading-relaxed opacity-50">We believe in preserving the land we love. A portion of every booking goes towards local conservation and community projects that protect Kenya's heritage.</p>
                  </div>
                  <div class="p-16 bg-white/[0.02] rounded-sm border border-white/5 hover:border-safari-gold/30 transition-all duration-700 group">
                        <Globe class="w-10 h-10 text-safari-gold mb-10 group-hover:scale-110 transition-transform" />
                        <h3 class="text-2xl font-black uppercase tracking-tight mb-6">Local Impact</h3>
                        <p class="text-sm font-light leading-relaxed opacity-50">By staying in eco-lodges and employing highly skilled local guides, we ensure the benefits of tourism directly impact the local communities we operate in.</p>
                  </div>
             </section>

             <!-- Large Quote -->
             <section class="text-center py-24 space-y-12">
                  <h2 class="text-4xl md:text-6xl font-black uppercase tracking-tighter leading-tight max-w-4xl mx-auto">
                       "Every horizon has a story. Let <span class="text-safari-gold">Big Simba</span> guide you to yours."
                  </h2>
                  <Link :href="route('tours.index')" class="inline-block px-16 py-6 bg-safari-gold text-black text-[10px] font-black uppercase tracking-[0.4em] hover:bg-white hover:text-black transition-all duration-700">
                       Start Your Expedition
                  </Link>
             </section>
        </main>
    </GuestLayout>
</template>
