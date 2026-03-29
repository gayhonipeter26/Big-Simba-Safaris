<script setup lang="ts">
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import { Target, ShieldCheck, Settings, Briefcase, Zap, X, MapPin, ArrowLeft } from 'lucide-vue-next';
import { ref, computed, onMounted } from 'vue';
import { useCurrency } from '@/composables/useCurrency';
import { useToast } from '@/composables/useToast';
import { router } from '@inertiajs/vue3';

const { show: showToast } = useToast();
const { formatPrice } = useCurrency();

const props = defineProps<{
    services: any[];
}>();

const page = usePage<any>();
const layout = computed(() => (page.props.auth?.user || page.url.startsWith('/dashboard')) ? AppHeaderLayout : GuestLayout);

const selectedService = ref<any>(null);

const form = useForm({
    service_id: '',
    name: (page.props as any).auth?.user?.name || '',
    email: (page.props as any).auth?.user?.email || '',
    phone: '',
    start_date: '',
    end_date: '',
    message: '',
    payment_method: 'mpesa',
});

const openOrderModal = (service: any) => {
    if (!page.props.auth?.user) {
        showToast('Authentication Required: Please login or register to proceed with authorization.', 'error', 5000);
        return;
    }
    selectedService.value = service;
    form.service_id = service.id;
};

const closeOrderModal = () => {
    selectedService.value = null;
    form.reset();
};

const submit = () => {
    form.post(route('services.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeOrderModal();
        }
    });
};

onMounted(() => {
    const params = new URLSearchParams(window.location.search);
    const serviceId = params.get('service');
    if (serviceId) {
        const targetService = props.services.find(s => s.id.toString() === serviceId);
        if (targetService) {
            openOrderModal(targetService);
        }
    }
});
</script>

<template>
    <Head title="Tactical Deployments - Extended Support Services">
        <meta name="description" content="Strategic safari support services from Big Simba. Professional guides, expert reconnaissance, and tactical mission coordination for your Kenyan expedition." />
        <meta property="og:title" content="Tactical Deployments - Big Simba Services" />
        <meta property="og:description" content="Beyond the vehicle. Elite strategic support for your Kenyan safari mission." />
        <meta name="keywords" content="Safari Services, Kenya Safari Guides, Strategic Mission Support, Big Simba Tactical Deployments" />
        <component is="script" type="application/ld+json" v-html="JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'ItemList',
          itemListElement: services?.map((s, i) => ({
            '@type': 'ListItem',
            position: i + 1,
            name: s?.name,
            description: s?.description
          })) || []
        })"></component>
    </Head>

    <component :is="layout">
         <main class="bg-[#050505] min-h-screen py-16 md:py-44 px-4 md:px-20 max-w-[1800px] mx-auto text-white">
            <!-- Hero -->
            <div class="mb-16 md:mb-24 space-y-4 md:space-y-6 pt-24 lg:pt-0 px-2 md:px-0">
                <Link :href="page.props.auth?.user ? route('dashboard') : route('home')" class="inline-flex items-center gap-2 text-[10px] md:text-xs font-black uppercase tracking-[0.2em] text-safari-gold/60 hover:text-safari-gold transition-all mb-2 md:mb-4 group/back">
                     <ArrowLeft class="w-3 h-3 group-hover/back:-translate-x-1.5 transition-transform" /> {{ page.props.auth?.user ? 'Back to Dashboard' : 'Back to Welcome' }}
                </Link>
                <p class="text-[9px] md:text-sm font-black uppercase tracking-[0.5em] text-safari-gold animate-in fade-in slide-in-from-left duration-1000 opacity-80">- TACTICAL DEPLOYMENTS //</p>
                <h1 class="font-display text-4xl md:text-9xl font-black uppercase tracking-tighter leading-[0.75]">Extended <br class="hidden md:block"/><span class="text-safari-gold/20">Services.</span></h1>
                <p class="text-lg md:text-xl font-light opacity-70 max-w-3xl leading-relaxed italic text-white/50">
                     Strategic mission support precisely engineered for absolute safari dominance. Request a unit to support your wilderness operations.
                </p>
            </div>

            <!-- Service Grid -->
            <div class="flex md:grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-12 overflow-x-auto md:overflow-visible pb-12 md:pb-0 no-scrollbar snap-x snap-mandatory px-4 md:px-0">
                 <div v-for="service in services" :key="service.id" class="flex-shrink-0 w-[340px] md:w-auto snap-start group bg-white/[0.01] border border-white/5 rounded-sm overflow-hidden hover:border-safari-gold/20 transition-all duration-1000 flex flex-col shadow-2xl">
                        <!-- Image Header -->
                        <div class="relative h-64 w-full shrink-0 overflow-hidden bg-black">
                            <img :src="service.image || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072'" :alt="service.name" class="w-full h-full object-cover grayscale-[0.8] group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-110 opacity-80" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 z-10 flex items-center gap-3">
                                 <div class="w-12 h-12 bg-black/50 backdrop-blur-md border border-safari-gold/20 flex items-center justify-center rounded-sm transition-transform duration-500 group-hover:scale-110">
                                      <Zap class="w-5 h-5 text-safari-gold" />
                                 </div>
                                 <h3 class="font-display text-2xl font-black uppercase tracking-tight text-white group-hover:text-safari-gold transition-colors">{{ service.name }}</h3>
                            </div>
                       </div>
                       
                        <!-- Details -->
                       <div class="p-8 lg:p-10 flex flex-col flex-grow space-y-8">
                            <p class="text-base font-normal text-gray-300 leading-relaxed line-clamp-3">{{ service.description }}</p>

                            <div class="space-y-4">
                               <p class="text-xs font-black uppercase tracking-widest text-safari-gold/70">- Specifications</p>
                               <ul class="space-y-3">
                                    <li v-for="(feature, idx) in (service.features ? service.features.split('\n').filter(Boolean).filter(String) : [])" :key="idx" class="flex items-start gap-3">
                                         <Target class="w-3 h-3 text-safari-gold mt-0.5 shrink-0 opacity-80" />
                                         <span class="text-sm opacity-70 leading-tight">{{ feature }}</span>
                                    </li>
                               </ul>
                            </div>

                            <div class="space-y-6 pt-6 border-t border-white/5 mt-auto">
                                 <div class="flex items-end justify-between">
                                      <div class="space-y-1">
                                           <div class="flex items-center gap-2">
                                                <span class="text-xs font-black uppercase tracking-widest opacity-80">{{ service.pricing_tier || 'Base Rate' }}</span>
                                           </div>
                                           <div class="text-3xl font-black text-safari-gold uppercase tracking-tighter">{{ formatPrice(service.starting_price) }}</div>
                                      </div>
                                 </div>

                                 <button @click="openOrderModal(service)" class="w-full py-5 border border-white/10 text-sm font-black uppercase tracking-[0.4em] hover:bg-safari-gold hover:text-black hover:border-safari-gold transition-all rounded-sm">
                                      REQUEST AUTHORIZATION
                                 </button>
                            </div>
                       </div>
                 </div>
            </div>

            <!-- Why Big Simba Services -->
            <section class="mt-24 md:mt-44 grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 py-24 md:py-32 border-y border-white/5">
                <div v-for="(item, i) in [
                     { icon: Settings, title: 'Tactical Precision', desc: 'Our services are perfectly calibrated to deliver seamless operations in the harshest environments.' },
                     { icon: ShieldCheck, title: 'Mission Guaranteed', desc: 'Uncompromised accountability. We deliver elite-level strategic value for every request.' },
                     { icon: Briefcase, title: 'Dedicated Units', desc: 'Expert personnel coordinating your logistics seamlessly across the savanna.' }
                ]" :key="i" class="space-y-6 p-8 bg-white/[0.01] border border-transparent hover:border-white/5 rounded-sm transition-all shadow-xl group">
                     <div class="w-14 h-14 rounded-full border border-white/5 flex items-center justify-center bg-black group-hover:border-safari-gold transition-all duration-700">
                          <component :is="item.icon" class="w-5 h-5 text-safari-gold opacity-80 group-hover:opacity-100 transition-opacity" />
                     </div>
                     <h4 class="font-display text-xl font-black uppercase tracking-tight text-white">{{ item.title }}</h4>
                     <p class="text-[13px] font-normal text-gray-300 leading-relaxed text-white/60">{{ item.desc }}</p>
                </div>
           </section>
        </main>

        <!-- Order Modal -->
        <Transition enter-active-class="transition duration-500 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-300 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
             <div v-if="selectedService" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/95 backdrop-blur-sm">
                  <div class="bg-card border border-white/10 w-full max-w-7xl rounded-sm overflow-hidden flex flex-col lg:flex-row shadow-[0_0_100px_rgba(202,138,4,0.1)]">
                       <!-- Side Panel -->
                       <div class="lg:w-2/5 relative h-80 lg:h-auto bg-black text-white">
                            <img :src="selectedService.image || 'https://images.unsplash.com/photo-1516422275817-ad668b121f02?q=80&w=2072'" class="absolute inset-0 w-full h-full object-cover opacity-90" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
                            <div class="absolute bottom-12 left-12 right-12 space-y-6">
                                 <p class="text-sm font-black uppercase tracking-[0.5em] text-safari-gold">Service Inducted</p>
                                 <h2 class="font-display text-5xl font-black uppercase tracking-tighter">{{ selectedService.name }}</h2>
                                 
                                 <div class="pt-8 border-t border-white/5">
                                      <p class="text-sm font-black uppercase tracking-[0.5em] text-safari-gold mb-4">Base Rate</p>
                                      <div class="flex items-center justify-between p-6 bg-white/5 border border-white/5 rounded-sm">
                                           <span class="text-xs font-black uppercase tracking-widest opacity-90">{{ selectedService.pricing_tier || 'Total Estimation' }}</span>
                                           <span class="text-2xl font-black text-safari-gold">{{ formatPrice(selectedService.starting_price) }}</span>
                                      </div>
                                 </div>
                            </div>
                       </div>

                       <!-- Form -->
                       <div class="lg:w-3/5 p-8 lg:p-16 relative overflow-y-auto max-h-[90vh] lg:max-h-none text-white bg-black md:bg-[#050505]">
                            <button @click="closeOrderModal" class="absolute top-10 right-10 p-3 border border-white/5 bg-white/5 rounded-full hover:bg-white/10 transition-all text-white">
                                 <X class="w-5 h-5" />
                            </button>

                            <div class="space-y-12">
                                 <div class="space-y-4">
                                      <h3 class="font-display text-3xl font-black uppercase tracking-tighter text-white">Induction Request</h3>
                                      <p class="text-sm opacity-70 uppercase tracking-[0.4em] italic font-bold">Transmit your operational details</p>
                                 </div>

                                 <form @submit.prevent="submit" class="space-y-10">
                                      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                           <div class="space-y-4">
                                                <label class="text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1">Identity Name</label>
                                                <input v-model="form.name" type="text" placeholder="Scout Identifier" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                           </div>
                                           <div class="space-y-4">
                                                <label class="text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1">Communications Relay</label>
                                                <input v-model="form.email" type="email" placeholder="Email Address" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                           </div>
                                      </div>

                                      <div class="space-y-4">
                                           <label class="text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1">Tactical Contact (Phone)</label>
                                           <input v-model="form.phone" type="text" placeholder="+254..." class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-sm font-light tracking-[0.2em] outline-none focus:border-safari-gold transition-all text-white" required />
                                      </div>

                                      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                           <div class="space-y-4">
                                                <label class="text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1">Deployment Start</label>
                                                <input v-model="form.start_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" required />
                                           </div>
                                           <div class="space-y-4">
                                                <label class="text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1">End of Mission</label>
                                                <input v-model="form.end_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-sm px-6 py-4 text-xs font-black uppercase tracking-widest outline-none focus:border-safari-gold transition-all text-white" />
                                           </div>
                                      </div>

                                      <div class="space-y-4">
                                           <label class="text-sm font-black uppercase tracking-[0.4em] opacity-80 ml-1">Mission Details / Modifications</label>
                                           <textarea v-model="form.message" rows="4" placeholder="SPECIFY ANY CUSTOM REQUIREMENTS..." class="w-full bg-white/5 border border-white/10 rounded-sm p-6 text-sm font-light outline-none focus:border-safari-gold transition-all leading-relaxed uppercase placeholder:opacity-10 text-white"></textarea>
                                      </div>

                                      <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-8 border-t border-white/10">
                                           <div class="flex items-center gap-4 opacity-70">
                                                <ShieldCheck class="w-5 h-5 text-safari-gold" />
                                                <span class="text-xs font-black uppercase tracking-widest text-white">Big Simba Network Secured</span>
                                           </div>
                                           <button type="submit" :disabled="form.processing" class="w-full md:w-auto px-16 py-6 bg-safari-gold text-black text-base font-black uppercase tracking-[0.5em] hover:bg-white transition-all transform hover:-translate-y-1 shadow-2xl rounded-sm">
                                                {{ form.processing ? 'TRANSMITTING...' : 'AUTHORIZE SERVICE' }}
                                           </button>
                                      </div>
                                 </form>
                            </div>
                       </div>
                  </div>
             </div>
        </Transition>
    </component>
</template>

<style scoped>
main { font-family: 'Outfit', sans-serif; }
</style>
