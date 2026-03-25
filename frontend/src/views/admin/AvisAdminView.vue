<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import type { Avis, User } from '@/types';

const avisList = ref<Avis[]>([]);

const currentPage = ref(1);
const lastPage = ref(1);

const fetchAvis = async (page = 1) => {
    try {
        const response = await api.get(`/avis/all?page=${page}`);
        avisList.value = response.data.data;
        currentPage.value = response.data.current_page;
        lastPage.value = response.data.last_page;
    } catch (error) {
        console.error("Erreur lors du chargement des avis :", error);
    } 
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' }).format(date);
};

onMounted(() => {
    fetchAvis();
});
</script>

<template>
    <div class="admin-avis">
        <div class="header-actions mb-4">
            <div>
                <h1 class="admin-title">Modération des Avis</h1>
            </div>
        </div>

        <div>
            <div class="avis-grid">
                <div v-for="avis in avisList" :key="avis.id" class="avis-card" :class="{ 'is-unpublished': !avis.publie }">
                    
                    <div class="avis-card__header">
                        <div class="avis-author">
                            <span class="font-bold">{{ avis.user?.firstname }} {{ avis.user?.lastname }}</span>
                            <span class="avis-date">{{ formatDate(avis.created_at) }}</span>
                        </div>
                        <div class="avis-rating-number">
                            <span class="note-value">{{ avis.note }}</span> <span class="note-max">/ 5</span>
                        </div>
                    </div>

                    <div class="avis-card__body">
                        <p>« {{ avis.description }} »</p>
                    </div>

                    <div class="avis-card__footer">
                        <div class="status-badge" :class="avis.publie ? 'badge-success' : 'badge-warning'">
                            {{ avis.publie ? '✅ En ligne' : '⏳ En attente' }}
                        </div>
                        
                        <div class="action-buttons">
                            <button 
                                class="btn-toggle" 
                                :class="avis.publie ? 'btn-toggle--hide' : 'btn-toggle--publish'">
                                {{ avis.publie ? '👁️ Masquer' : '🚀 Publier' }}
                            </button>
                            <button  class="btn-delete" title="Supprimer">🗑️</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pagination mt-4" v-if="lastPage > 1">
                <button 
                    :disabled="currentPage === 1" 
                    @click="fetchAvis(currentPage - 1)"
                    class="button button--outline">
                    Précédent
                </button>
                <span class="page-info">Page {{ currentPage }} sur {{ lastPage }}</span>
                <button 
                    :disabled="currentPage === lastPage" 
                    @click="fetchAvis(currentPage + 1)"
                    class="button button--outline">
                    Suivant
                </button>
            </div>
            
            <div v-if="avisList.length === 0" class="text-center text-muted py-5">
                Aucun avis pour le moment.
            </div>
        </div>
    </div>
</template>