<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import type { Plat } from '@/types'; 

const plats = ref<Plat[]>([]);

const fetchPlats = async () => {
    try {
        const response = await api.get('/plats'); 
        plats.value = response.data.data || response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération des plats :", error);
    } 
};

onMounted(() => fetchPlats());
</script>

<template>
    <div class="admin-plats">
        <div class="admin-plats__header">
            <div>
                <h1 class="admin-title">Gestion des Plats</h1>
            </div>
            <button class="button button--primary">+ Ajouter un plat</button>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Titre du plat</th>
                        <th>Type</th>
                        <th>Allergènes</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="plats.length === 0">
                        <td colspan="5" class="text-center">Aucun plat n'est actuellement à la carte.</td>
                    </tr>

                    <tr v-for="plat in plats" :key="plat.id">
                        <td>
                            <img v-if="plat.image" :src="plat.image" alt="Miniature" class="admin-table__img" />
                            <span v-else class="admin-table__no-img">?</span>
                        </td>
                        <td class="font-bold">{{ plat.titre_plat }}</td>
                        <td>
                            <span class="badge">{{ plat.type_plat?.libelle || 'Non classé' }}</span>
                        </td>
                        <td>
                            <div class="allergene-list">
                                <span 
                                v-for="allergene in plat.allergenes" 
                                :key="allergene.id" 
                                class="badge-allergene"
                                >
                                    {{ allergene.libelle }}
                                </span>
                                <span v-if="plat.allergenes.length === 0" class="text-muted">Aucun</span>
                            </div>
                        </td>
                        <td class="admin-table__actions text-right">
                            <button class="btn-action btn-action--edit" title="Modifier">✏️</button>
                            <button class="btn-action btn-action--delete" title="Supprimer">🗑️</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

