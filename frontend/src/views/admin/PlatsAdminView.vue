<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import type { Plat, TypePlat, Allergene } from '@/types'; 

const plats = ref<Plat[]>([]);
const typesPlats = ref<TypePlat[]>([]);
const allergenesDisponibles = ref<Allergene[]>([]);
const showModal = ref(false);
const isEditing = ref(false);

const form = ref({
    id: 0,
    titre_plat: '',
    type_plat_id: '' as number | '',
    allergenes: [] as number[], 
    image: null as File | null 
});

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target && target.files && target.files.length > 0) {
        form.value.image = target.files[0] || null;
    } else {
        form.value.image = null; 
    }
};

const openAddModal = () => {
    isEditing.value = false;
    form.value = { id: 0, titre_plat: '', type_plat_id: '', allergenes: [], image: null };
    showModal.value = true;
};

const openEditModal = (plat: Plat) => {
    isEditing.value = true;
    form.value = {
        id: plat.id,
        titre_plat: plat.titre_plat,
        type_plat_id: plat.type_plat_id,   
        allergenes: plat.allergenes?.map(a => a.id) || [], 
        image: null 
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const fetchPlats = async () => {
    try {
        const response = await api.get('/plats'); 
        plats.value = response.data.data || response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération des plats :", error);
    } 
};

const deletePlat = async (id: number) => {
    if (!confirm("Êtes-vous sûr de vouloir supprimer ce plat ?")) return;

    try {
        await api.delete(`/plats/${id}`);
        plats.value = plats.value.filter(plat => plat.id !== id);
        alert("Le plat a été supprimé !");
    } catch (error) {
        console.error("Erreur lors de la suppression :", error);
        alert("Impossible de supprimer ce plat.");
    }
};

const savePlat = async () => {
    try {
        const formData = new FormData();
        formData.append('titre_plat', form.value.titre_plat);
        formData.append('type_plat_id', String(form.value.type_plat_id));
    
        form.value.allergenes.forEach(id => {
        formData.append('allergenes[]', String(id));
    });

    if (form.value.image) {
        formData.append('image', form.value.image);
    }

    if (isEditing.value) {
        formData.append('_method', 'PUT'); 
        await api.post(`/plats/${form.value.id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
    alert("Plat modifié avec succès !");
    } else {
        await api.post('/plats', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        alert("Plat créé avec succès !");
    }

    closeModal();
    fetchPlats(); 

    } catch (error) {
        console.error("Erreur lors de la sauvegarde :", error);
        alert("Veuillez vérifier les informations du formulaire.");
    }
};

const fetchTypesPlats = async () => {
    try {
        const response = await api.get('/types-plats'); 
        typesPlats.value = response.data.data || response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération des types :", error);
    } 
};

// 2. Récupérer les allergènes
const fetchAllergenes = async () => {
    try {
        // Adaptez l'URL '/allergenes' selon votre API Laravel
        const response = await api.get('/allergenes'); 
        allergenesDisponibles.value = response.data.data || response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération des allergènes :", error);
    } 
};

onMounted(() => {
    fetchPlats();
    fetchTypesPlats();
    fetchAllergenes();
});
</script>

<template>
    <div class="admin-plats">
        <div class="admin-plats__header">
            <div>
                <h1 class="admin-title">Gestion des Plats</h1>
            </div>
            <button class="button button--primary" @click="openAddModal">+ Ajouter un plat</button>
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
                            <img v-if="plat.image" :src=" plat.image " alt="Miniature" class="admin-table__img" />
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
                            <button class="btn-action btn-action--edit" @click="openEditModal(plat)"title="Modifier">✏️</button>
                            <button class="btn-action btn-action--delete" @click="deletePlat(plat.id)" title="Supprimer">🗑️</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal-overlay" v-if="showModal" @click.self="closeModal">
        <div class="modal">
            <div class="modal__header">
                <h2>{{ isEditing ? 'Modifier le plat' : 'Ajouter un nouveau plat' }}</h2>
                <button class="modal__close" @click="closeModal">✕</button>
            </div>

            <form class="modal__form" @submit.prevent="savePlat">
                <div class="form-group">
                    <label>Titre du plat *</label>
                    <input type="text" v-model="form.titre_plat" required class="form-input">
                </div>

                <div class="form-group">
                    <label>Type de plat *</label>
                    <select v-model="form.type_plat_id" required class="form-input">
                        <option value="" disabled>Sélectionnez un type</option>
                        <option v-for="type in typesPlats" :key="type.id" :value="type.id">
                            {{ type.libelle }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Image du plat</label>
                    <input type="file" @change="handleImageUpload" accept="image/*" class="form-input">
                </div>

                <div class="form-group">
                    <label>Allergènes présents</label>
                    <div class="checkbox-grid">
                        <label 
                            v-for="allergene in allergenesDisponibles" 
                            :key="allergene.id" 
                            class="checkbox-item"
                        >
                            <input 
                                type="checkbox" 
                                :value="allergene.id" 
                                v-model="form.allergenes"
                            >
                            {{ allergene.libelle }}
                        </label>
                    </div>
                    <p v-if="allergenesDisponibles.length === 0" class="text-muted">
                        Aucun allergène trouvé en base de données.
                    </p>
                </div>

                <div class="modal__footer">
                    <button type="button" class="button button--outline" @click="closeModal">Annuler</button>
                    <button type="submit" class="button button--primary">
                        {{ isEditing ? 'Mettre à jour' : 'Créer le plat' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</template>

