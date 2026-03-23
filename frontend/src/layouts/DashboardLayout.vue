<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = () => {
    authStore.logout();
    router.push('/login');
};
</script>

<template>
    <div class="dashboard">
        <aside class="dashboard__sidebar">
            <div class="dashboard__logo">
                Espace Équipe
            </div>

            <nav class="dashboard__nav">
        
                <router-link to="/admin" class="dashboard__link">Vue d'ensemble</router-link>
                <router-link to="/admin/avis" class="dashboard__link">Modération Avis</router-link>
                <router-link to="/admin/plats" class="dashboard__link">Gestion PLats</router-link>
                <router-link to="/admin/menus" class="dashboard__link">Gestion Menus</router-link>
                <router-link to="/admin/horaires" class="dashboard__link">Gestion Horaires</router-link>

                <template v-if="authStore.isAdmin">
                    <div class="dashboard__divider">Administration</div>
                    <router-link to="/admin/equipe" class="dashboard__link">Gérer l'équipe</router-link>
                    <router-link to="/admin/statistiques" class="dashboard__link">Chiffre d'affaires</router-link>
                </template>

            </nav>

            <div class="dashboard__footer">
                <p class="dashboard__user">{{ authStore.user?.firstname }} {{ authStore.user?.lastname }}</p>
                <button @click="handleLogout" class="dashboard__logout">Déconnexion</button>
            </div>
        </aside>

        <main class="dashboard__main">
            <header class="dashboard__header">
                <router-link to="/" class="button button--outline" target="_blank">
                    Voir le site public ↗
                </router-link>
            </header>

            <div class="dashboard__content">
                <router-view />
            </div>
        </main>
    </div>
</template>

