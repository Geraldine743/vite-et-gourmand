<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import type { User, Role } from '@/types';

const users = ref<User[]>([]);
const roles = ref<Role[]>([]);
const showModal = ref(false);
const isEditing = ref(false);

const form = ref({
    id: '',
    firstname: '',
    lastname: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    postal_code: '',
    country: 'France', 
    role_id: '' as number | '',
    password: '', 
    password_confirmation: ''
});

const fetchData = async () => {
    try {
        const [resUsers, resRoles] = await Promise.all([
            api.get('/staff'),
            api.get('/roles')
        ]);
        users.value = resUsers.data.data || resUsers.data;
        roles.value = resRoles.data.data || resRoles.data;
    } catch (error) {
        console.error("Erreur lors du chargement :", error);
    }
};

const openAddModal = () => {
    isEditing.value = false;
    form.value = {
        id: '', firstname: '', lastname: '', email: '', phone: '',
        address: '', city: '', postal_code: '', country: 'France', role_id: '', password: '',password_confirmation: ''
    };
    showModal.value = true;
};

const openEditModal = (user: User) => {
    isEditing.value = true;
    form.value = {
        id: user.id,
        firstname: user.firstname,
        lastname: user.lastname,
        email: user.email,
        phone: user.phone,
        address: user.address,
        city: user.city,
        postal_code: user.postal_code,
        country: user.country,
        role_id: user.role_id,
        password: '' ,
        password_confirmation: ''
    };
    showModal.value = true;
};

const closeModal = () => showModal.value = false;

const saveUser = async () => {
    try {
        let payload: any = { ...form.value };
        if (isEditing.value) {
            const { password, ...payloadWithoutPassword } = payload; 
            payload = payloadWithoutPassword;
            await api.put(`/users/${form.value.id}`, payload);
            alert("Membre de l'équipe modifié avec succès !");
        } else {
            await api.post('/users', payload);
            alert("Nouveau membre ajouté avec succès !");
        }
        closeModal();
        fetchData();
    } catch (error: any) {
        console.error("Erreur de sauvegarde :", error);
        alert("Erreur lors de la sauvegarde.");
    }
};

const deleteUser = async (id: string) => {
    if (!confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ? L'action est irréversible.")) return;
    try {
        await api.delete(`/users/${id}`);
        users.value = users.value.filter(u => u.id !== id);
    } catch (error) {
        console.error("Erreur suppression :", error);
    }
};
onMounted(() => fetchData());
</script>

<template>
    <div class="admin-users">
        <div class="admin-users__header mb-4 flex justify-between items-center">
            <div>
                <h1 class="admin-title">Gestion de l'Équipe</h1>
            </div>
            <button class="button button--primary" @click="openAddModal">+ Ajouter un membre</button>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Employé</th>
                        <th>Contact</th>
                        <th>Localisation</th>
                        <th>Rôle</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>
                            <div class="font-bold">{{ user.firstname }} {{ user.lastname }}</div>
                        </td>
                        <td>
                            <div class="text-sm">📧 {{ user.email }}</div>
                            <div class="text-sm">📞 {{ user.phone }}</div>
                        </td>
                        <td>
                            <div class="text-sm">{{ user.city }} ({{ user.postal_code }})</div>
                        </td>
                        <td>
                            <span class="badge" :class="{'badge--admin': user.role_id === 1, 'badge--staff': user.role_id !== 1}">
                                {{ user.role?.libelle || 'Non défini' }}
                            </span>
                        </td>
                        <td class="text-right">
                            <button class="btn-action btn-action--edit" @click="openEditModal(user)" title="Modifier">✏️</button>
                            <button class="btn-action btn-action--delete" @click="deleteUser(user.id)" title="Supprimer">🗑️</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal-overlay" v-if="showModal" @click.self="closeModal">
            <div class="modal">
                <div class="modal__header">
                    <h2>{{ isEditing ? 'Modifier le membre' : 'Ajouter un membre' }}</h2>
                    <button class="modal__close" @click="closeModal">&times;</button>
                </div>

                <form class="modal__form" @submit.prevent="saveUser">
                    
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Prénom *</label>
                            <input type="text" v-model="form.firstname" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label>Nom *</label>
                            <input type="text" v-model="form.lastname" required class="form-input">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" v-model="form.email" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label>Téléphone *</label>
                            <input type="tel" v-model="form.phone" required class="form-input">
                        </div>
                    </div>

                    <div class="form-group" v-if="!isEditing">
                        <label>Mot de passe provisoire *</label>
                        <input type="password" v-model="form.password" required class="form-input" minlength="8">
                        <p class="text-sm text-muted">L'employé pourra le changer plus tard.</p>
                    </div>

                    <div class="form-group">
                        <label>Confirmer le mot de passe *</label>
                        <input type="password" v-model="form.password_confirmation" required class="form-input" minlength="8">
                    </div>

                    <hr class="my-4">

                    <div class="form-group">
                        <label>Adresse *</label>
                        <input type="text" v-model="form.address" required class="form-input">
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group">
                            <label>Code Postal *</label>
                            <input type="text" v-model="form.postal_code" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label>Ville *</label>
                            <input type="text" v-model="form.city" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label>Pays *</label>
                            <input type="text" v-model="form.country" required class="form-input">
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label>Rôle de l'utilisateur *</label>
                        <select v-model="form.role_id" required class="form-input">
                            <option value="" disabled>Sélectionner un rôle</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.libelle }}</option>
                        </select>
                    </div>

                    <div class="modal__footer mt-4">
                        <button type="button" class="button button--outline" @click="closeModal">Annuler</button>
                        <button type="submit" class="button button--primary">
                            {{ isEditing ? 'Mettre à jour' : 'Ajouter' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
