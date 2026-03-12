<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import type { Avis } from '@/types';
import ReviewCard from '@/components/ReviewCard.vue';

const reviews = ref<Avis[]>([]);
const currentPage = ref(1);
const lastPage = ref(1);

const loadReviews = async (page: number) => {
    try {
        const response = await api.get(`/avis-all?page=${page}`);
        console.log("Réponse de Laravel :", response.data);
        if (page === 1) {
            reviews.value = response.data.data;
        } else {
            reviews.value = [...reviews.value, ...response.data.data];
        }
        currentPage.value = response.data.current_page;
        lastPage.value = response.data.last_page;
    } catch (error) {
        console.error("Erreur de chargement des avis :", error);
    } 
};

onMounted(() => loadReviews(1));

const loadMore = () => {
    if (currentPage.value < lastPage.value) {
        loadReviews(currentPage.value + 1);
    }
};
</script>

<template>
<section class="reviews">
    <h2 class="reviews__title">Ce que nos clients disent de nous</h2>   

    <div class="reviews__grid">
    
        <ReviewCard 
            v-for="avis in reviews" 
            :key="avis.id" 
            :avis="avis" 
        />
    </div>
    <div class="reviews__action">
        <button
            v-if="currentPage < lastPage" 
            @click="loadMore" 
            class="button button--primary"
        >
            Voir plus d'avis
        </button>
    </div>
</section>
</template>