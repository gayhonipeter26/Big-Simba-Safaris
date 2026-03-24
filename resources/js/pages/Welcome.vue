<script setup lang="ts">
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { Camera, MapPin, Compass, Search, Star, Newspaper, MessageSquare, Video, Car, Settings, ArrowRight, Zap, Users, Layers, Target } from 'lucide-vue-next';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { router } from '@inertiajs/vue3';

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

const page = usePage<any>();
const user = page.props.auth?.user;

const searchQuery = ref('');
const activeSlide = ref(0);

const carouselImages = props.heroSlides?.length
    ? props.heroSlides.map((s: any) => ({ title: s.title, img: s.image_url }))
    : [
        { title: 'Maasai Mara', img: 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071' },
        { title: 'Amboseli Savanna', img: 'https://images.unsplash.com/photo-1516422213484-21437ef33ebb?q=80&w=2670' },
        { title: 'Diani Beach', img: 'https://images.unsplash.com/photo-1589315834339-ad7481ecfed9?q=80&w=2124' },
        { title: 'Samburu Wild', img: 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?q=80&w=2072' },
    ];

let carouselInterval: any;

const nextSlide = () => {
    activeSlide.value = (activeSlide.value + 1) % carouselImages.length;
};

const prevSlide = () => {
    activeSlide.value = (activeSlide.value - 1 + carouselImages.length) % carouselImages.length;
};

onMounted(() => {
    carouselInterval = setInterval(nextSlide, 4000);
});

onUnmounted(() => {
    clearInterval(carouselInterval);
});

const search = () => {
    router.get(route('tours.index'), { search: searchQuery.value }, { preserveState: true });
};

// Define spots for the gallery using props
const favoriteSpots = computed(() => props.favoriteSpots || []);

</script>

<template>
    <Head title="Welcome to Big Simba Safaris">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    </Head>

    <GuestLayout>
        <!-- Hero Section -->
        <div class="relative h-screen w-full overflow-hidden bg-black z-40 rounded-b-[6rem] -mb-32 shadow-[0_30px_100px_rgba(0,0,0,0.8)] border-b border-white/5">
            <!-- Background Images (Transitioning) -->
            <div 
                v-for="(item, idx) in carouselImages" 
                :key="idx"
                class="absolute inset-0 transition-opacity duration-[2000ms] ease-in-out"
                :class="activeSlide === idx ? 'opacity-100 z-0' : 'opacity-0 z-[-1]'"
            >
                <img 
                    :src="item.img" 
                    alt="Safari Background"
                    class="h-full w-full object-cover brightness-[0.3] scale-105"
                />
            </div>

            <!-- Hero Content -->
            <div class="relative h-full flex flex-col items-center justify-center text-center px-4 text-white z-10 pt-20">
                <div class="animate-in fade-in slide-in-from-bottom-10 duration-[1500ms] mb-16 select-none group w-full px-8 flex flex-col items-center">
                     <p class="text-[12px] md:text-[14px] font-black tracking-[0.4em] md:tracking-[0.6em] uppercase text-safari-gold mb-12 drop-shadow-[0_2px_15px_rgba(202,138,4,0.3)] whitespace-nowrap opacity-100">- EXPERIENCE AFRICA LIKE A KING. //</p>
                    <h1 class="text-5xl md:text-[8.5rem] xl:text-[10.5rem] font-black tracking-[0.1em] md:tracking-[0.15em] leading-[0.85] uppercase relative drop-shadow-2xl">
                        BIG SIMBA
                        <span class="block text-safari-gold mt-1 tracking-tighter transition-all duration-700 opacity-95 scale-x-90">SAFARIS</span>
                    </h1>

                    <form @submit.prevent="search" class="mt-12 w-full max-w-5xl animate-in fade-in slide-in-from-bottom duration-1000 delay-300">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-8 flex items-center pointer-events-none">
                                <Search class="w-5 h-5 text-safari-gold opacity-40 group-focus-within:opacity-100 transition-opacity" />
                            </div>
                            <input 
                                type="text" 
                                v-model="searchQuery" 
                                placeholder="WHERE DOES YOUR SOUL LONG TO BE? (E.G. MARA, DIANI, AMBOSELI)" 
                                class="w-full bg-white/[0.03] backdrop-blur-3xl border border-white/10 pl-20 pr-32 py-8 text-[11px] font-black tracking-[0.2em] uppercase text-white placeholder:opacity-30 focus:border-safari-gold/50 focus:bg-white/[0.07] outline-none transition-all duration-700 rounded-sm shadow-2xl"
                            />
                            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-safari-gold text-black px-8 py-4 text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white transition-all transform hover:scale-105 active:scale-95 duration-500">
                                Launch Scout
                            </button>
                        </div>
                        
                        <!-- Quick Intelligence Tags -->
                        <div class="mt-8 flex flex-wrap justify-center gap-8 text-[11px] font-black uppercase tracking-[0.3em] opacity-50">
                             <span class="text-safari-gold/80 opacity-100">Hot Recon:</span>
                             <button type="button" @click="searchQuery = 'Maasai Mara'; search()" class="hover:text-safari-gold hover:opacity-100 transition-all border-b border-transparent hover:border-safari-gold/40">Maasai Mara</button>
                             <button type="button" @click="searchQuery = 'Diani'; search()" class="hover:text-safari-gold hover:opacity-100 transition-all border-b border-transparent hover:border-safari-gold/40">Diani Beach</button>
                             <button type="button" @click="searchQuery = 'Amboseli'; search()" class="hover:text-safari-gold hover:opacity-100 transition-all border-b border-transparent hover:border-safari-gold/40">Amboseli</button>
                             <button type="button" @click="searchQuery = 'Samburu'; search()" class="hover:text-safari-gold hover:opacity-100 transition-all border-b border-transparent hover:border-safari-gold/40">Samburu</button>
                        </div>
                    </form>

                    <div class="mt-12 animate-in fade-in slide-in-from-bottom duration-1000 delay-700 flex flex-col items-center">
                         <div 
                            class="group relative cursor-pointer px-12 py-5 flex flex-col items-center gap-3 transition-all duration-700 hover:bg-white/[0.02] rounded-sm" 
                            @click="router.get(route('tours.index'))"
                        >
                              <!-- Animated Corner Accents -->
                              <div class="absolute top-0 left-0 w-2 h-2 border-t-[1.5px] border-l-[1.5px] border-safari-gold/30 group-hover:w-6 group-hover:h-6 group-hover:border-safari-gold transition-all duration-700"></div>
                              <div class="absolute top-0 right-0 w-2 h-2 border-t-[1.5px] border-r-[1.5px] border-safari-gold/30 group-hover:w-6 group-hover:h-6 group-hover:border-safari-gold transition-all duration-700"></div>
                              <div class="absolute bottom-0 left-0 w-2 h-2 border-b-[1.5px] border-l-[1.5px] border-safari-gold/30 group-hover:w-6 group-hover:h-6 group-hover:border-safari-gold transition-all duration-700"></div>
                              <div class="absolute bottom-0 right-0 w-2 h-2 border-b-[1.5px] border-r-[1.5px] border-safari-gold/30 group-hover:w-6 group-hover:h-6 group-hover:border-safari-gold transition-all duration-700"></div>

                              <div class="flex items-center gap-8">
                                   <div class="h-[1px] w-8 bg-gradient-to-r from-transparent via-safari-gold/20 to-safari-gold/40 group-hover:w-16 group-hover:to-safari-gold transition-all duration-1000"></div>
                                   <span class="text-[11px] font-black uppercase tracking-[1.2em] whitespace-nowrap text-white/50 group-hover:text-white group-hover:tracking-[1.4em] transition-all duration-1000">
                                        Explore The Wild
                                   </span>
                                   <div class="h-[1px] w-8 bg-gradient-to-l from-transparent via-safari-gold/20 to-safari-gold/40 group-hover:w-16 group-hover:to-safari-gold transition-all duration-1000"></div>
                              </div>
                              <span class="text-[8px] font-black uppercase tracking-[0.5em] text-safari-gold/30 group-hover:text-safari-gold transition-all duration-700">View Master expedition Register</span>
                         </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 h-[40%] bg-gradient-to-t from-black via-black/40 to-transparent z-15 pointer-events-none"></div>

            <div class="absolute right-6 top-1/2 -translate-y-1/2 hidden md:flex flex-col gap-6 text-[10px] font-bold opacity-30 tracking-widest z-40">
                 <div v-for="(img, idx) in carouselImages" :key="idx" class="flex items-center gap-4 group cursor-pointer" @click="activeSlide = idx">
                    <span :class="activeSlide === idx ? 'opacity-100 text-safari-gold' : 'group-hover:opacity-100'">{{ (idx + 1).toString().padStart(2, '0') }}</span>
                    <div class="h-[1px] transition-all duration-500 bg-white" :class="activeSlide === idx ? 'w-8 bg-safari-gold' : 'w-2 group-hover:w-4'"></div>
                 </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="relative z-10 pt-32 bg-[#050505]">
            <!-- 01: Safari Story Section -->
            <section v-if="about" id="about" class="py-28 px-8 md:px-16 container mx-auto text-white overflow-hidden relative border-b border-white/5">
                <div class="absolute -right-20 top-10 text-[15rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Story</div>
                <div class="absolute left-10 top-20 text-4xl font-black opacity-5 select-none text-safari-gold">01</div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
                    <div class="relative group">
                         <div class="aspect-[4/5] overflow-hidden rounded-sm border border-white/5 relative z-10 shadow-2xl">
                              <img :src="about.image_1 || 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071'" class="w-full h-full object-cover grayscale-[0.5] group-hover:grayscale-0 transition-all duration-[2000ms] group-hover:scale-110" />
                              <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
                         </div>
                         <div v-if="about.image_2" class="absolute -bottom-12 -right-12 w-2/3 aspect-video overflow-hidden rounded-sm border-4 border-[#050505] z-20 shadow-[0_20px_50px_rgba(0,0,0,0.8)] hidden md:block">
                              <img :src="about.image_2" class="w-full h-full object-cover grayscale-[0.3] group-hover:grayscale-0 transition-all duration-[1500ms]" />
                         </div>
                         <div class="absolute -top-10 -left-10 w-44 h-44 border border-safari-gold/20 rounded-full animate-pulse z-0"></div>
                    </div>

                    <div class="space-y-8 relative z-10">
                         <div class="space-y-4">
                              <p class="text-[9px] font-black tracking-[0.5em] uppercase text-safari-gold mb-2 opacity-70">// {{ about.subtitle }}</p>
                              <h2 class="text-4xl md:text-6xl font-black uppercase tracking-tighter leading-[0.85] text-white">{{ about.title }}</h2>
                         </div>
                         <div class="space-y-6">
                              <p class="text-[13px] font-light opacity-50 leading-relaxed italic max-w-xl border-l-[2px] border-safari-gold/30 pl-6 py-1">
                                   {{ about.content }}
                              </p>
                              <div class="grid grid-cols-2 gap-8 pt-8 border-t border-white/5">
                                   <div v-if="about.experience_years" class="space-y-1">
                                        <p class="text-3xl font-black text-safari-gold tracking-tighter">{{ about.experience_years }}</p>
                                        <p class="text-[8px] font-black uppercase tracking-[0.3em] opacity-30">Years of Dominance</p>
                                   </div>
                                   <div v-if="about.tours_completed" class="space-y-1">
                                        <p class="text-3xl font-black text-white tracking-tighter">{{ about.tours_completed }}+</p>
                                        <p class="text-[8px] font-black uppercase tracking-[0.3em] opacity-30">Crated Expeditions</p>
                                   </div>
                              </div>
                         </div>
                         <div class="pt-8">
                              <Link :href="route('tours.index')" class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold hover:text-white transition-all duration-700 inline-flex items-center gap-4 group/btn">
                                   Join The Pride Registry <ArrowRight class="w-4 h-4 group-hover/btn:translate-x-3 transition-transform" />
                              </Link>
                         </div>
                    </div>
                </div>
            </section>

            <!-- 02: Favorite Spots Section -->
            <section class="py-44 px-8 md:px-16 container mx-auto text-white border-b border-white/5 relative overflow-hidden">
                <div class="absolute -right-20 top-20 text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Locales</div>
                <div class="absolute left-10 top-44 text-6x1 font-black opacity-5 select-none text-safari-gold">02</div>
                
                <div class="text-center mb-24 relative z-10">
                    <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Strategic Destinations //</p>
                    <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight">Favorite Spots</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
                    <Link 
                        v-for="(spot, index) in favoriteSpots" 
                        :key="index"
                        :href="route('destinations.show', { slug: spot.slug })"
                        class="group relative aspect-[3/4] overflow-hidden rounded-sm cursor-pointer shadow-2xl transition-all duration-700 hover:-translate-y-2 uppercase"
                    >
                        <img :src="spot.image" :alt="spot.name" class="absolute inset-0 h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale-[0.5] group-hover:grayscale-0" />
                        <div class="absolute inset-x-0 bottom-0 p-8 bg-gradient-to-t from-black/90 to-transparent">
                            <h3 class="text-lg font-bold uppercase tracking-wider mb-1">{{ spot.name }}</h3>
                            <p class="text-[10px] opacity-60 uppercase tracking-widest italic line-clamp-2 leading-relaxed">{{ spot.overview }}</p>
                            <div class="h-[2px] w-12 bg-safari-gold mt-4 transition-all duration-500 group-hover:w-full"></div>
                        </div>
                    </Link>
                </div>
            </section>

            <!-- 03: Featured Packages Section -->
            <section class="py-44 px-8 md:px-16 container mx-auto text-white relative overflow-hidden border-b border-white/5">
                <div class="absolute -left-20 top-20 text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Elite</div>
                <div class="absolute right-10 top-44 text-6x1 font-black opacity-5 select-none text-safari-gold">03</div>
                
                <div class="space-y-24 relative z-10">
                    <div class="text-center mb-20">
                        <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Selected Expeditions //</p>
                        <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight">Featured Packages</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <Link v-for="tour in featuredTours" :key="tour.id" :href="route('tours.show', { slug: tour.slug })" class="group relative aspect-[3/4] overflow-hidden rounded-sm border border-white/10 shadow-xl transition-all duration-1000 hover:scale-[1.05] hover:z-20">
                            <img :src="tour.images?.[0] || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072'" :alt="tour.name" class="absolute inset-0 h-full w-full object-cover grayscale-[0.8] group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-110" />
                            <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/95 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                                 <p class="text-[8px] font-black uppercase tracking-[0.4em] text-safari-gold mb-1">{{ tour.duration }}</p>
                                 <p class="text-[11px] font-black uppercase tracking-[0.1em] text-white leading-tight mb-2">{{ tour.name }}</p>
                                 <p class="text-[10px] font-black text-safari-gold uppercase tracking-tighter">${{ parseFloat(tour.price).toLocaleString() }}</p>
                            </div>
                        </Link>
                    </div>
                    <div class="text-center mt-20">
                         <Link :href="route('tours.index')" class="text-[10px] font-black uppercase tracking-[0.4em] text-white/40 hover:text-safari-gold transition-all duration-700 inline-flex items-center gap-4 group/btn">
                              Explore Master Registry <ArrowRight class="w-4 h-4 group-hover/btn:translate-x-3 transition-transform" />
                         </Link>
                    </div>
                </div>
            </section>

            <!-- 04: Our Fleet Section -->
            <section class="py-44 px-8 md:px-16 container mx-auto text-white relative overflow-hidden border-b border-white/5">
                <div class="absolute -right-20 top-20 text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Fleet</div>
                <div class="absolute left-10 top-44 text-6x1 font-black opacity-5 select-none text-safari-gold">04</div>
                
                <div class="text-center mb-24 relative z-10">
                    <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Tactical Mobility Units //</p>
                    <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight">Our Fleet</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                    <div v-for="vehicle in fleet" :key="vehicle.id" class="group relative aspect-video overflow-hidden rounded-sm border border-white/5 bg-white/[0.02] shadow-2xl transition-all duration-700">
                         <img :src="vehicle.image || 'https://images.unsplash.com/photo-1533105079780-92b9be482077?q=80&w=2670'" class="absolute inset-0 h-full w-full object-cover grayscale-[0.8] group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-105" />
                         <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                         <div class="absolute bottom-0 left-0 right-0 p-8 space-y-2 translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                              <p class="text-[9px] font-black uppercase tracking-widest text-safari-gold">{{ vehicle.drive_type }}</p>
                              <h3 class="text-2xl font-black uppercase tracking-tighter text-white">{{ vehicle.name }}</h3>
                              <div class="flex items-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pt-4 border-t border-white/10">
                                   <div class="flex items-center gap-2 text-[8px] font-black tracking-widest uppercase opacity-40">
                                        <Users class="w-3 h-3" /> {{ vehicle.capacity }}
                                   </div>
                                    <Link :href="route('fleet-hire.index')" class="text-[8px] font-black uppercase tracking-[0.4em] text-safari-gold hover:text-white transition-all duration-700 inline-flex items-center gap-2">
                                         Request Unit <ArrowRight class="w-2.5 h-2.5" />
                                    </Link>
                              </div>
                         </div>
                    </div>
                </div>
            </section>

            <!-- 05: Strategic Services Section -->
            <section v-if="services?.length > 0" class="py-44 px-8 md:px-16 container mx-auto text-white relative overflow-hidden border-b border-white/5">
                <div class="absolute -left-20 top-20 text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Support</div>
                <div class="absolute right-10 top-44 text-6x1 font-black opacity-5 select-none text-safari-gold">05</div>
                
                <div class="text-center mb-24 relative z-10">
                    <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Strategic Mission Support //</p>
                    <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight">Extended Services</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative z-10">
                    <div v-for="service in services" :key="service.id" class="group p-8 bg-white/[0.02] border border-white/5 hover:border-safari-gold/20 transition-all duration-700 flex flex-col justify-between shadow-2xl">
                         <div class="space-y-6">
                              <div class="w-12 h-12 bg-safari-gold/10 border border-safari-gold/20 flex items-center justify-center rounded-sm transition-transform duration-500 group-hover:scale-110">
                                   <Zap class="w-5 h-5 text-safari-gold" />
                              </div>
                              <h3 class="text-xl font-black uppercase tracking-tight group-hover:text-safari-gold transition-colors">{{ service.name }}</h3>
                              <p class="text-[11px] font-light opacity-40 leading-relaxed italic line-clamp-3">{{ service.description }}</p>
                              
                              <div class="space-y-2 pt-4 border-t border-white/5">
                                   <div class="flex items-center gap-2">
                                        <Target class="w-2.5 h-2.5 text-safari-gold opacity-40" />
                                        <span class="text-[8px] font-black uppercase tracking-widest opacity-40">{{ service.pricing_tier }}</span>
                                   </div>
                                   <div class="text-[14px] font-black text-safari-gold uppercase tracking-tighter">${{ parseFloat(service.starting_price).toFixed(2) }}</div>
                              </div>
                         </div>
                         <div class="pt-8">
                              <Link :href="route('contact')" class="text-[9px] font-black uppercase tracking-[0.4em] text-white/40 hover:text-safari-gold transition-all duration-700 flex items-center gap-2 group/link">
                                   Induct Unit <ArrowRight class="w-3 h-3 translate-x-0 group-hover/link:translate-x-2 transition-transform" />
                              </Link>
                         </div>
                    </div>
                </div>
            </section>

            <!-- 06: Wildlife Gallery Section -->
            <section class="py-44 px-8 md:px-16 bg-[#030303] text-white relative overflow-hidden border-b border-white/5">
                <div class="absolute -left-20 top-20 text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Gallery</div>
                <div class="absolute right-10 top-44 text-6x1 font-black opacity-5 select-none text-safari-gold">06</div>
                
                <div class="container mx-auto relative z-10">
                    <div class="flex flex-col md:flex-row items-end justify-between mb-24 gap-8">
                         <div class="text-left">
                              <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Visual Records //</p>
                              <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight">Wildlife Gallery</h2>
                         </div>
                         <Link :href="route('gallery.index')" class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 hover:opacity-100 transition-opacity border-b border-white/10 pb-2">View Full Archive</Link>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-32">
                        <div v-for="item in galleryItems" :key="item.id" class="group relative aspect-[3/4] overflow-hidden rounded-sm cursor-pointer border border-white/10 shadow-xl transition-all duration-1000 hover:scale-[1.05] hover:z-20">
                             <img :src="item.url" class="absolute inset-0 h-full w-full object-cover grayscale-[0.8] group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-110" />
                             <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/95 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                                  <p class="text-[8px] font-black uppercase tracking-[0.4em] text-safari-gold mb-1">{{ item.type }}</p>
                                  <p class="text-[11px] font-black uppercase tracking-[0.1em] text-white">{{ item.title }}</p>
                             </div>
                        </div>
                    </div>

                    <!-- Sub-section: Tactical Chronicles (Blog) -->
                    <div class="pt-32 border-t border-white/5">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                            <div class="lg:col-span-1">
                                <p class="text-[10px] font-black tracking-[0.6em] uppercase text-safari-gold mb-6 opacity-70">// FIELD RECORDS</p>
                                <h3 class="text-4xl font-black uppercase tracking-tighter leading-none mb-8">Latest <br/><span class="text-white/20">Chronicles.</span></h3>
                                <p class="text-xs font-light opacity-40 leading-relaxed mb-10 max-w-xs italic">"Records from recent operational deployments and wildlife surveillance missions."</p>
                                <Link :href="route('blog.index')" class="text-[10px] font-black uppercase tracking-[0.4em] text-safari-gold hover:text-white transition-colors">Open Full Intel File</Link>
                            </div>
                            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div v-for="post in latestPosts" :key="post.id" class="p-8 border border-white/5 bg-white/[0.01] hover:border-safari-gold/20 transition-all group">
                                     <p class="text-[8px] opacity-30 uppercase tracking-[0.4em] mb-4">{{ post.created_at_human }}</p>
                                     <h4 class="text-lg font-black uppercase tracking-tight mb-4 group-hover:text-safari-gold transition-colors">{{ post.title }}</h4>
                                     <p class="text-xs font-light opacity-50 leading-relaxed line-clamp-2 mb-6">{{ post.excerpt }}</p>
                                     <Link :href="route('blog.show', { slug: post.slug })" class="inline-flex items-center gap-2 text-[9px] font-black uppercase tracking-widest opacity-40 group-hover:opacity-100 group-hover:text-safari-gold transition-all">
                                          Read Record <ArrowRight class="w-3 h-3 translate-x-0 group-hover:translate-x-2 transition-transform" />
                                     </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

             <!-- 07: Witness Reports Section -->
             <section class="py-44 px-8 md:px-16 container mx-auto text-white overflow-hidden relative border-b border-white/5">
                  <div class="absolute -right-20 top-20 text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Review</div>
                  <div class="absolute left-10 top-44 text-6x1 font-black opacity-5 select-none text-safari-gold">07</div>
                 
                 <div class="text-center mb-24 relative z-10">
                      <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Pride Recognition //</p>
                      <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight">Witness Reports</h2>
                 </div>

                 <div class="grid grid-cols-1 md:grid-cols-3 gap-10 relative z-10">
                      <div v-for="review in featuredReviews" :key="review.id" class="p-10 border border-white/5 bg-white/[0.02] rounded-sm space-y-8 group hover:border-safari-gold/30 transition-all">
                           <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-safari-gold/10 border border-safari-gold/20 flex items-center justify-center">
                                     <span class="text-[9px] font-black text-safari-gold uppercase">{{ review.user?.name.charAt(0) }}</span>
                                </div>
                                <div>
                                     <p class="text-[10px] font-black uppercase tracking-widest">{{ review.user?.name }}</p>
                                     <p class="text-[8px] opacity-30 uppercase tracking-widest leading-none mt-1">On {{ review.tour?.name }}</p>
                                </div>
                           </div>
                           <div class="flex items-center gap-1">
                                <Star v-for="i in 5" :key="i" :class="['w-2.5 h-2.5', i <= review.rating ? 'text-safari-gold fill-safari-gold' : 'text-white/5']" />
                           </div>
                           <p class="text-sm font-light opacity-60 leading-relaxed italic line-clamp-4">"{{ review.comment }}"</p>
                      </div>
                 </div>
            </section>

            <!-- 08: Wild Intel (FAQs) Section -->
            <section class="py-44 px-8 md:px-16 container mx-auto text-white overflow-hidden relative border-b border-white/5">
                <div class="absolute -left-20 top-20 text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Intel</div>
                <div class="absolute right-10 top-44 text-6x1 font-black opacity-5 select-none text-safari-gold">08</div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-24 relative z-10">
                    <div class="space-y-12">
                         <div class="space-y-6">
                              <p class="text-[10px] font-black tracking-[0.6em] uppercase text-safari-gold mb-4 opacity-70">// WILD INTEL</p>
                              <h2 class="text-5xl md:text-7xl font-black uppercase tracking-tighter leading-[0.85] text-white">Frequently <br/><span class="text-safari-gold/30">Asked.</span></h2>
                         </div>
                         <p class="text-sm font-light opacity-50 leading-relaxed max-w-md italic">
                              "Precision in planning prevents failure in the wild. Review our tactical intelligence for your upcoming mission."
                         </p>
                    </div>

                    <div class="space-y-4">
                         <div v-for="(faq, i) in [
                              { q: 'How do I secure a mission (booking)?', a: 'Access our Expedition Registry, select your target tour, and complete the digital reconnaissance form. Our command center will verify and confirm within 24 hours.' },
                              { q: 'What mobility assets are available?', a: 'We deploy custom-built 4x4 Land Cruisers and specialized heavy-terrain units, all satellite-linked and maintained for absolute savanna dominance.' },
                              { q: 'Who leads the pride (guides)?', a: 'Our scouts are KPSGA-certified elite navigators with over 10 years of operational experience in East Africa\'s wild zones.' }
                         ]" :key="i" class="p-8 bg-white/[0.02] border border-white/5 hover:border-safari-gold/20 transition-all group">
                              <h4 class="text-[11px] font-black uppercase tracking-wider text-safari-gold mb-4 flex items-center gap-4">
                                   <span class="w-1.5 h-1.5 rounded-full bg-safari-gold animate-pulse"></span>
                                   {{ faq.q }}
                              </h4>
                              <p class="text-xs font-light opacity-50 leading-relaxed pl-5">{{ faq.a }}</p>
                         </div>
                    </div>
                </div>
            </section>

            <!-- 09: Situation Report (Contact) Section -->
            <section id="contact" class="py-44 px-8 md:px-16 container mx-auto text-white overflow-hidden relative">
                <div class="absolute -right-20 top-20 text-[20rem] font-black opacity-[0.02] pointer-events-none select-none uppercase tracking-tighter">Report</div>
                <div class="absolute left-10 top-44 text-6x1 font-black opacity-5 select-none text-safari-gold">09</div>

                <div class="max-w-5xl mx-auto relative z-10">
                    <div class="text-center mb-24">
                         <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-safari-gold mb-4">- Establish Comms //</p>
                         <h2 class="text-5xl md:text-6xl font-black uppercase tracking-tight leading-none mb-6">Situation Report</h2>
                         <p class="text-xs font-light opacity-40 uppercase tracking-widest italic">Prepare your reconnaissance data for mission briefing.</p>
                    </div>

                    <form @submit.prevent="submitContact" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                         <div class="space-y-2">
                              <label class="text-[9px] font-black uppercase tracking-widest opacity-40 ml-4">Personnel Name</label>
                              <input v-model="contactForm.name" type="text" placeholder="IDENTIFICATION REQUIRED" required class="w-full bg-white/5 border border-white/10 p-6 text-[10px] font-black uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all rounded-sm" />
                              <p v-if="contactForm.errors.name" class="text-[8px] text-red-500 uppercase font-black ml-4">{{ contactForm.errors.name }}</p>
                         </div>
                         <div class="space-y-2">
                              <label class="text-[9px] font-black uppercase tracking-widest opacity-40 ml-4">Comms Channel (Email)</label>
                              <input v-model="contactForm.email" type="email" placeholder="SECURE PROTOCOL" required class="w-full bg-white/5 border border-white/10 p-6 text-[10px] font-black uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all rounded-sm" />
                              <p v-if="contactForm.errors.email" class="text-[8px] text-red-500 uppercase font-black ml-4">{{ contactForm.errors.email }}</p>
                         </div>
                         <div class="md:col-span-2 space-y-2">
                              <label class="text-[9px] font-black uppercase tracking-widest opacity-40 ml-4">Mission Brief (Message)</label>
                              <textarea v-model="contactForm.message" rows="4" placeholder="DESCRIBE YOUR OBJECTIVES..." required class="w-full bg-white/5 border border-white/10 p-6 text-[10px] font-black uppercase tracking-widest outline-none focus:border-safari-gold/50 transition-all rounded-sm"></textarea>
                              <p v-if="contactForm.errors.message" class="text-[8px] text-red-500 uppercase font-black ml-4">{{ contactForm.errors.message }}</p>
                         </div>
                         <div class="md:col-span-2 pt-8">
                              <button type="submit" :disabled="contactForm.processing" class="w-full bg-safari-gold text-black py-8 text-[11px] font-black uppercase tracking-[0.5em] hover:bg-white transition-all transform hover:-translate-y-2 shadow-2xl rounded-sm disabled:opacity-50">
                                   {{ contactForm.processing ? 'Establishing Link...' : 'Send Situation Report' }}
                              </button>
                              <p v-if="contactForm.recentlySuccessful" class="text-[9px] text-safari-gold uppercase font-black text-center mt-6 animate-pulse tracking-[0.2em]">Intel Sent Successfully. Command will respond shortly.</p>
                         </div>
                    </form>
                </div>
            </section>
        </div>
    </GuestLayout>
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

.font-sans {
    font-family: 'Outfit', sans-serif;
}
</style>
