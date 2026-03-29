<script setup lang="ts">
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { ArrowRight, ChevronRight, ChevronLeft, MapPin, Search, Calendar, Users, Car, Heart, Star, LayoutGrid, Clock, ShieldCheck, Zap, Ship, Camera, Waves, Wind, Plus, Minus } from 'lucide-vue-next';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { useCurrency } from '@/composables/useCurrency';
import { useToast } from '@/composables/useToast';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const { formatPrice } = useCurrency();
const { show: showToast } = useToast();

const props = defineProps<{
    about: any;
    heroSlides: any[];
    featuredTours: any[];
    latestPosts: any[];
    galleryItems: any[];
    featuredReviews: any[];
    favoriteSpots: any[];
    fleet: any[];
    services: any[];
}>();

const contactForm = useForm({
    name: '',
    email: '',
    message: '',
});

const submitContact = () => {
    contactForm.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => contactForm.reset(),
    });
};

const scrollRef = ref<HTMLElement | null>(null);

const scroll = (direction: 'left' | 'right') => {
    if (scrollRef.value) {
        const scrollAmount = 300;
        scrollRef.value.scrollBy({
            left: direction === 'left' ? -scrollAmount : scrollAmount,
            behavior: 'smooth'
        });
    }
};

const page = usePage<any>();
const layout = computed(() => (page.props.auth?.user || page.url.startsWith('/dashboard')) ? AppHeaderLayout : GuestLayout);

const fleetScrollRef = ref<HTMLElement | null>(null);

const scrollFleet = (direction: 'left' | 'right') => {
    if (fleetScrollRef.value) {
        const scrollAmount = 300;
        fleetScrollRef.value.scrollBy({
            left: direction === 'left' ? -scrollAmount : scrollAmount,
            behavior: 'smooth'
        });
    }
};

const toursScrollRef = ref<HTMLElement | null>(null);

const scrollTours = (direction: 'left' | 'right') => {
    if (toursScrollRef.value) {
        const scrollAmount = 300;
        toursScrollRef.value.scrollBy({
            left: direction === 'left' ? -scrollAmount : scrollAmount,
            behavior: 'smooth'
        });
    }
};

const servicesScrollRef = ref<HTMLElement | null>(null);

const scrollServices = (direction: 'left' | 'right') => {
    if (servicesScrollRef.value) {
        const scrollAmount = 300;
        servicesScrollRef.value.scrollBy({
            left: direction === 'left' ? -scrollAmount : scrollAmount,
            behavior: 'smooth'
        });
    }
};

const expandedServices = ref<Record<number, boolean>>({});

const toggleServiceDescription = (id: number) => {
    expandedServices.value[id] = !expandedServices.value[id];
};

const searchQuery = ref('');
const activeSlide = ref(0);

const carouselImages = props.heroSlides?.length
    ? props.heroSlides.map((s: any) => ({ title: s.title, img: s.image_url }))
    : [
        { title: 'Maasai Mara', img: 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=800&auto=format' },
        { title: 'Amboseli Savanna', img: 'https://images.unsplash.com/photo-1516422213484-21437ef33ebb?q=80&w=800&auto=format' },
        { title: 'Diani Beach', img: 'https://images.unsplash.com/photo-1589315834339-ad7481ecfed9?q=80&w=2124' },
        { title: 'Samburu Wild', img: 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?q=80&w=800&auto=format' },
    ];

let carouselInterval: any;

const nextSlide = () => {
    activeSlide.value = (activeSlide.value + 1) % carouselImages.length;
};

const prevSlide = () => {
    activeSlide.value = (activeSlide.value - 1 + carouselImages.length) % carouselImages.length;
};

// Tactical Swipe Intelligence
let touchStartX = 0;
let touchEndX = 0;

const handleTouchStart = (e: TouchEvent) => {
    touchStartX = e.changedTouches[0].screenX;
};

const handleTouchEnd = (e: TouchEvent) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
};

const handleSwipe = () => {
    const swipeThreshold = 50;
    if (touchEndX < touchStartX - swipeThreshold) {
        nextSlide();
    }
    if (touchEndX > touchStartX + swipeThreshold) {
        prevSlide();
    }
};

onMounted(() => {
    carouselInterval = setInterval(nextSlide, 8000);
});

onUnmounted(() => {
    if (carouselInterval) clearInterval(carouselInterval);
});


const search = () => {
    router.get(route('tours.index'), { search: searchQuery.value }, { preserveState: true });
};

// Define spots for the gallery using props
const favoriteSpots = computed(() => props.favoriteSpots || []);

const handleEnquireCheckout = (service: any) => {
    if (!page.props.auth?.user) {
        showToast('Authentication Required: Please login or register to checkout services.', 'error', 5000);
        return;
    }
    router.get(route('services.index'), { service: service.id });
};
</script>

<template>
    <Head title="Premium Safari Expeditions in Kenya">
        <meta name="description" content="Experience the untamed beauty of East Africa with Big Simba Safaris. Elite 4x4 expeditions, luxury savanna stays, and authentic wildlife encounters in Maasai Mara, Amboseli, and more." />
        <meta property="og:title" content="Big Simba Safaris - Elite Kenyan Expeditions" />
        <meta property="og:description" content="Join the pride for the ultimate Kenyan safari. Tactical 4x4 mobility, expert scouts, and cinematic wildlife reconnaissance." />
        <meta property="og:image" content="/images/og-safari.jpg" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="author" content="Big Simba Safaris Command" />
        <meta name="keywords" content="Big Simba Safaris, Kenya Safari, Maasai Mara, 4x4 Safari, Luxury Kenya Travel, East Africa Expeditions" />
        
        <!-- JSON-LD Structured Data for LLM/Search Intelligence -->
        <component :is="'script'" type="application/ld+json" v-html="JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'TravelAgency',
          'name': 'Big Simba Safaris',
          'description': 'Elite Kenyan Safari Agency specializing in tactical 4x4 expeditions and luxury wildlife reconnaissance across East Africa.',
          'url': 'https://bigsimbasafaris.com',
          'telephone': '+254726159925',
          'address': {
            '@type': 'PostalAddress',
            'addressCountry': 'KE',
            'addressLocality': 'Nairobi'
          },
          'sameAs': [
            'https://instagram.com/bigsimbasafaris',
            'https://twitter.com/bigsimbasafaris'
          ],
          'priceRange': '$$$',
          'openingHoursSpecification': [
            {
              '@type': 'OpeningHoursSpecification',
              'dayOfWeek': ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
              'opens': '00:00',
              'closes': '23:59'
            }
          ]
        })"></component>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    </Head>

    <component :is="layout">
        <!-- Hero Section -->
        <div 
            class="relative h-[95vh] md:h-screen min-h-[550px] md:min-h-[600px] w-full overflow-hidden bg-black z-40 rounded-b-[2rem] md:rounded-b-[6rem] -mb-20 md:-mb-32 shadow-[0_30px_100px_rgba(0,0,0,0.8)] border-b border-white/5"
            @touchstart="handleTouchStart"
            @touchend="handleTouchEnd"
        >
            <!-- Background Images (Transitioning) -->
            <div 
                v-for="(item, idx) in carouselImages" 
                :key="idx"
                class="absolute inset-0 transition-opacity duration-[2000ms] ease-in-out"
                :class="activeSlide === idx ? 'opacity-1000 z-0' : 'opacity-0 z-[-1]'"
            >
                <img fetchpriority="high" 
                    :src="item.img" 
                    alt="Safari Background"
                    class="h-full w-full object-cover brightness-[0.3] scale-105"
                />
            </div>

            <!-- Hero Content -->
            <div class="relative h-full flex flex-col items-center justify-center text-center px-4 text-white z-10 pt-20">
                <div class="animate-in fade-in slide-in-from-bottom-10 duration-[1500ms] mb-16 select-none group w-full px-8 flex flex-col items-center">
                     <p class="text-[10px] md:text-xs font-black tracking-[0.3em] md:tracking-[0.5em] uppercase text-safari-gold mb-8 drop-shadow-[0_2px_15px_rgba(202,138,4,0.3)] whitespace-nowrap opacity-1000">- EXPERIENCE AFRICA LIKE A KING. //</p>
                    <h1 class="font-display text-5xl sm:text-6xl md:text-[5.5rem] xl:text-[7.5rem] font-black tracking-[0.05em] md:tracking-[0.1em] leading-[0.9] md:leading-[0.85] uppercase relative drop-shadow-2xl">
                        {{ $t('BIG SIMBA') }}
                        <span class="block text-safari-gold mt-2 md:mt-1 tracking-tighter transition-all duration-700 opacity-95 scale-x-95 md:scale-x-90">{{ $t('SAFARIS') }}</span>
                    </h1>

                    <form @submit.prevent="search" class="mt-8 w-full max-w-3xl animate-in fade-in slide-in-from-bottom duration-1000 delay-300">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-6 md:left-8 flex items-center pointer-events-none">
                                <Search class="w-4 h-4 md:w-5 md:h-5 text-safari-gold" aria-hidden="true" style="stroke-width: 2.5;" />
                            </div>
                            <input 
                                type="text" 
                                v-model="searchQuery" 
                                :placeholder="$t('SEARCH MISSION...')" 
                                class="w-full bg-white/[0.03] backdrop-blur-3xl border border-white/10 pl-12 md:pl-16 pr-6 py-4 md:py-6 text-[10px] md:text-xs font-black tracking-[0.2em] uppercase text-white placeholder:text-gray-500 focus:border-safari-gold/50 focus:bg-white/[0.07] outline-none transition-all duration-700 rounded-sm shadow-2xl"
                            />
                        </div>
                        
                        <!-- Quick Intelligence Tags -->
                        <div class="mt-6 flex flex-wrap justify-center gap-3 md:gap-5 text-[9px] md:text-[11px] font-black uppercase tracking-[0.2em] opacity-80">
                             <span class="text-safari-gold/80">{{ $t('Hot Recon') }}:</span>
                             <button type="button" @click="searchQuery = 'Maasai Mara'; search()" class="hover:text-safari-gold transition-all">Mara</button>
                             <button type="button" @click="searchQuery = 'Diani'; search()" class="hover:text-safari-gold transition-all">Diani</button>
                             <button type="button" @click="searchQuery = 'Amboseli'; search()" class="hover:text-safari-gold transition-all">Amboseli</button>
                             <button type="button" @click="searchQuery = 'Samburu'; search()" class="hover:text-safari-gold transition-all">Samburu</button>
                        </div>
                    </form>

                    <div class="mt-8 animate-in fade-in slide-in-from-bottom duration-1000 delay-700 flex flex-col items-center">
                         <button 
                             @click="router.get(route('tours.index'))"
                             prefetch
                             aria-label="Explore the Safari Portfolio"
                             class="group flex items-center gap-1.5 md:gap-3 px-4 py-2 md:px-8 md:py-4 bg-safari-gold border-2 border-safari-gold rounded-sm hover:scale-x-110 transition-all duration-500 shadow-[0_0_40px_rgba(212,155,62,0.3)]"
                         >
                              <Car class="w-3.5 h-3.5 md:w-5 md:h-5 text-black group-hover:translate-x-2 transition-all duration-500" aria-hidden="true" />
                              <span class="text-[8px] md:text-xs font-black uppercase tracking-[0.15em] md:tracking-[0.3em] text-black">Explore the Safari</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 h-[40%] bg-gradient-to-t from-black via-black/40 to-transparent z-15 pointer-events-none"></div>

            <div class="absolute right-6 top-1/2 -translate-y-1/2 hidden md:flex flex-col gap-6 text-sm font-bold opacity-100 tracking-widest z-40">
                 <div v-for="(img, idx) in carouselImages" :key="idx" class="flex items-center gap-4 group cursor-pointer" @click="activeSlide = idx">
                    <span :class="activeSlide === idx ? 'opacity-1000 text-safari-gold' : 'group-hover:opacity-1000'">{{ (idx + 1).toString().padStart(2, '0') }}</span>
                    <div class="h-[1px] transition-all duration-500 bg-white" :class="activeSlide === idx ? 'w-8 bg-safari-gold' : 'w-2 group-hover:w-4'"></div>
                 </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="relative z-10 pt-32 bg-[#050505]">
            <!-- 01: Safari Story Section -->
            <section v-if="about" id="about" class="py-12 md:py-36 px-6 md:px-16 container mx-auto text-white overflow-hidden relative border-b border-white/5">
                <div class="absolute -right-20 top-20 text-[6rem] md:text-[160px] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Story</div>
                <div class="absolute left-6 md:left-10 top-16 md:top-36 text-2xl md:text-5xl font-black opacity-5 select-none text-safari-gold">01</div>
                 
                 <div class="text-center mb-10 md:mb-24 relative z-10 px-4" v-reveal>
                     <p class="text-[9px] md:text-sm font-bold tracking-[0.3em] uppercase text-safari-gold mb-3 md:mb-4">- {{ about.subtitle }} //</p>
                     <h2 class="font-display text-2xl md:text-5xl font-black uppercase tracking-tight">{{ about.title }}</h2>
                 </div>

                 <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
                    <div class="relative group" v-reveal="{ delay: 200 }">
                         <div class="aspect-square md:aspect-[5/4] overflow-hidden rounded-sm border border-white/5 relative z-10 shadow-2xl">
                              <img :src="about.image_1" :alt="`${about.title} - Safari Reconnaissance`" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110" loading="lazy" />
                              <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90"></div>
                         </div>
                         <div v-if="about.image_2" class="absolute -bottom-12 -right-12 w-2/3 aspect-video overflow-hidden rounded-sm border-4 border-[#050505] z-20 shadow-[0_20px_50px_rgba(0,0,0,0.8)] hidden md:block">
                              <img :src="about.image_2" :alt="`${about.title} Narrative Accent`" class="h-full w-full object-cover" loading="lazy" />
                         </div>
                         <div class="absolute -top-10 -left-10 w-44 h-44 border border-safari-gold/20 rounded-full animate-pulse z-0"></div>
                    </div>

                    <div class="space-y-8 relative z-10" v-reveal="{ delay: 400 }">
                         <div class="space-y-6">
                              <p class="text-[13px] font-normal text-gray-300 leading-relaxed max-w-xl border-l-[2px] border-safari-gold/30 pl-6 py-1">
                                   {{ about.content }}
                              </p>
                              <div class="grid grid-cols-2 gap-8 pt-8 border-t border-white/5">
                                   <div v-if="about.experience_years" class="space-y-1">
                                         <p class="text-3xl font-black text-safari-gold tracking-tighter">{{ about.experience_years }}</p>
                                         <p class="text-xs font-black uppercase tracking-[0.3em] opacity-100">Years of Dominance</p>
                                   </div>
                                   <div v-if="about.tours_completed" class="space-y-1">
                                         <p class="text-3xl font-black text-white tracking-tighter">{{ about.tours_completed }}+</p>
                                         <p class="text-xs font-black uppercase tracking-[0.3em] opacity-100">Crated Expeditions</p>
                                   </div>
                              </div>
                         </div>
                         <div class="pt-8">
                              <Link :href="route('tours.index')" prefetch class="text-sm font-black uppercase tracking-[0.4em] text-safari-gold hover:text-white transition-all duration-700 inline-flex items-center gap-4 group/btn" aria-label="Join The Pride Registry">
                                   Join The Pride Registry <ArrowRight class="w-4 h-4 group-hover/btn:translate-x-3 transition-transform" aria-hidden="true" />
                              </Link>
                         </div>
                    </div>
                </div>
            </section>

            <!-- 02: Favorite Spots Section -->
            <section id="locales" class="py-24 md:py-36 px-6 md:px-16 container mx-auto text-white border-b border-white/5 relative overflow-hidden">
                <div class="absolute -right-20 top-20 text-[8rem] md:text-[160px] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Locales</div>
                <div class="absolute left-6 md:left-10 top-24 md:top-36 text-3xl md:text-5xl font-black opacity-5 select-none text-safari-gold">02</div>
                 
                 <div class="text-center mb-16 md:mb-24 relative z-10" v-reveal>
                     <p class="text-[10px] md:text-sm font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Strategic Destinations //</p>
                     <h2 class="font-display text-3xl md:text-5xl font-black uppercase tracking-tight">Favorite Spots</h2>
                 </div>

                <div class="relative group/locales z-10 w-full overflow-hidden px-2 md:px-0">
                    <!-- Mobile Navigation Buttons -->
                    <button 
                        @click="scroll('left')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-6 h-10 bg-black/30 backdrop-blur-sm border-y border-r border-white/10 rounded-r-sm flex items-center justify-center text-safari-gold opacity-50 transition-all md:hidden"
                        aria-label="Previous locations"
                    >
                        <ChevronLeft class="w-3.5 h-3.5" aria-hidden="true" />
                    </button>
                    <button 
                        @click="scroll('right')"
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-6 h-10 bg-black/30 backdrop-blur-sm border-y border-l border-white/10 rounded-l-sm flex items-center justify-center text-safari-gold opacity-50 transition-all md:hidden"
                        aria-label="Next locations"
                    >
                        <ChevronRight class="w-3.5 h-3.5" aria-hidden="true" />
                    </button>

                    <div 
                        ref="scrollRef"
                        class="flex md:grid md:grid-cols-4 gap-6 overflow-x-auto md:overflow-visible pb-12 md:pb-0 scroll-smooth no-scrollbar snap-x snap-mandatory"
                    >
                         <Link 
                            v-for="(spot, index) in favoriteSpots" 
                            :key="index"
                            v-reveal="{ delay: index * 100 }"
                            :href="route('destinations.show', { slug: spot.slug })"
                            prefetch
                            class="flex-none w-[290px] md:w-auto h-[400px] md:aspect-[3/4] md:h-auto group relative overflow-hidden rounded-sm border border-white/10 bg-black shadow-2xl transition-all duration-700 md:hover:-translate-y-2 uppercase snap-center"
                            :aria-label="`View ${spot.name}`"
                        >
                            <img :src="spot.image" :alt="`Destination: ${spot.name}`" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110" loading="lazy" />
                            <div class="absolute inset-x-0 bottom-0 p-8 bg-gradient-to-t from-black/95 to-transparent">
                                <h3 class="font-display text-lg font-bold uppercase tracking-wider mb-1">{{ spot.name }}</h3>
                                <p class="text-xs opacity-90 uppercase tracking-widest italic line-clamp-2 leading-relaxed">{{ spot.overview }}</p>
                                <div class="h-[1.5px] w-12 bg-safari-gold mt-4 transition-all duration-500 md:group-hover:w-full"></div>
                            </div>
                        </Link>
                    </div>
                </div>

                <div class="text-center mt-20 relative z-10">
                     <Link :href="route('destinations.index')" class="text-sm font-black uppercase tracking-[0.4em] text-gray-200 hover:text-safari-gold transition-all duration-700 inline-flex items-center gap-4 group/btn" aria-label="View All Tactical Destinations">
                          View All Tactical Destinations <ArrowRight class="w-4 h-4 group-hover/btn:translate-x-3 transition-transform" aria-hidden="true" />
                     </Link>
                </div>
            </section>

            <!-- 03: Featured Packages Section -->
            <section id="expeditions" class="py-24 md:py-36 px-6 md:px-16 container mx-auto text-white relative overflow-hidden border-b border-white/5">
                <div class="absolute -left-20 top-20 text-[8rem] md:text-[160px] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Elite</div>
                <div class="absolute right-10 top-24 md:top-36 text-3xl md:text-5xl font-black opacity-5 select-none text-safari-gold">03</div>
                
                <div class="space-y-24 relative z-10">
                <div class="text-center mb-24 relative z-10" v-reveal>
                    <p class="text-sm font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Selected Expeditions //</p>
                    <h2 class="font-display text-4xl md:text-5xl font-black uppercase tracking-tight">Featured Packages</h2>
                </div>

                <div class="relative group/tours z-10 w-full overflow-hidden px-2 md:px-0">
                    <!-- Mobile Navigation Buttons -->
                    <button 
                        @click="scrollTours('left')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-6 h-10 bg-black/30 backdrop-blur-sm border-y border-r border-white/10 rounded-r-sm flex items-center justify-center text-safari-gold opacity-50 transition-all md:hidden"
                        aria-label="Previous expeditions"
                    >
                        <ChevronLeft class="w-3.5 h-3.5" aria-hidden="true" />
                    </button>
                    <button 
                        @click="scrollTours('right')"
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-6 h-10 bg-black/30 backdrop-blur-sm border-y border-l border-white/10 rounded-l-sm flex items-center justify-center text-safari-gold opacity-50 transition-all md:hidden"
                        aria-label="Next expeditions"
                    >
                        <ChevronRight class="w-3.5 h-3.5" aria-hidden="true" />
                    </button>

                    <div 
                        ref="toursScrollRef"
                        class="flex md:grid md:grid-cols-3 gap-8 overflow-x-auto md:overflow-visible pb-12 md:pb-0 scroll-smooth no-scrollbar snap-x snap-mandatory"
                    >
                        <Link v-for="(tour, index) in featuredTours" :key="tour.id" v-reveal="{ delay: index * 100 }" :href="route('tours.show', { slug: tour.slug })" prefetch class="flex-none w-[290px] md:w-auto group relative aspect-[3/4] overflow-hidden rounded-sm border border-white/10 shadow-xl transition-all duration-1000 md:hover:scale-[1.05] md:hover:z-20 flex flex-col justify-end snap-center">
                            <img :src="tour.images?.[0]" :alt="`${tour.name} - Expedition Assets`" class="absolute inset-0 h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110" loading="lazy" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-90 group-hover:opacity-100 transition-opacity duration-700"></div>
                            
                            <div class="relative z-10 p-8 translate-y-6 group-hover:translate-y-0 transition-transform duration-700">
                                 <p class="text-[10px] font-black uppercase tracking-[0.3em] text-safari-gold mb-2">{{ tour.duration }}</p>
                                 <h3 class="font-display text-xl font-black uppercase tracking-tight text-white leading-tight mb-4 group-hover:text-safari-gold transition-colors">{{ tour.name }}</h3>
                                 <div class="flex items-center justify-between pt-4 border-t border-white/20">
                                      <p class="text-base font-black text-safari-gold uppercase tracking-tighter">{{ formatPrice(tour.price) }}</p>
                                      <span class="text-[10px] flex items-center gap-2 font-black uppercase tracking-[0.2em] text-white/50 group-hover:text-white transition-colors">Details <ArrowRight class="w-3 h-3" aria-hidden="true" /></span>
                                 </div>
                            </div>
                        </Link>
                    </div>
                </div>
                    <div class="text-center mt-20">
                         <Link :href="route('tours.index')" class="text-sm font-black uppercase tracking-[0.4em] text-gray-200 hover:text-safari-gold transition-all duration-700 inline-flex items-center gap-4 group/btn" aria-label="Explore Master Registry">
                              Explore Master Registry <ArrowRight class="w-4 h-4 group-hover/btn:translate-x-3 transition-transform" aria-hidden="true" />
                         </Link>
                    </div>
                </div>
            </section>

            <!-- 04: Our Fleet Section -->
            <section id="logistics" class="py-44 px-8 md:px-16 container mx-auto text-white relative overflow-hidden border-b border-white/5">
                <div class="absolute -right-20 top-20 text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Fleet</div>
                <div class="absolute left-10 top-44 text-6xl font-black opacity-5 select-none text-safari-gold">04</div>
                
                <div class="text-center mb-24 relative z-10" v-reveal>
                    <p class="text-sm font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Tactical Mobility Units //</p>
                    <h2 class="font-display text-4xl md:text-5xl font-black uppercase tracking-tight">Our Fleet</h2>
                </div>

                <div class="relative group/fleet z-10 w-full overflow-hidden px-2 md:px-0">
                    <!-- Mobile Navigation Buttons -->
                    <button 
                        @click="scrollFleet('left')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-6 h-10 bg-black/30 backdrop-blur-sm border-y border-r border-white/10 rounded-r-sm flex items-center justify-center text-safari-gold opacity-50 transition-all md:hidden"
                        aria-label="Previous vehicles"
                    >
                        <ChevronLeft class="w-3.5 h-3.5" aria-hidden="true" />
                    </button>
                    <button 
                        @click="scrollFleet('right')"
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-6 h-10 bg-black/30 backdrop-blur-sm border-y border-l border-white/10 rounded-l-sm flex items-center justify-center text-safari-gold opacity-50 transition-all md:hidden"
                        aria-label="Next vehicles"
                    >
                        <ChevronRight class="w-3.5 h-3.5" aria-hidden="true" />
                    </button>

                    <div 
                        ref="fleetScrollRef"
                        class="flex md:grid md:grid-cols-3 gap-8 overflow-x-auto md:overflow-visible pb-12 md:pb-0 scroll-smooth no-scrollbar snap-x snap-mandatory"
                    >
                        <div v-for="(vehicle, index) in fleet" :key="vehicle.id" v-reveal="{ delay: index * 100 }" class="flex-none w-[330px] md:w-auto group relative aspect-[3/4] lg:aspect-[4/5] overflow-hidden rounded-sm border border-white/10 bg-black shadow-2xl transition-all duration-700 snap-center">
                             <img :src="vehicle.image" :alt="`Tactical Asset: ${vehicle.name}`" class="absolute inset-0 h-full w-full object-cover opacity-80 transition-transform duration-1000 group-hover:scale-110 group-hover:opacity-100" loading="lazy" />
                             <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent mix-blend-multiply"></div>
                             <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                             
                             <div class="absolute inset-x-0 bottom-0 p-6 md:p-8 flex flex-col justify-end">
                                  <p class="text-[8px] md:text-xs font-black uppercase tracking-[0.4em] text-safari-gold mb-2 md:mb-3 drop-shadow-md">{{ vehicle.drive_type }}</p>
                                  <h3 class="font-display text-xl md:text-3xl font-black uppercase tracking-tight text-white mb-4 md:mb-6 leading-none drop-shadow-lg">{{ vehicle.name }}</h3>
                                  
                                  <div class="grid grid-cols-3 gap-2 border-y border-white/20 py-4 mb-4">
                                       <div class="text-center">
                                            <p class="text-[8px] font-black opacity-60 uppercase tracking-widest mb-1">Per Hour</p>
                                            <p class="text-[10px] md:text-sm font-black text-safari-gold">{{ vehicle.price_per_hour > 0 ? formatPrice(vehicle.price_per_hour) : '--' }}</p>
                                       </div>
                                       <div class="text-center border-x border-white/10">
                                            <p class="text-[8px] font-black opacity-60 uppercase tracking-widest mb-1">Per Day</p>
                                            <p class="text-[10px] md:text-sm font-black text-safari-gold">{{ vehicle.price_per_day > 0 ? formatPrice(vehicle.price_per_day) : '--' }}</p>
                                       </div>
                                       <div class="text-center">
                                            <p class="text-[8px] font-black opacity-60 uppercase tracking-widest mb-1">Safari</p>
                                            <p class="text-[10px] md:text-sm font-black text-safari-gold">{{ vehicle.price_per_safari_drive > 0 ? formatPrice(vehicle.price_per_safari_drive) : '--' }}</p>
                                       </div>
                                  </div>
                                  
                                  <div class="flex items-center justify-between pt-2">
                                       <div class="flex items-center gap-2 text-xs font-black tracking-[0.2em] uppercase text-white drop-shadow-md">
                                            <Users class="w-4 h-4 text-safari-gold" aria-hidden="true" /> {{ vehicle.capacity }}
                                       </div>
                                        <Link :href="route('fleet-hire.index')" class="text-xs flex items-center gap-2 font-black uppercase tracking-[0.2em] text-safari-gold hover:text-white transition-all group/link shrink-0" aria-label="Request Unit">
                                             Request Unit <ArrowRight class="w-3 h-3 group-hover/link:translate-x-1.5 transition-transform" aria-hidden="true" />
                                        </Link>
                                  </div>
                             </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-20 relative z-10">
                     <Link :href="route('fleet-hire.index')" class="text-sm font-black uppercase tracking-[0.4em] text-gray-200 hover:text-safari-gold transition-all duration-700 inline-flex items-center gap-4 group/btn" aria-label="View All Tactical Units">
                          View All Tactical Units <ArrowRight class="w-4 h-4 group-hover/btn:translate-x-3 transition-transform" aria-hidden="true" />
                     </Link>
                </div>
            </section>

            <!-- 05: Strategic Services Section -->
            <section v-if="services?.length > 0" id="support" class="py-44 px-8 md:px-16 container mx-auto text-white relative overflow-hidden border-b border-white/5">
                <div class="absolute -left-20 top-20 text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Support</div>
                <div class="absolute right-10 top-44 text-6xl font-black opacity-5 select-none text-safari-gold">05</div>
                
                <div class="text-center mb-24 relative z-10" v-reveal>
                    <p class="text-sm font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Strategic Mission Support //</p>
                    <h2 class="font-display text-4xl md:text-5xl font-black uppercase tracking-tight">Extended Services</h2>
                </div>

                <div class="relative group/services z-10 w-full overflow-hidden px-2 md:px-0">
                    <!-- Mobile Navigation Buttons -->
                    <button 
                        @click="scrollServices('left')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-6 h-10 bg-black/30 backdrop-blur-sm border-y border-r border-white/10 rounded-r-sm flex items-center justify-center text-safari-gold opacity-50 transition-all md:hidden"
                        aria-label="Previous services"
                    >
                        <ChevronLeft class="w-3.5 h-3.5" aria-hidden="true" />
                    </button>
                    <button 
                        @click="scrollServices('right')"
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-6 h-10 bg-black/30 backdrop-blur-sm border-y border-l border-white/10 rounded-l-sm flex items-center justify-center text-safari-gold opacity-50 transition-all md:hidden"
                        aria-label="Next services"
                    >
                        <ChevronRight class="w-3.5 h-3.5" aria-hidden="true" />
                    </button>

                    <div 
                        ref="servicesScrollRef"
                        class="flex md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-2 gap-8 overflow-x-auto md:overflow-visible pb-12 md:pb-0 scroll-smooth no-scrollbar snap-x snap-mandatory"
                    >
                        <div v-for="(service, index) in services" :key="service.id" v-reveal="{ delay: index * 100 }" class="flex-none w-[300px] md:w-auto group bg-white/[0.02] border border-white/5 hover:border-safari-gold/20 transition-all duration-700 flex flex-col shadow-2xl rounded-sm overflow-hidden snap-center">
                             <!-- Image Header -->
                             <div class="relative h-40 md:h-64 lg:h-72 w-full shrink-0 overflow-hidden">
                                  <img :src="service.image" :alt="`Operational Service: ${service.name}`" class="h-full w-full object-cover transition-transform duration-[2000ms] group-hover:scale-[1.10]" loading="lazy" />
                                  <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/40 to-transparent">
                                       <div class="p-3 md:p-4 flex items-center gap-2 md:gap-3">
                                            <div class="w-7 h-7 md:w-10 md:h-10 bg-black/50 backdrop-blur-md border border-safari-gold/20 flex items-center justify-center rounded-sm transition-transform duration-500 group-hover:scale-110">
                                                 <Zap class="w-3 md:w-4 h-3 md:h-4 text-safari-gold" aria-hidden="true" />
                                            </div>
                                            <h3 class="font-display text-xs md:text-lg font-black uppercase tracking-tight text-white group-hover:text-safari-gold transition-colors">{{ service.name }}</h3>
                                       </div>
                                  </div>
                             </div>
                             
                             <!-- Service Details -->
                             <div class="p-4 md:p-10 lg:p-12 flex flex-col flex-grow">
                                  <div class="relative">
                                      <p 
                                        :class="[
                                            'text-[11px] md:text-base font-normal text-gray-300 leading-relaxed mb-4 transition-all duration-500',
                                            !expandedServices[service.id] ? 'line-clamp-2 md:line-clamp-4' : ''
                                        ]"
                                      >{{ service.description }}</p>
                                      <button 
                                        v-if="service.description?.length > 100"
                                        @click="toggleServiceDescription(service.id)"
                                        class="text-[9px] md:text-xs font-black uppercase tracking-widest text-safari-gold hover:text-white transition-all flex items-center gap-2 mb-6"
                                        :aria-label="expandedServices[service.id] ? `Read less about ${service.name}` : `Read more about ${service.name}`"
                                      >
                                          <component :is="expandedServices[service.id] ? Minus : Plus" class="w-3 h-3" aria-hidden="true" />
                                          {{ expandedServices[service.id] ? 'Read Less' : 'Read More' }}
                                      </button>
                                  </div>
    
                                  <!-- Detailed Prices/Features -->
                                  <div v-if="service.features" class="flex-grow space-y-2 mb-6 hidden md:block">
                                       <p class="text-xs font-black uppercase tracking-widest text-safari-gold mb-3 ml-1">- Specifications & Pricing</p>
                                       <ul class="space-y-2">
                                            <li v-for="(feature, idx) in service.features.split('\n').filter(Boolean).filter(String)" :key="idx" class="flex items-start gap-3">
                                                 <div class="w-1 h-1 bg-safari-gold mt-1.5 shrink-0 opacity-90"></div>
                                                 <span class="text-sm opacity-100 leading-tight">{{ feature }}</span>
                                            </li>
                                       </ul>
                                  </div>
                                  <div v-else class="flex-grow"></div>
    
                                  <!-- Pricing & Action -->
                                  <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                                       <div class="flex flex-col">
                                            <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest text-white/40 mb-1">Mission Unit Price</p>
                                            <p class="text-sm md:text-xl font-black text-safari-gold">{{ formatPrice(service.price) }}</p>
                                       </div>
                                       <Link :href="route('contact')" class="size-10 md:size-12 bg-safari-gold hover:bg-white text-black flex items-center justify-center transition-all group/btn rounded-sm" aria-label="Inquire about this service">
                                            <ArrowRight class="w-4 h-4 md:w-5 md:h-5 group-hover/btn:translate-x-1.5 transition-transform" aria-hidden="true" />
                                       </Link>
                                  </div>
                             </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-20 relative z-10">
                     <Link :href="route('services.index')" class="text-sm font-black uppercase tracking-[0.4em] text-gray-200 hover:text-safari-gold transition-all duration-700 inline-flex items-center gap-4 group/btn" aria-label="Review All Operational Services">
                          Review All Operational Services <ArrowRight class="w-4 h-4 group-hover/btn:translate-x-3 transition-transform" aria-hidden="true" />
                     </Link>
                </div>
            </section>

            <!-- 06: Wildlife Gallery Section -->
            <section id="gallery" class="py-16 md:py-44 px-6 md:px-16 bg-[#030303] text-white relative overflow-hidden border-b border-white/5">
                <div class="absolute -left-20 top-20 text-[10rem] md:text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Gallery</div>
                <div class="absolute right-10 top-16 md:top-44 text-4xl md:text-6xl font-black opacity-5 select-none text-safari-gold">06</div>
                
                <div class="container mx-auto relative z-10">
                    <div class="text-center mb-16 md:mb-24 relative z-10 px-4" v-reveal>
                         <p class="text-[9px] md:text-sm font-bold tracking-[0.3em] uppercase text-safari-gold mb-3 md:mb-4">- Visual Records //</p>
                         <h2 class="font-display text-3xl md:text-5xl font-black uppercase tracking-tight">Wildlife Gallery</h2>
                         <div class="mt-4">
                              <Link :href="route('gallery.index')" class="text-[10px] md:text-sm font-black uppercase tracking-[0.4em] opacity-80 hover:opacity-1000 transition-opacity border-b border-white/10 pb-2" aria-label="View Full Wildlife Archive">View Full Archive</Link>
                         </div>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-16 md:mb-32 px-4 md:px-0">
                        <div v-for="(item, index) in galleryItems" :key="item.id" v-reveal="{ delay: index * 50 }" class="group relative aspect-square md:aspect-[3/4] overflow-hidden rounded-sm cursor-pointer border border-white/10 shadow-xl transition-all duration-1000 md:hover:scale-[1.05] md:hover:z-20">
                             <img :src="item.url" :alt="`Wildlife Gallery: ${item.title}`" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110" loading="lazy" />
                             <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/95 via-black/40 to-transparent opacity-0 group-hover:opacity-1000 transition-opacity duration-700">
                                  <p class="text-xs font-black uppercase tracking-[0.4em] text-safari-gold mb-1">{{ item.type }}</p>
                                  <p class="text-sm font-black uppercase tracking-[0.1em] text-white">{{ item.title }}</p>
                             </div>
                        </div>
                    </div>

                    <!-- Sub-section: Tactical Chronicles (Blog) -->
                    <div class="pt-24 md:pt-32 border-t border-white/5">
                        <div class="text-center mb-16 md:mb-24 relative z-10 px-4" v-reveal>
                            <p class="text-[9px] md:text-sm font-bold tracking-[0.3em] uppercase text-safari-gold mb-3 md:mb-4">- Field Records //</p>
                            <h2 class="font-display text-3xl md:text-5xl font-black uppercase tracking-tight">Latest Chronicles</h2>
                            <div class="mt-4">
                                <Link :href="route('blog.index')" class="text-[10px] md:text-sm font-black uppercase tracking-[0.4em] opacity-80 hover:opacity-1000 transition-opacity border-b border-white/10 pb-2" aria-label="Open Full Intel File">Open Full Intel File</Link>
                            </div>
                        </div>

                        <div class="flex md:grid md:grid-cols-3 gap-8 overflow-x-auto md:overflow-visible pb-12 md:pb-0 no-scrollbar snap-x snap-mandatory px-4 md:px-0">
                                <div v-for="(post, index) in latestPosts" :key="post.id" v-reveal="{ delay: index * 100 }" class="flex-shrink-0 w-[310px] md:w-auto snap-start p-8 border border-white/5 bg-white/[0.01] hover:border-safari-gold/20 transition-all group">
                                     <p class="text-xs opacity-100 uppercase tracking-[0.4em] mb-4">{{ post.created_at_human }}</p>
                                     <h4 class="font-display text-lg font-black uppercase tracking-tight mb-4 group-hover:text-safari-gold transition-colors">{{ post.title }}</h4>
                                     <p class="text-xs font-normal text-gray-300 leading-relaxed line-clamp-2 mb-6">{{ post.excerpt }}</p>
                                     <Link :href="route('blog.show', { slug: post.slug })" class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest opacity-80 group-hover:opacity-1000 group-hover:text-safari-gold transition-all" :aria-label="`Read Record: ${post.title}`">
                                          Read Record <ArrowRight class="w-3 h-3 translate-x-0 group-hover:translate-x-2 transition-transform" aria-hidden="true" />
                                     </Link>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>

             <!-- 07: Witness Reports Section -->
             <section id="debriefings" class="py-16 md:py-44 px-6 md:px-16 container mx-auto text-white overflow-hidden relative border-b border-white/5">
                  <div class="absolute -right-20 top-20 text-[10rem] md:text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Review</div>
                  <div class="absolute left-10 top-16 md:top-44 text-4xl md:text-6xl font-black opacity-5 select-none text-safari-gold">07</div>
                 
                 <div class="text-center mb-16 md:mb-24 relative z-10 px-4" v-reveal>
                      <p class="text-[9px] md:text-sm font-bold tracking-[0.3em] uppercase text-safari-gold mb-3 md:mb-4">- Pride Recognition //</p>
                      <h2 class="font-display text-3xl md:text-5xl font-black uppercase tracking-tight">Witness Reports</h2>
                 </div>

                 <div class="flex md:grid md:grid-cols-3 gap-6 md:gap-10 relative z-10 overflow-x-auto md:overflow-visible pb-12 md:pb-0 no-scrollbar snap-x snap-mandatory px-4 md:px-0">
                      <div v-for="(review, index) in featuredReviews" :key="review.id" v-reveal="{ delay: index * 100 }" class="flex-shrink-0 w-[340px] md:w-auto snap-start p-8 md:p-10 border border-white/5 bg-white/[0.02] rounded-sm space-y-6 md:space-y-8 group hover:border-safari-gold/30 transition-all">
                           <div class="flex items-center gap-4">
                                <div v-if="review.user?.profile_photo_url || review.user?.avatar" class="w-10 h-10 rounded-full border border-safari-gold/20 overflow-hidden shrink-0">
                                     <img :src="review.user?.profile_photo_url || review.user?.avatar" :alt="review.user?.name" class="w-full h-full object-cover" />
                                </div>
                                <div v-else class="w-10 h-10 rounded-full bg-safari-gold/10 border border-safari-gold/20 flex items-center justify-center shrink-0">
                                     <span class="text-xs font-black text-safari-gold uppercase">{{ review.user?.name?.charAt(0) || 'G' }}</span>
                                </div>
                                <div>
                                     <p class="text-sm font-black uppercase tracking-widest">{{ review.user?.name }}</p>
                                     <p class="text-xs opacity-100 uppercase tracking-widest leading-none mt-1">On {{ review.tour?.name }}</p>
                                </div>
                           </div>
                           <div class="flex items-center gap-1">
                                <Star v-for="i in 5" :key="i" :class="['w-2.5 h-2.5', i <= review.rating ? 'text-safari-gold fill-safari-gold' : 'text-white/5']" />
                           </div>
                           <p class="text-sm font-normal text-gray-300 leading-relaxed line-clamp-4">"{{ review.comment }}"</p>
                      </div>
                 </div>
            </section>

            <!-- 08: Wild Intel (FAQs) Section -->
            <section class="py-12 md:py-44 px-6 md:px-16 container mx-auto text-white overflow-hidden relative border-b border-white/5">
                <div class="absolute -left-20 top-20 text-[6rem] md:text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Intel</div>
                <div class="absolute right-10 top-16 md:top-44 text-4xl md:text-6xl font-black opacity-5 select-none text-safari-gold">08</div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 md:gap-24 relative z-10 px-4 md:px-0">
                    <div class="space-y-8 md:space-y-12" v-reveal>
                         <div class="space-y-4 md:space-y-6">
                              <p class="text-[9px] md:text-sm font-black tracking-[0.6em] uppercase text-safari-gold mb-3 md:mb-4 opacity-100">- Wild Intel //</p>
                              <h2 class="font-display text-4xl md:text-7xl font-black uppercase tracking-tighter leading-[0.85] text-white">Frequently <br/><span class="text-safari-gold">Asked.</span></h2>
                         </div>
                         <p class="text-[11px] md:text-sm font-normal text-gray-400 leading-relaxed max-w-md italic">
                              "Precision in planning prevents failure in the wild. Review our tactical intelligence for your upcoming mission."
                         </p>
                    </div>

                    <div class="space-y-3 md:space-y-4 pt-4 md:pt-0">
                         <div v-for="(faq, i) in [
                              { q: 'How do I secure a mission (booking)?', a: 'Access our Expedition Registry, select your target tour, and complete the digital reconnaissance form. Our command center will verify and confirm within 24 hours.' },
                              { q: 'What mobility assets are available?', a: 'We deploy custom-built 4x4 Land Cruisers and specialized heavy-terrain units, all satellite-linked and maintained for absolute savanna dominance.' },
                              { q: 'Who leads the pride (guides)?', a: 'Our scouts are KPSGA-certified elite navigators with over 10 years of operational experience in East Africa\'s wild zones.' }
                         ]" :key="i" class="p-5 md:p-8 bg-white/[0.02] border border-white/5 hover:border-safari-gold/20 transition-all group">
                              <h4 class="font-display text-[11px] md:text-sm font-black uppercase tracking-wider text-safari-gold mb-3 md:mb-4 flex items-center gap-3 md:gap-4">
                                   <span class="w-1.5 h-1.5 rounded-full bg-safari-gold animate-pulse"></span>
                                   {{ faq.q }}
                              </h4>
                              <p class="text-[10px] md:text-xs font-normal text-gray-400 leading-relaxed pl-4 md:pl-5">{{ faq.a }}</p>
                         </div>
                    </div>
                </div>
            </section>

            <!-- 09: Situation Report (Contact) Section -->
            <section id="contact" class="py-12 md:py-44 px-6 md:px-16 container mx-auto text-white overflow-hidden relative">
                <div class="absolute -right-20 top-20 text-[10rem] md:text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Report</div>
                <div class="absolute left-10 top-24 md:top-44 text-4xl md:text-6xl font-black opacity-5 select-none text-safari-gold">09</div>

                <div class="max-w-5xl mx-auto relative z-10">
                    <div class="text-center mb-12 md:mb-24 px-4" v-reveal>
                         <p class="text-[10px] md:text-sm font-bold tracking-[0.3em] uppercase text-safari-gold mb-3 md:mb-4">- Establish Comms //</p>
                         <h2 class="font-display text-4xl md:text-6xl font-black uppercase tracking-tight leading-none mb-4 md:mb-6">Situation Report</h2>
                         <p class="text-[9px] md:text-xs font-light opacity-80 uppercase tracking-widest italic">Prepare your reconnaissance data for mission briefing.</p>
                    </div>

                    <form @submit.prevent="submitContact" class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 px-4 md:px-0" v-reveal="{ delay: 200 }">
                         <div class="space-y-1 md:space-y-2">
                              <label for="contact-name" class="text-[9px] md:text-xs font-black uppercase tracking-widest opacity-80 ml-2 md:ml-4">Personnel Name</label>
                              <input id="contact-name" v-model="contactForm.name" type="text" placeholder="ID REQUIRED" required class="w-full bg-white/5 border border-white/10 p-4 md:p-6 text-[10px] md:text-sm font-black uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all rounded-sm placeholder:opacity-30" />
                              <p v-if="contactForm.errors.name" class="text-[9px] md:text-sm text-red-400 uppercase font-black ml-2 md:ml-4">{{ contactForm.errors.name }}</p>
                         </div>
                         <div class="space-y-1 md:space-y-2">
                              <label for="contact-email" class="text-[9px] md:text-xs font-black uppercase tracking-widest opacity-80 ml-2 md:ml-4">Comms Channel (Email)</label>
                              <input id="contact-email" v-model="contactForm.email" type="email" placeholder="SECURE PROTOCOL" required class="w-full bg-white/5 border border-white/10 p-4 md:p-6 text-[10px] md:text-sm font-black uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all rounded-sm placeholder:opacity-30" />
                              <p v-if="contactForm.errors.email" class="text-[9px] md:text-sm text-red-400 uppercase font-black ml-2 md:ml-4">{{ contactForm.errors.email }}</p>
                         </div>
                         <div class="md:col-span-2 space-y-1 md:space-y-2">
                              <label for="contact-message" class="text-[9px] md:text-xs font-black uppercase tracking-widest opacity-80 ml-2 md:ml-4">Mission Brief (Message)</label>
                              <textarea id="contact-message" v-model="contactForm.message" rows="3" placeholder="DESCRIBE YOUR OBJECTIVES..." required class="w-full bg-white/5 border border-white/10 p-4 md:p-6 text-[10px] md:text-sm font-black uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all rounded-sm resize-none placeholder:opacity-30"></textarea>
                              <p v-if="contactForm.errors.message" class="text-[9px] md:text-sm text-red-400 uppercase font-black ml-2 md:ml-4">{{ contactForm.errors.message }}</p>
                         </div>
                         <div class="md:col-span-2 pt-4 md:pt-8">
                              <button type="submit" :disabled="contactForm.processing" class="w-full bg-safari-gold text-black py-4 md:py-8 text-[11px] md:text-sm font-black uppercase tracking-[0.4em] md:tracking-[0.5em] hover:bg-white transition-all shadow-2xl rounded-sm disabled:opacity-90">
                                   {{ contactForm.processing ? 'Establishing Link...' : 'Send Situation Report' }}
                              </button>
                              <p v-if="contactForm.recentlySuccessful" class="text-[9px] md:text-xs text-safari-gold uppercase font-black text-center mt-4 md:mt-6 animate-pulse tracking-[0.2em]">Intel Sent Successfully. Command Replying shortly.</p>
                         </div>
                    </form>
                </div>
            </section>
        </div>
    </component>
</template>

<style scoped>
.animate-in {
    animation-duration: 1s;
    animation-fill-mode: both;
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slide-in-from-bottom {
    from { transform: translateY(20px); }
    to { transform: translateY(0); }
}

</style>
