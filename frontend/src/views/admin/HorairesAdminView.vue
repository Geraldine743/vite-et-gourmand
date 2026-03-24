<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import type { Horaire } from '@/types';

const horaires = ref<Horaire[]>([]);
const isSaving = ref(false);

const fetchHoraires = async () => {
    try {
        const response = await api.get('/horaires');
        horaires.value = response.data.data || response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération des horaires :", error);
    }
};

const updateHoraire = async (horaire: Horaire) => {
    try {
        isSaving.value = true;
        await api.put(`/horaires/${horaire.id}`, {
            heure_ouverture: horaire.heure_ouverture,
            heure_fermeture: horaire.heure_fermeture
        });
        alert(`Les horaires du ${horaire.jour} ont été mis à jour !`);
    } catch (error) {
        console.error("Erreur lors de la mise à jour :", error);
        alert("Impossible de mettre à jour l'horaire.");
    } finally {
        isSaving.value = false;
    }
};

onMounted(() => fetchHoraires());
</script>

<template>
    <div class="admin-horaires">
        <div class="admin-horaires__header mb-4">
            <h1 class="admin-title">Gestion des Horaires</h1>
            <p class="text-muted">Modifiez les heures d'ouverture et de fermeture pour chaque jour de la semaine.</p>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Jour</th>
                        <th>Heure d'ouverture</th>
                        <th>Heure de fermeture</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="horaires.length === 0">
                        <td colspan="4" class="text-center">Aucun horaire trouvé.</td>
                    </tr>

                    <tr v-for="horaire in horaires" :key="horaire.id">
                        <td class="font-bold capitalize">{{ horaire.jour }}</td>
                        
                        <td>
                            <input 
                                type="time" 
                                v-model="horaire.heure_ouverture" 
                                class="form-input"
                            >
                        </td>
                        <td>
                            <input 
                                type="time" 
                                v-model="horaire.heure_fermeture" 
                                class="form-input"
                            >
                        </td>
                        <td class="text-right">
                            <button 
                                class="button button--primary" 
                                @click="updateHoraire(horaire)"
                                :disabled="isSaving"
                            >
                                Enregistrer
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>