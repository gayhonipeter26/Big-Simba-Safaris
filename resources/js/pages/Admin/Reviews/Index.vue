<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { MessageSquare, Check, X, Trash2, Star } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: route('admin.dashboard') },
    { title: 'Pride Feedback', href: '#' },
];

defineProps<{
    reviews: any[];
}>();

const toggleApproval = (id: number, isApproved: boolean) => {
    router.patch(route('admin.reviews.update', { review: id }), {
        is_approved: isApproved
    }, {
        preserveScroll: true,
    });
};

const deleteReview = (id: number) => {
    if (confirm('Are you sure you want to remove this feedback?')) {
        router.delete(route('admin.reviews.destroy', { review: id }), {
            preserveScroll: true,
        });
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <Head title="Moderate Feedback" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 md:p-10 space-y-10 max-w-[1600px] mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/5 pb-10">
                 <div>
                      <h1 class="text-3xl font-black uppercase tracking-tighter mb-2">Pride Feedback</h1>
                      <p class="text-[10px] opacity-40 uppercase tracking-widest">Moderate customer reviews and ratings</p>
                 </div>
            </div>

            <div v-if="reviews.length > 0" class="space-y-6">
                 <div v-for="review in reviews" :key="review.id" class="bg-card border border-white/5 rounded-sm p-8 flex flex-col md:flex-row gap-10 hover:border-white/10 transition-colors">
                      <div class="md:w-64 space-y-4">
                           <div class="flex items-center gap-2">
                                <div v-for="i in 5" :key="i">
                                     <Star :class="['w-3.5 h-3.5', i <= review.rating ? 'text-safari-gold fill-safari-gold' : 'text-white/10']" />
                                </div>
                           </div>
                           <p class="text-[10px] font-black uppercase tracking-widest">{{ review.user?.name }}</p>
                           <p class="text-[9px] opacity-30 uppercase tracking-[0.2em]">{{ formatDate(review.created_at) }}</p>
                           <div class="pt-4">
                                <span :class="['px-2 py-1 text-[8px] font-black uppercase tracking-widest rounded-sm border', review.is_approved ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-orange-500/10 text-orange-500 border-orange-500/20']">
                                     {{ review.is_approved ? 'Visible' : 'Pending Moderation' }}
                                </span>
                           </div>
                      </div>

                      <div class="flex-1 space-y-6">
                           <div>
                                <p class="text-[9px] font-black uppercase tracking-widest text-safari-gold mb-2">Expedition: {{ review.tour?.name }}</p>
                                <p class="text-sm font-light opacity-60 leading-relaxed italic">"{{ review.comment }}"</p>
                           </div>
                           
                           <div class="flex items-center gap-4 pt-6 border-t border-white/5">
                                <button v-if="!review.is_approved" @click="toggleApproval(review.id, true)" class="px-6 py-2 bg-green-500/10 text-green-500 text-[9px] font-black uppercase tracking-widest hover:bg-green-500 hover:text-white transition-all rounded-sm flex items-center gap-2">
                                     <Check class="w-3 h-3" /> Approve
                                </button>
                                <button v-else @click="toggleApproval(review.id, false)" class="px-6 py-2 bg-orange-500/10 text-orange-500 text-[9px] font-black uppercase tracking-widest hover:bg-orange-500 hover:text-white transition-all rounded-sm flex items-center gap-2">
                                     <X class="w-3 h-3" /> Retract
                                </button>
                                <button @click="deleteReview(review.id)" class="px-6 py-2 bg-red-500/10 text-red-500 text-[9px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all rounded-sm flex items-center gap-2">
                                     <Trash2 class="w-3 h-3" /> Purge
                                </button>
                           </div>
                      </div>
                 </div>
            </div>

            <div v-else class="py-32 border border-dashed border-white/10 rounded-sm flex flex-col items-center justify-center text-center">
                 <MessageSquare class="w-12 h-12 opacity-10 mb-6" />
                 <h3 class="text-xl font-black uppercase tracking-widest mb-2">The Silence is Absolute</h3>
                 <p class="text-[10px] opacity-30 uppercase tracking-[0.2em]">No feedback has been recorded from the pride yet.</p>
            </div>
        </div>
    </AppLayout>
</template>
