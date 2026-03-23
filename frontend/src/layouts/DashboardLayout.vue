<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const isSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const closeSidebar = () => {
    isSidebarOpen.value = false;
};

const handleLogout = () => {
    authStore.logout();
    router.push('/login');
};
</script>

<template>
    <div class="dashboard">
    
        <div 
            class="dashboard__overlay" 
            v-if="isSidebarOpen" 
            @click="closeSidebar"
        ></div>

        <aside class="dashboard__sidebar" :class="{ 'is-open': isSidebarOpen }">
            <div class="dashboard__logo">
                Espace Équipe
                <button class="dashboard__close-btn" @click="closeSidebar">✕</button>
            </div>
            
            <nav class="dashboard__nav">
                <router-link to="/admin" class="dashboard__link" @click="closeSidebar">Vue d'ensemble</router-link>
                <router-link to="/admin/avis" class="dashboard__link" @click="closeSidebar">Modération Avis</router-link>
                <router-link to="/admin/plats" class="dashboard__link" @click="closeSidebar">Gestion Plats</router-link>
                <router-link to="/admin/menus" class="dashboard__link" @click="closeSidebar">Gestion Menus</router-link>
                <router-link to="/admin/horaires" class="dashboard__link" @click="closeSidebar">Gestion Horaires</router-link>
                <template v-if="authStore.isAdmin">
                    <div class="dashboard__divider">Administration</div>
                    <router-link to="/admin/equipe" class="dashboard__link" @click="closeSidebar">Gérer l'équipe</router-link>
                    <router-link to="/admin/statistiques" class="dashboard__link" @click="closeSidebar">Chiffre d'affaires</router-link>
                </template>
            </nav>

            <div class="dashboard__footer">
                <p class="dashboard__user">{{ authStore.user?.firstname }} {{ authStore.user?.lastname }}</p>
                <button @click="handleLogout" class="dashboard__logout">Déconnexion</button>
            </div>
        </aside>

        <main class="dashboard__main">
            <header class="dashboard__header">
                <button class="dashboard__burger" @click="toggleSidebar">
                    <span class="burger-icon">☰</span> Menu
                </button>
                <router-link to="/" class="button button--outline" target="_blank">
                    Voir le site ↗
                </router-link>
            </header>
            <div class="dashboard__content">
                <router-view />
            </div>
        </main>
    </div>
</template>

