<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Save, Info, Image as ImageIcon, Award, History } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Safari Story', href: '/admin/about' },
];

const props = defineProps<{
    about: any;
}>();

const form = useForm({
    title: props.about?.title || '',
    subtitle: props.about?.subtitle || '',
    content: props.about?.content || '',
    image_1: props.about?.image_1 || '',
    image_2: props.about?.image_2 || '',
    experience_years: props.about?.experience_years || '',
    tours_completed: props.about?.tours_completed || 0,
    is_active: !!props.about?.is_active,
});

const submit = () => {
    form.post(route('admin.about.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Refine Safari Story" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1400px] mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="text-3xl font-black uppercase tracking-tighter mb-2 text-safari-gold">Refine Safari Story</h1>
                      <p class="text-xs opacity-50 uppercase tracking-widest italic">Maintain the narrative soul of Big Simba</p>
                 </div>
                 <button @click="submit" :disabled="form.processing" class="px-8 py-4 bg-safari-gold text-black text-[10px] font-black uppercase tracking-widest hover:bg-white transition-all rounded-sm flex items-center gap-2 shadow-[0_0_30px_rgba(202,138,4,0.3)]">
                      <Save class="w-3 h-3" /> Sync Intelligence
                 </button>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 xl:grid-cols-3 gap-10">
                <!-- Left Column: Core Narrative -->
                <div class="xl:col-span-2 space-y-10">
                    <div class="bg-card border border-white/5 rounded-sm p-10 space-y-8 shadow-2xl">
                        <div class="flex items-center gap-4 text-safari-gold mb-4">
                             <Info class="w-4 h-4" />
                             <h2 class="text-xs font-black uppercase tracking-[0.4em]">Section Narratives</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                             <div class="space-y-4">
                                  <label class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40">Main Headline</label>
                                  <input v-model="form.title" type="text" class="w-full bg-white/5 border border-white/10 px-6 py-4 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all" required />
                             </div>
                             <div class="space-y-4">
                                  <label class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40">Subheader Registry</label>
                                  <input v-model="form.subtitle" type="text" class="w-full bg-white/5 border border-white/10 px-6 py-4 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all" />
                             </div>
                        </div>

                        <div class="space-y-4">
                             <label class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40">Mission Briefing (About Content)</label>
                             <textarea v-model="form.content" rows="12" class="w-full bg-white/5 border border-white/10 px-8 py-6 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20 flex-1 leading-loose"></textarea>
                        </div>
                    </div>

                    <div class="bg-card border border-white/5 rounded-sm p-10 space-y-8 shadow-2xl">
                        <div class="flex items-center gap-4 text-safari-gold mb-4">
                             <ImageIcon class="w-4 h-4" />
                             <h2 class="text-xs font-black uppercase tracking-[0.4em]">Visual Intelligence</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                             <div class="space-y-4">
                                  <label class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40">Primary Showcase Image</label>
                                  <input v-model="form.image_1" type="url" placeholder="HTTPS://..." class="w-full bg-white/5 border border-white/10 px-6 py-4 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" />
                             </div>
                             <div class="space-y-4">
                                  <label class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40">Tactical Offset Image</label>
                                  <input v-model="form.image_2" type="url" placeholder="HTTPS://..." class="w-full bg-white/5 border border-white/10 px-6 py-4 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all placeholder:opacity-20" />
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Strategic Metrics -->
                <div class="space-y-10">
                    <div class="bg-card border border-white/5 rounded-sm p-10 space-y-8 shadow-2xl">
                        <div class="flex items-center gap-4 text-safari-gold mb-4">
                             <Award class="w-4 h-4" />
                             <h2 class="text-xs font-black uppercase tracking-[0.4em]">Strength Metrics</h2>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-3">
                                 <label class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40">Years of Dominance</label>
                                 <input v-model="form.experience_years" type="text" placeholder="E.G. 15+" class="w-full bg-white/5 border border-white/10 px-6 py-4 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all" />
                            </div>
                            <div class="space-y-3">
                                 <label class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40">Tours Dispatched</label>
                                 <input v-model="form.tours_completed" type="number" class="w-full bg-white/5 border border-white/10 px-6 py-4 text-xs font-black tracking-widest uppercase focus:border-safari-gold/50 outline-none transition-all" />
                            </div>
                        </div>
                    </div>

                    <div class="bg-card border border-white/5 rounded-sm p-10 space-y-8 shadow-2xl">
                        <div class="flex items-center gap-4 text-safari-gold mb-4">
                             <History class="w-4 h-4" />
                             <h2 class="text-xs font-black uppercase tracking-[0.4em]">Pulse Status</h2>
                        </div>

                        <div class="flex items-center gap-4">
                             <input v-model="form.is_active" type="checkbox" id="active" class="w-6 h-6 border border-white/10 bg-white/5 text-safari-gold focus:ring-0 cursor-pointer" />
                             <label for="active" class="text-[10px] font-black uppercase tracking-[0.3em] cursor-pointer">Deploy Narratives Live</label>
                        </div>

                        <p class="text-[9px] opacity-20 uppercase tracking-[0.2em] italic leading-loose">
                             - Disabling this removes the 'About' section from the home radar entirely //
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
