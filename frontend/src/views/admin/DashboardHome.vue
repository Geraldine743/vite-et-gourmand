<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
</script>

<template>
    <div class="admin-home">
        <h1 class="admin-title">Bonjour, {{ authStore.user?.firstname }} 👋</h1>
        <p class="admin-subtitle">Bienvenue sur votre espace de gestion Vite & Gourmand.</p>
    </div>
</template>