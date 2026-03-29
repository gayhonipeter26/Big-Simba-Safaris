<script setup lang="ts">
import SimbaLogo from '@/components/SimbaLogo.vue';
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { Mail, Phone, MapPin, Instagram, Youtube, Twitter, Send, Star } from 'lucide-vue-next';
import { ref, computed, watchEffect } from 'vue';

const page = usePage<any>();
const user = computed(() => page.props.auth?.user);
const tours = computed(() => page.props.tours || []);

const reviewForm = useForm({
    tour_id: '',
    rating: 5,
    comment: '',
    name: user.value?.name || '',
    email: user.value?.email || '',
});

const showReviewForm = ref(false);

// Update form when user context changes
watchEffect(() => {
    if (user.value) {
        reviewForm.name = user.value.name;
        reviewForm.email = user.value.email;
    }
});

const submitReview = () => {
    if (!reviewForm.tour_id) return;

    reviewForm.post(route('reviews.store', { tour: reviewForm.tour_id }), {
        preserveScroll: true,
        onSuccess: () => {
             reviewForm.reset('comment', 'tour_id', 'rating');
        },
    });
};
</script>

<template>
    <footer class="bg-[#020202] border-t border-white/5 pt-16 md:pt-32 pb-8 md:pb-16 px-6 md:px-16 overflow-hidden relative w-full">
        <!-- Tactical Grid Pattern -->
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="max-w-[1800px] mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-20 relative z-10">
            <!-- Col 1: Brand & Contact -->
            <div class="space-y-6 md:space-y-10 flex flex-col items-center md:items-start text-center md:text-left">
                <SimbaLogo />
                <!-- Tactical Mission Line (Mobile Only) -->
                <div class="md:hidden w-[1.5px] h-12 bg-safari-gold/40 shadow-[0_0_15px_rgba(234,179,8,0.2)]"></div>

                <p class="text-xs md:text-sm opacity-40 leading-relaxed font-light max-w-sm uppercase tracking-wider text-white">
                    Born from the savanna, we provide the most authentic, luxurious, and impactful safari experiences in the heart of Kenya. Every journey supports local conservation efforts.
                </p>
                <div class="w-full space-y-3 md:space-y-4 pt-4 border-t border-white/5">
                    <div class="flex items-center justify-center md:justify-start gap-4 text-xs font-black uppercase tracking-[0.2em] opacity-40 hover:opacity-100 transition-opacity text-white">
                        <Mail class="w-3.5 h-3.5 text-safari-gold" aria-hidden="true" />
                        <a href="mailto:info@bigsimbasafaris.co.ke" aria-label="Email Big Simba Safaris">info@bigsimbasafaris.co.ke</a>
                    </div>
                    <div class="flex items-center justify-center md:justify-start gap-4 text-xs font-black uppercase tracking-[0.2em] opacity-40 hover:opacity-100 transition-opacity text-white">
                        <Phone class="w-3.5 h-3.5 text-safari-gold" aria-hidden="true" />
                        <a href="tel:+254726159925" aria-label="Call Big Simba Safaris Operations">+254 726 159 925</a>
                    </div>
                    <div class="flex items-center justify-center md:justify-start gap-4 text-xs font-black uppercase tracking-[0.2em] opacity-40 hover:opacity-100 transition-opacity text-white">
                        <MapPin class="w-3.5 h-3.5 text-safari-gold" aria-hidden="true" />
                        <span aria-label="Headquarters">Nairobi, Kenya Ops</span>
                    </div>
                </div>
            </div>

            <!-- Col 2: Navigation Matrix -->
            <div class="grid grid-cols-2 gap-4 md:gap-8">
                <div class="space-y-6 md:space-y-8 text-center md:text-left">
                    <h4 class="font-display text-sm font-black uppercase tracking-[0.4em] text-safari-gold">Registry</h4>
                    <div class="flex flex-col gap-3 md:gap-4 text-[10px] md:text-sm font-bold uppercase tracking-widest opacity-40 text-white items-center md:items-start">
                        <Link v-for="t in (tours || []).slice(0, 4)" :key="t.id" :href="route('tours.index')">{{ t.name }}</Link>
                    </div>
                </div>
                <div class="space-y-6 md:space-y-8 text-center md:text-left">
                    <h4 class="font-display text-sm font-black uppercase tracking-[0.4em] text-safari-gold">Network</h4>
                    <div class="flex flex-col gap-3 md:gap-4 text-[10px] md:text-sm font-bold uppercase tracking-widest opacity-40 text-white items-center md:items-start">
                        <Link :href="route('home') + '#about'">Our Story</Link>
                        <Link :href="route('home') + '#contact'">Reach Out</Link>
                        <Link :href="route('blog.index')">Chronicles</Link>
                        <Link :href="route('register')">Join Member</Link>
                    </div>
                </div>
            </div>

            <!-- Col 3: Social Recon -->
            <div class="space-y-6 md:space-y-10 text-center md:text-left">
                <h4 class="font-display text-sm font-black uppercase tracking-[0.4em] text-safari-gold">Social Recon</h4>
                <div class="grid grid-cols-2 gap-3 md:gap-4">
                    <a href="#" class="p-4 md:p-6 border border-white/5 bg-white/[0.02] flex flex-col items-center gap-2 md:gap-3 hover:border-safari-gold/30 transition-all group" aria-label="Follow Big Simba Safaris on Instagram">
                        <Instagram class="w-5 h-5 text-white/20 group-hover:text-safari-gold transition-colors" aria-hidden="true" />
                        <span class="text-xs font-black uppercase tracking-widest opacity-30 text-white">Instagram</span>
                    </a>
                    <a href="#" class="p-4 md:p-6 border border-white/5 bg-white/[0.02] flex flex-col items-center gap-2 md:gap-3 hover:border-safari-gold/30 transition-all group" aria-label="Follow Big Simba Safaris on X">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 fill-white/20 group-hover:fill-safari-gold transition-colors" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932 6.064-6.932zm-1.292 19.494h2.039L6.486 3.24H4.298l13.311 17.407z"/>
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest opacity-30 text-white">X / Twitter</span>
                    </a>
                </div>
                <p class="text-[9px] md:text-xs opacity-20 uppercase font-black tracking-[0.4em] text-center pt-4 md:pt-8 italic leading-loose text-white">
                    - Every Expedition Fuels <br/> Conservation Efforts Worldwide -
                </p>
            </div>

            <!-- Col 4: Witness Report (Form) -->
            <div class="space-y-4 p-4 md:p-8 border border-white/10 bg-white/[0.01] rounded-sm relative group overflow-hidden flex flex-col items-center md:items-start text-center md:text-left">
                <div class="absolute -right-4 -top-4 w-12 h-12 border-t-[1.5px] border-r-[1.5px] border-safari-gold/20 group-hover:border-safari-gold group-hover:w-full group-hover:h-full transition-all duration-700 pointer-events-none"></div>
                
                <div class="flex items-center justify-between w-full">
                    <h4 class="font-display text-xs md:text-sm font-black uppercase tracking-[0.4em] text-safari-gold ml-auto md:ml-0">Witness Report</h4>
                    <button 
                        @click="showReviewForm = !showReviewForm"
                        class="md:hidden text-[9px] font-black uppercase tracking-widest text-safari-gold border border-safari-gold/20 px-3 py-1.5 rounded-sm bg-safari-gold/5 ml-auto"
                    >
                        {{ showReviewForm ? 'DISMISS' : 'REPORT' }}
                    </button>
                </div>

                <div :class="['space-y-4 transition-all duration-700 overflow-hidden w-full', !showReviewForm ? 'max-h-0 md:max-h-[1000px] opacity-0 md:opacity-100' : 'max-h-[1000px] opacity-100']">
                    <p class="text-[10px] md:text-xs opacity-40 uppercase tracking-widest leading-relaxed text-white">Logged an expedition? Contribute your intel.</p>
                    
                    <form @submit.prevent="submitReview" class="space-y-3 md:space-y-4 pt-2 md:pt-4">
                        <div v-if="!user" class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            <input v-model="reviewForm.name" type="text" placeholder="NAME" required class="w-full bg-white/5 border border-white/10 p-3 text-[10px] font-black uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all rounded-sm text-white placeholder:opacity-40" />
                            <input v-model="reviewForm.email" type="email" placeholder="EMAIL" required class="w-full bg-white/5 border border-white/10 p-3 text-[10px] font-black uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all rounded-sm text-white placeholder:opacity-40" />
                        </div>
    
                        <select v-model="reviewForm.tour_id" required class="w-full bg-white/5 border border-white/10 p-3 text-[10px] font-black uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all rounded-sm text-white">
                            <option value="" disabled class="bg-black">Select Expedition</option>
                            <option v-for="t in tours" :key="t.id" :value="t.id" class="bg-black">{{ t.name }}</option>
                        </select>
                        
                        <div class="flex items-center gap-2 py-1">
                             <button 
                                v-for="i in 5" 
                                :key="i"
                                type="button"
                                @click="reviewForm.rating = i"
                                :aria-label="`Rate ${i} stars`"
                                class="transition-all transform hover:scale-125 focus:outline-none"
                             >
                                <Star :class="['w-3.5 h-3.5', i <= reviewForm.rating ? 'text-safari-gold fill-safari-gold' : 'text-white/10 transition-colors']" aria-hidden="true" />
                             </button>
                             <span class="text-[9px] font-black text-safari-gold/50 uppercase ml-auto">{{ reviewForm.rating }}/5 Recon</span>
                        </div>
    
                        <textarea v-model="reviewForm.comment" rows="2" placeholder="YOUR INTEL..." required class="w-full bg-white/5 border border-white/10 p-3 text-[10px] font-black uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all rounded-sm resize-none text-white placeholder:opacity-40"></textarea>
                        
                        <button 
                            type="submit" 
                            :disabled="reviewForm.processing || !reviewForm.tour_id" 
                            class="w-full bg-safari-gold text-black py-3 md:py-4 text-[10px] md:text-xs font-black uppercase tracking-[0.4em] hover:bg-white transition-all disabled:opacity-30 flex items-center justify-center gap-3 rounded-sm"
                        >
                            <Send class="w-3.5 h-3.5" />
                            {{ reviewForm.processing ? 'Syncing...' : 'Submit Report' }}
                        </button>
                        <p v-if="reviewForm.recentlySuccessful" class="text-[9px] text-green-500 uppercase font-black text-center pt-2 animate-pulse">Intel Logged. Awaiting High Command Verification.</p>
                    </form>
                </div>
            </div>
        </div>

        <div class="max-w-[1800px] mx-auto mt-20 md:mt-32 pt-12 md:pt-16 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 md:gap-8 opacity-20">
            <p class="text-[9px] md:text-xs font-bold uppercase tracking-[0.3em] text-white text-center md:text-left">© 2026 Big Simba Safaris. All Wild Rights Reserved. LOCALE: {{ page.props.locale?.current }}</p>
            <div class="flex flex-wrap justify-center items-center gap-6 md:gap-12 text-[9px] md:text-xs font-bold uppercase tracking-[0.3em] text-white">
                <span>Terms of Pride</span>
                <span>Wild Privacy</span>
                <span>Conservation Pact</span>
            </div>
        </div>
    </footer>
</template>

<style scoped>
footer {
    font-family: 'Outfit', sans-serif;
}
</style>
