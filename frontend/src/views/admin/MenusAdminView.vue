<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';
import type { Menu, Plat, Regime, Theme } from '@/types';

const menus = ref<Menu[]>([]);
const plats = ref<Plat[]>([]);
const regimes = ref<Regime[]>([]);
const themes = ref<Theme[]>([]);

const showModal = ref(false);
const isEditing = ref(false);
const entrees = computed(() => plats.value.filter(p => p.type_plat_id === 1));
const platsPrincipaux = computed(() => plats.value.filter(p => p.type_plat_id === 2));
const desserts = computed(() => plats.value.filter(p => p.type_plat_id === 3));

const form = ref({
    id: 0,
    titre: '',
    description: '',
    prix_par_personne: 0,
    stock: 0,
    nb_personne_min: 1,
    condition: '',
    regime_id: '' as number | '',
    theme_id: '' as number | '',
    entree_id: '' as number | '',
    plat_id: '' as number | '',
    dessert_id: '' as number | ''
});

const fetchData = async () => {
    try {
        const [resMenus, resPlats, resRegimes, resThemes] = await Promise.all([
            api.get('/menus'),
            api.get('/plats'),
            api.get('/regimes'),
            api.get('/themes')
        ]);
        
        menus.value = resMenus.data.data || resMenus.data;
        plats.value = resPlats.data.data || resPlats.data;
        regimes.value = resRegimes.data.data || resRegimes.data;
        themes.value = resThemes.data.data || resThemes.data;
    } catch (error) {
        console.error("Erreur lors du chargement des données :", error);
    }
};

const openAddModal = () => {
    isEditing.value = false;
    form.value = {
        id: 0, titre: '', description: '', prix_par_personne: 0, stock: 0, 
        nb_personne_min: 1, condition: '', regime_id: '', theme_id: '', 
        entree_id: '', plat_id: '', dessert_id: ''
    };
    showModal.value = true;
};

const openEditModal = (menu: Menu) => {
    isEditing.value = true;
    
    const currentEntree = menu.plats?.find(p => p.type_plat_id === 1);
    const currentPlat = menu.plats?.find(p => p.type_plat_id === 2);
    const currentDessert = menu.plats?.find(p => p.type_plat_id === 3);

    form.value = {
        id: menu.id,
        titre: menu.titre,
        description: menu.description,
        prix_par_personne: menu.prix_par_personne,
        stock: menu.stock,
        nb_personne_min: menu.nb_personne_min,
        condition: menu.condition || '',
        regime_id: menu.regime?.id || '',
        theme_id: menu.theme?.id || '',
        entree_id: currentEntree ? currentEntree.id : '',
        plat_id: currentPlat ? currentPlat.id : '',
        dessert_id: currentDessert ? currentDessert.id : ''
    };
    showModal.value = true;
};

const closeModal = () => showModal.value = false;

const saveMenu = async () => {
    try {
        const payload = {
            titre: form.value.titre,
            description: form.value.description,
            prix_par_personne: form.value.prix_par_personne,
            stock: form.value.stock,
            nb_personne_min: form.value.nb_personne_min,
            condition: form.value.condition,
            regime_id: form.value.regime_id,
            theme_id: form.value.theme_id,
            plats: [form.value.entree_id, form.value.plat_id, form.value.dessert_id].filter(id => id !== '')
        };

        if (isEditing.value) {
            await api.put(`/menus/${form.value.id}`, payload);
            alert("Menu modifié avec succès !");
        } else {
            await api.post('/menus', payload);
            alert("Menu créé avec succès !");
        }

        closeModal();
        fetchData(); 

    } catch (error: any) {
        console.error("Erreur de sauvegarde :", error);
        if (error.response?.data?.errors) {
            alert("Erreur de validation : " + Object.values(error.response.data.errors)[0]);
        }
    }
};

const deleteMenu = async (id: number) => {
    if (!confirm("Supprimer ce menu ?")) return;
    try {
        await api.delete(`/menus/${id}`);
        menus.value = menus.value.filter(m => m.id !== id);
    } catch (error) {
        console.error("Erreur suppression :", error);
    }
};

onMounted(() => fetchData());
</script>

<template>
    <div class="admin-menus">
        <div class="admin-menus__header">
            <div>
                <h1 class="admin-title">Gestion des Menus</h1>
            </div>
            <button class="button button--primary" @click="openAddModal">+ Ajouter un menu</button>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Prix & Disponibilité</th>
                        <th>Catégories</th>
                        <th>Composition (E / P / D)</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="menus.length === 0">
                        <td colspan="5" class="text-center">Aucun menu n'a été créé pour le moment.</td>
                    </tr>

                    <tr v-for="menu in menus" :key="menu.id">
                        <td>
                            <div class="font-bold" style="font-size: 1.1rem;">{{ menu.titre }}</div>
                            <div class="text-muted text-sm" v-if="menu.description">
                                {{ menu.description.length > 50 ? menu.description.substring(0, 50) + '...' : menu.description }}
                            </div>
                        </td>

                        <td>
                            <div class="font-bold text-primary">{{ menu.prix_par_personne }} € <span class="text-sm font-normal text-muted">/ pers.</span></div>
                            <div class="text-sm mt-1">📦 Stock : <strong>{{ menu.stock }}</strong></div>
                            <div class="text-sm">👥 Min : <strong>{{ menu.nb_personne_min }}</strong> pers.</div>
                        </td>

                        <td>
                            <div class="flex-column gap-1">
                                <span class="badge" style="background-color: #e0f2fe; color: #0284c7;">
                                    {{ menu.regime?.libelle || 'Aucun régime' }}
                                </span>
                                <span class="badge" style="background-color: #fef08a; color: #a16207;">
                                    {{ menu.theme?.libelle || 'Aucun thème' }}
                                </span>
                            </div>
                        </td>

                        <td class="text-sm">
                            <div class="mb-1">
                                <span class="font-bold">E :</span> 
                                {{ menu.plats?.find(p => p.type_plat_id === 1)?.titre_plat || 'Non défini' }}
                            </div>
                            <div class="mb-1">
                                <span class="font-bold">P :</span> 
                                {{ menu.plats?.find(p => p.type_plat_id === 2)?.titre_plat || 'Non défini' }}
                            </div>
                            <div>
                                <span class="font-bold">D :</span> 
                                {{ menu.plats?.find(p => p.type_plat_id === 3)?.titre_plat || 'Non défini' }}
                            </div>
                        </td>

                        <td class="admin-table__actions text-right">
                            <button class="btn-action btn-action--edit" @click="openEditModal(menu)" title="Modifier">✏️</button>
                            <button class="btn-action btn-action--delete" @click="deleteMenu(menu.id)" title="Supprimer">🗑️</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal-overlay" v-if="showModal" @click.self="closeModal">
        <div class="modal">
            <div class="modal__header">
                <h2>{{ isEditing ? 'Modifier' : 'Créer' }} un menu</h2>
                <button class="modal__close" @click="closeModal">✕</button>
            </div>
            <form class="modal__form" @submit.prevent="saveMenu">
                <div class="form-group">
                    <label>Titre du menu *</label>
                    <input type="text" v-model="form.titre" required class="form-input">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea v-model="form.description" class="form-input" rows="3"></textarea>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label>Prix / pers. (€) *</label>
                        <input type="number" step="0.01" v-model="form.prix_par_personne" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label>Stock disponible *</label>
                        <input type="number" v-model="form.stock" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label>Pers. minimum *</label>
                        <input type="number" v-model="form.nb_personne_min" required min="1" class="form-input">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Régime *</label>
                        <select v-model="form.regime_id" required class="form-input">
                            <option value="" disabled>Choisir un régime</option>
                            <option v-for="r in regimes" :key="r.id" :value="r.id">{{ r.libelle }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Thème *</label>
                        <select v-model="form.theme_id" required class="form-input">
                            <option value="" disabled>Choisir un thème</option>
                            <option v-for="t in themes" :key="t.id" :value="t.id">{{ t.libelle }}</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">
                <h3 class="mb-3 font-bold">Composition du Menu</h3>

                <div class="form-group">
                    <label>Entrée *</label>
                    <select v-model="form.entree_id" required class="form-input">
                        <option value="" disabled>Sélectionnez une entrée</option>
                        <option v-for="plat in entrees" :key="plat.id" :value="plat.id">{{ plat.titre_plat }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Plat principal *</label>
                    <select v-model="form.plat_id" required class="form-input">
                        <option value="" disabled>Sélectionnez un plat</option>
                        <option v-for="plat in platsPrincipaux" :key="plat.id" :value="plat.id">{{ plat.titre_plat }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Dessert *</label>
                    <select v-model="form.dessert_id" required class="form-input">
                        <option value="" disabled>Sélectionnez un dessert</option>
                        <option v-for="plat in desserts" :key="plat.id" :value="plat.id">{{ plat.titre_plat }}</option>
                    </select>
                </div>

                <div class="modal__footer">
                    <button type="button" class="button button--outline" @click="closeModal">Annuler</button>
                    <button type="submit" class="button button--primary">
                        {{ isEditing ? 'Mettre à jour' : 'Créer le menu' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>