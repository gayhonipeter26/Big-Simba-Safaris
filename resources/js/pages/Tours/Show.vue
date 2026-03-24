<script setup lang="ts">
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Clock, MapPin, Share2, Heart, CheckCircle2, ChevronRight, Users, ShieldCheck, Star, ChevronLeft, X, Globe } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps<{
    tour: any;
}>();

const page = usePage<any>();
const auth = computed(() => page.props.auth);
const layout = computed(() => page.url.startsWith('/dashboard') ? AppHeaderLayout : GuestLayout);

const activeImage = ref(props.tour.images?.[0] || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072');
const selectedPeople = ref(1);

const formatPrice = (price: string | number) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(parseFloat(price.toString()));
};

const goBack = () => {
    window.history.back();
};

const bookingForm = useForm({
    tour_id: props.tour.id,
    booking_date: '',
    number_of_people: 1,
});

const totalInvestment = computed(() => {
    return parseFloat(props.tour.price) * bookingForm.number_of_people;
});

const submitBooking = () => {
    bookingForm.post(route('bookings.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Handle success
        }
    });
};

const reviewForm = useForm({
    rating: 5,
    comment: ''
});

const submitReview = () => {
    reviewForm.post(route('reviews.store', { tour: props.tour.id }), {
        onSuccess: () => reviewForm.reset(),
        preserveScroll: true
    });
};
</script>

<template>
    <Head :title="`${tour.name} - Big Simba Safaris`" />

    <component :is="layout" :full-bleed="true">
        <main class="max-w-[1800px] mx-auto px-8 md:px-20 py-20 md:py-44 min-h-screen bg-[#050505]">
            <!-- Functional Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-12 mb-24 pt-32 lg:pt-0">
                 <div class="space-y-8 flex-1">
                      <button @click="goBack" class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold/60 hover:text-safari-gold transition-colors group">
                           <ChevronLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" /> Back to Wilderness
                      </button>
                      <div class="space-y-6">
                           <div class="flex items-center gap-6">
                                <span class="px-5 py-2 bg-safari-gold/5 border border-safari-gold/20 text-safari-gold text-[9px] font-black uppercase tracking-[0.3em] rounded-full">
                                     {{ tour.destination?.name || 'CENTRAL KENYA' }}
                                </span>
                                <div class="flex items-center gap-3 text-[10px] font-black opacity-40 uppercase tracking-[0.4em] text-white">
                                     <Clock class="w-4 h-4 text-safari-gold" /> {{ tour.duration }}
                                </div>
                                <div class="flex items-center gap-3 text-[10px] font-black opacity-40 uppercase tracking-[0.4em] text-white">
                                     <Users class="w-4 h-4 text-safari-gold" /> Group max: 12
                                </div>
                           </div>
                           <h1 class="text-6xl md:text-[8rem] font-black uppercase tracking-tighter leading-[0.85] text-white">{{ tour.name }}</h1>
                      </div>
                 </div>
                 <div class="hidden xl:flex items-center gap-10 bg-white/[0.02] border border-white/5 p-10 rounded-sm shadow-2xl">
                      <div class="text-right space-y-2">
                           <p class="text-[10px] font-black uppercase tracking-[0.5em] opacity-30 text-white">Starting Investment</p>
                           <p class="text-5xl font-black uppercase tracking-tighter text-safari-gold">{{ formatPrice(tour.price) }}</p>
                      </div>
                      <button class="h-16 w-16 border border-white/10 hover:border-red-500/50 hover:bg-red-500/5 transition-all text-white/20 hover:text-red-500 rounded-full flex items-center justify-center">
                           <Heart class="w-6 h-6" />
                      </button>
                 </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-24 border-b border-white/5 pb-32 mb-32">
                 <!-- Left: Story & Details -->
                 <div class="lg:col-span-2 space-y-32">
                      <!-- Immersive Gallery -->
                      <section class="space-y-8">
                           <div class="aspect-[21/10] rounded-sm overflow-hidden border border-white/5 relative group shadow-2xl">
                                <img :src="activeImage" class="w-full h-full object-cover grayscale-[0.2] transition-transform duration-[3000ms] group-hover:scale-105" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-40"></div>
                           </div>
                           <div v-if="tour.images?.length > 1" class="flex gap-6 overflow-x-auto pb-6 scrollbar-hide px-2">
                                <button v-for="(img, i) in tour.images" :key="i" @click="activeImage = img" 
                                     :class="['w-32 h-32 rounded-sm overflow-hidden flex-shrink-0 border-2 transition-all shadow-lg', activeImage === img ? 'border-safari-gold scale-95 translate-y-1' : 'border-transparent opacity-40 hover:opacity-100']">
                                     <img :src="img" class="w-full h-full object-cover" />
                                </button>
                           </div>
                      </section>

                      <!-- Description -->
                      <section class="space-y-10 prose prose-invert max-w-none">
                           <div class="flex items-center gap-8 mb-12">
                                <p class="text-[11px] font-black tracking-[0.6em] uppercase text-safari-gold whitespace-nowrap">The Narrative //</p>
                                <div class="h-[1px] w-full bg-white/5"></div>
                           </div>
                           <p class="text-2xl md:text-3xl font-light leading-relaxed opacity-60 italic max-w-4xl text-white">
                                {{ tour.description }}
                           </p>
                      </section>

                      <!-- Itinerary Architecture -->
                      <section class="space-y-20">
                           <div class="border-l-4 border-safari-gold pl-12 space-y-4">
                                <h2 class="text-5xl font-black uppercase tracking-tighter text-white">Expedition Roadmap</h2>
                                <p class="text-[10px] opacity-40 uppercase tracking-[0.5em] font-black text-white">Daily Sequence of Events</p>
                           </div>

                           <div class="space-y-16 relative">
                                <!-- Connecting Line -->
                                <div class="absolute left-7 top-4 bottom-4 w-[1px] bg-white/10 border-l border-dashed border-white/20"></div>

                                <div v-for="(item, index) in tour.itinerary" :key="index" class="relative pl-32 group">
                                     <!-- Day Anchor -->
                                     <div class="absolute left-0 top-0 w-14 h-14 rounded-full border border-white/10 bg-black flex items-center justify-center group-hover:border-safari-gold group-hover:bg-safari-gold/10 transition-all z-10 shadow-xl">
                                          <span class="text-[11px] font-black text-safari-gold group-hover:scale-125 transition-transform">{{ (item.day as any) || (index as number) + 1 }}</span>
                                     </div>
                                     <div class="space-y-6 bg-white/[0.01] border border-white/5 p-10 rounded-sm hover:border-safari-gold/20 transition-all duration-500 shadow-2xl">
                                          <h3 class="text-2xl font-black uppercase tracking-tight group-hover:text-safari-gold transition-colors text-white">{{ item.title || `Phase ${(index as number) + 1}` }}</h3>
                                          <p class="text-lg font-light opacity-50 leading-relaxed max-w-3xl italic text-white/70">"{{ item.description }}"</p>
                                          <div class="flex gap-4 opacity-20 group-hover:opacity-100 transition-opacity">
                                               <span class="text-[8px] font-black uppercase tracking-[0.3em] px-3 py-1 border border-white/10 rounded-full text-white">Dawn Recon</span>
                                               <span class="text-[8px] font-black uppercase tracking-[0.3em] px-3 py-1 border border-white/10 rounded-full text-white">Scout Guided</span>
                                          </div>
                                     </div>
                                </div>
                           </div>
                      </section>

                       <!-- Expedition Logistics -->
                       <section class="space-y-16">
                            <div class="border-l-4 border-safari-gold pl-12 space-y-4">
                                 <h2 class="text-5xl font-black uppercase tracking-tighter text-white">Expedition Logistics</h2>
                                 <p class="text-[10px] opacity-40 uppercase tracking-[0.5em] font-black text-white">Tactical Mobility & Support Hub</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                 <div v-for="vehicle in (page.props.fleet as any[])" :key="vehicle.id" class="group bg-white/[0.01] border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-700 shadow-2xl flex flex-col sm:flex-row">
                                      <div class="sm:w-1/2 aspect-video sm:aspect-auto overflow-hidden bg-black relative">
                                           <img :src="vehicle.image" class="w-full h-full object-cover transition-transform duration-[2000ms] group-hover:scale-110 opacity-70" />
                                           <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black to-transparent">
                                                <p class="text-[8px] font-black uppercase tracking-widest text-safari-gold">{{ vehicle.drive_type }}</p>
                                                <h4 class="text-sm font-black uppercase tracking-tight text-white">{{ vehicle.name }}</h4>
                                           </div>
                                      </div>
                                      <div class="sm:w-1/2 p-8 space-y-6 flex flex-col justify-between italic">
                                           <div class="space-y-4">
                                                <div class="flex items-center gap-2 opacity-40">
                                                     <Users class="w-4 h-4 text-safari-gold" />
                                                     <span class="text-[10px] font-black uppercase tracking-widest text-white">{{ vehicle.capacity }} SEATS</span>
                                                </div>
                                                <p class="text-[10px] font-light opacity-50 leading-relaxed uppercase tracking-widest">Available for this expedition</p>
                                           </div>
                                           <Link :href="route('fleet-hire.index')" class="flex items-center gap-2 text-[8px] font-black uppercase tracking-widest text-safari-gold/60 hover:text-safari-gold transition-colors group/btn">
                                                View Documentation <ChevronRight class="w-3 h-3 group-hover/btn:translate-x-1 transition-transform" />
                                           </Link>
                                      </div>
                                 </div>
                            </div>
                       </section>

                       <!-- Provisions -->
                       <section class="grid grid-cols-1 md:grid-cols-2 gap-20 py-24 border-y border-white/5">
                           <div class="space-y-12">
                                <div class="space-y-2">
                                     <h3 class="text-[11px] font-black uppercase tracking-[0.5em] text-safari-gold">Included In Pride</h3>
                                     <p class="text-[9px] opacity-20 uppercase tracking-widest font-bold italic text-white">Standard Big Simba Provisions</p>
                                </div>
                                <ul class="space-y-6">
                                     <li v-for="item in tour.whats_included" :key="item" class="flex items-center gap-6 text-[10px] font-black uppercase tracking-[0.3em] opacity-60 hover:opacity-100 transition-opacity text-white">
                                          <div class="w-8 h-8 rounded-full bg-safari-gold/5 flex items-center justify-center flex-shrink-0 border border-safari-gold/20 shadow-lg">
                                               <CheckCircle2 class="w-4 h-4 text-safari-gold" />
                                          </div>
                                          {{ item }}
                                     </li>
                                </ul>
                           </div>
                           <div class="space-y-12">
                                <div class="space-y-2">
                                     <h3 class="text-[11px] font-black uppercase tracking-[0.5em] opacity-40 text-white">Personal Provisions</h3>
                                     <p class="text-[9px] opacity-20 uppercase tracking-widest font-bold italic text-white">Items Not Under Our Banner</p>
                                </div>
                                <ul class="space-y-6">
                                     <li v-for="item in tour.whats_excluded" :key="item" class="flex items-center gap-6 text-[10px] font-black uppercase tracking-[0.3em] opacity-20 hover:opacity-40 transition-opacity text-white">
                                          <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center flex-shrink-0 border border-white/10">
                                               <X class="w-4 h-4 text-white/40" />
                                          </div>
                                          {{ item }}
                                     </li>
                                </ul>
                           </div>
                      </section>
                 </div>

                 <!-- Right: Action Center -->
                 <div class="lg:sticky lg:top-40 h-fit space-y-12">
                      <div class="bg-[#111009] border border-white/10 rounded-sm p-12 space-y-16 shadow-2xl relative overflow-hidden group">
                           <div class="absolute -right-20 -top-20 w-64 h-64 bg-safari-gold/5 rounded-full blur-3xl group-hover:bg-safari-gold/10 transition-all duration-1000"></div>
                           
                           <div class="space-y-4 relative z-10">
                                <p class="text-[11px] font-black uppercase tracking-[0.5em] opacity-40 text-white">Reserve Expedition</p>
                                <div class="flex items-baseline gap-3">
                                     <h2 class="text-6xl font-black uppercase tracking-tighter text-safari-gold">{{ formatPrice(tour.price) }}</h2>
                                     <span class="text-[11px] font-black opacity-30 uppercase tracking-[0.2em] italic text-white">/ Person</span>
                                </div>
                           </div>

                           <div class="space-y-12 relative z-10">
                                <div class="space-y-5">
                                     <label class="text-[10px] font-black uppercase tracking-[0.5em] opacity-40 ml-1 text-white">Commencement Date</label>
                                     <div class="relative">
                                          <span class="absolute left-6 top-1/2 -translate-y-1/2 text-sm opacity-40">📅</span>
                                          <input type="date" v-model="bookingForm.booking_date" class="w-full bg-white/5 border border-white/10 rounded-sm pl-16 pr-6 py-6 text-xs font-black uppercase tracking-widest focus:border-safari-gold transition-all outline-none text-white appearance-none" />
                                     </div>
                                </div>

                                <div class="space-y-5">
                                     <label class="text-[10px] font-black uppercase tracking-[0.5em] opacity-40 ml-1 text-white">Party Magnitude</label>
                                     <div class="relative">
                                          <Users class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-safari-gold opacity-40" />
                                          <input type="number" v-model="bookingForm.number_of_people" min="1" max="12" class="w-full bg-white/5 border border-white/10 rounded-sm pl-16 pr-6 py-6 text-xs font-black uppercase tracking-widest focus:border-safari-gold transition-all outline-none text-white" />
                                     </div>
                                </div>
                           </div>

                           <div class="pt-12 border-t border-white/10 space-y-10 relative z-10">
                                <div class="flex items-center justify-between">
                                     <p class="text-[11px] font-black uppercase tracking-[0.4em] opacity-40 text-white">Expedition Total</p>
                                     <p class="text-3xl font-black text-safari-gold tracking-tighter">{{ formatPrice(totalInvestment) }}</p>
                                </div>
                                <button @click="submitBooking" :disabled="bookingForm.processing" class="w-full bg-safari-gold text-black py-8 text-[12px] font-black uppercase tracking-[0.5em] hover:bg-white transition-all transform hover:-translate-y-2 shadow-2xl rounded-sm active:scale-95">
                                     {{ bookingForm.processing ? 'SYNCHRONIZING...' : 'AUTHORIZE DEPOSIT' }}
                                </button>
                                <div class="flex items-center justify-center gap-4 opacity-30 group cursor-default pt-4 border-t border-white/5">
                                     <ShieldCheck class="w-4 h-4 group-hover:text-green-500 transition-colors" />
                                     <span class="text-[9px] font-black uppercase tracking-[0.3em] text-white">Encrypted Pulse Protocol Secured</span>
                                </div>
                           </div>
                      </div>

                      <!-- Heritage Guarantee -->
                      <div class="p-12 border border-white/5 bg-white/[0.02] rounded-sm space-y-8 relative overflow-hidden group hover:border-safari-gold/20 transition-all duration-700 shadow-xl">
                           <div class="flex items-center gap-6">
                                <div class="h-12 w-12 rounded-full border border-white/5 flex items-center justify-center bg-black shadow-inner">
                                     <Globe class="w-5 h-5 text-safari-gold opacity-40" />
                                </div>
                                <h3 class="text-2xl font-black uppercase tracking-tighter text-white">Big Simba <br/>Pledge.</h3>
                           </div>
                           <p class="text-[11px] opacity-40 uppercase tracking-[0.2em] leading-relaxed italic font-light text-white/50">
                                "Your investment is secured by our heritage guarantee. Every expedition is fully vetted and operated by local masters who have traversed these trails for generations."
                           </p>
                      </div>
                 </div>
            </div>

            <!-- Reviews Section -->
            <section class="space-y-24 mb-44">
                 <div class="flex items-end justify-between border-b border-white/5 pb-16">
                      <div class="space-y-4">
                           <h2 class="text-6xl font-black uppercase tracking-tighter leading-none text-white">Witness <br/>Reports</h2>
                           <p class="text-[11px] opacity-40 uppercase tracking-[0.5em] font-black italic text-white/40">Verified feedback from previous explorers</p>
                      </div>
                      <div class="flex items-center gap-8 bg-white/[0.02] p-8 border border-white/5 rounded-sm shadow-xl">
                           <div class="text-right">
                                <p class="text-[10px] font-black uppercase tracking-[0.4em] opacity-30 whitespace-nowrap text-white">Expedition Rank</p>
                                <div class="flex items-center gap-1 justify-end mt-2">
                                     <Star v-for="i in 5" :key="i" class="w-3 h-3 text-safari-gold fill-safari-gold shadow-gold-sm" />
                                </div>
                           </div>
                           <div class="h-12 w-[1px] bg-white/10"></div>
                           <div class="flex items-center gap-4">
                                <span class="text-6xl font-black text-safari-gold leading-none">{{ tour.reviews?.length || 0 }}</span>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-30 leading-tight text-white">Total <br/> Voices</span>
                           </div>
                      </div>
                 </div>

                 <div v-if="tour.reviews?.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-x-24 gap-y-20">
                      <div v-for="review in tour.reviews" :key="review.id" class="space-y-8 group bg-white/[0.01] p-12 border border-transparent hover:border-white/5 rounded-sm transition-all duration-500 shadow-2xl">
                           <div class="flex items-center justify-between">
                                <div class="flex items-center gap-6">
                                     <div class="w-16 h-16 bg-black border border-white/5 rounded-full flex items-center justify-center group-hover:border-safari-gold/40 transition-all shadow-xl">
                                          <span class="text-[14px] font-black uppercase tracking-[0.2em] text-safari-gold">{{ review.user?.name.charAt(0) }}</span>
                                     </div>
                                     <div class="space-y-2">
                                          <p class="text-lg font-black uppercase tracking-tight text-white capitalize">{{ review.user?.name }}</p>
                                          <div class="flex items-center gap-1">
                                               <Star v-for="i in 5" :key="i" :class="['w-3 h-3', i <= review.rating ? 'text-safari-gold fill-safari-gold' : 'text-white/5']" />
                                          </div>
                                     </div>
                                </div>
                                <span class="text-[8px] font-bold opacity-20 uppercase tracking-[0.5em] hidden md:block text-white">Verified Explorer</span>
                           </div>
                           <p class="text-lg font-light opacity-50 leading-relaxed italic border-l-2 border-white/5 pl-10 group-hover:border-safari-gold/40 transition-all duration-700 text-white/70">"{{ review.comment }}"</p>
                      </div>
                 </div>

                 <div v-else class="py-32 text-center border border-dashed border-white/5 rounded-sm opacity-20">
                      <p class="text-[12px] font-black uppercase tracking-[1em] text-white">The records are silent</p>
                 </div>

                 <!-- Review Form -->
                 <div v-if="(page.props as any).auth?.user" class="mt-44 p-16 bg-white/[0.01] border border-white/10 rounded-sm space-y-16 shadow-2xl relative overflow-hidden">
                      <div class="absolute inset-0 bg-safari-gold/[0.01] pointer-events-none"></div>
                      <div class="space-y-6 relative z-10">
                           <h3 class="text-4xl font-black uppercase tracking-tighter text-white">Scribe Your Legend</h3>
                           <p class="text-[11px] opacity-40 uppercase tracking-[0.4em] font-black italic text-white/50">Contribute to the collective wisdom of the pride.</p>
                      </div>

                      <form @submit.prevent="submitReview" class="space-y-16 relative z-10">
                           <div class="space-y-8">
                                <label class="text-[11px] font-black uppercase tracking-[0.5em] opacity-40 ml-1 text-white">Witness Rating</label>
                                <div class="flex items-center gap-10">
                                     <button v-for="i in 5" :key="i" type="button" @click="reviewForm.rating = i" class="group relative">
                                          <Star :class="['w-12 h-12 transition-all duration-500', i <= reviewForm.rating ? 'text-safari-gold fill-safari-gold scale-125 drop-shadow-[0_0_15px_rgba(202,138,4,0.4)]' : 'text-white/5 hover:text-white/20']" />
                                          <div v-if="i === reviewForm.rating" class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-2 h-2 rounded-full bg-safari-gold animate-pulse"></div>
                                     </button>
                                </div>
                           </div>

                           <div class="space-y-8">
                                <label class="text-[11px] font-black uppercase tracking-[0.5em] opacity-40 ml-1 text-white">The Narrative (In Detail)</label>
                                <textarea v-model="reviewForm.comment" rows="8" placeholder="DOCUMENT YOUR JOURNEY ACCURATELY..." class="w-full bg-black/40 border border-white/10 rounded-sm p-12 text-lg font-light tracking-wide outline-none focus:border-safari-gold transition-all leading-loose uppercase placeholder:opacity-10 text-white shadow-inner"></textarea>
                           </div>

                           <button type="submit" :disabled="reviewForm.processing" class="px-24 py-8 bg-safari-gold text-black text-[12px] font-black uppercase tracking-[0.6em] hover:bg-white transition-all transform hover:-translate-y-2 rounded-sm shadow-2xl active:scale-95">
                                {{ reviewForm.processing ? 'SYNCHRONIZING...' : 'SEAL THE RECORD' }}
                           </button>
                      </form>
                 </div>
                 <div v-else class="text-center py-32 border-t border-white/5">
                      <Link :href="route('login')" class="group inline-flex items-center gap-6">
                           <div class="h-[1px] w-12 bg-white/10 group-hover:w-24 transition-all duration-700"></div>
                           <span class="text-[12px] font-black uppercase tracking-[0.6em] text-safari-gold group-hover:text-white transition-colors">Authenticate to Chronicle Your Journey</span>
                           <div class="h-[1px] w-12 bg-white/10 group-hover:w-24 transition-all duration-700"></div>
                      </Link>
                 </div>
            </section>
        </main>
    </component>
</template>

<style scoped>
main {
    font-family: 'Outfit', sans-serif;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.shadow-gold-sm {
    filter: drop-shadow(0 0 5px rgba(202, 138, 4, 0.3));
}
</style>

