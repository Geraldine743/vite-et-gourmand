<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import { ref } from 'vue';
const isMenuOpen = ref(false);

const closeMenu = () => {
    isMenuOpen.value = false;
};
</script>

<template>
    <header class="header">
        <div class="header__container">
            
            <router-link to="/" class="header__logo" @click="closeMenu">
                <img src="@/assets/images/logo.png" alt="Logo Traiteur" class="header__logo-img">
            </router-link>
            <div class="burger">
                <button class="header__burger-img" :class="{ 'is-hidden': isMenuOpen }" @click="isMenuOpen = true">
                    <span class="burger-icon">☰</span>
                </button>
            </div>
        
            <nav class="header__nav" :class="{ 'is-open': isMenuOpen }">
        
                <button class="header__close" @click="closeMenu">✕</button>

                <ul class="header__list">
                    <li class="header__item">
                        <router-link to="/" class="header__link" @click="closeMenu">Accueil</router-link>
                    </li>
                    <li class="header__item">
                        <router-link to="/menus" class="header__link" @click="closeMenu">Menus</router-link>
                    </li>
                    <li class="header__item">
                        <router-link to="/contact" class="header__link" @click="closeMenu">Contact</router-link>
                    </li>
                    <li class="header__item">
                        <router-link to="/login" class="header__link" @click="closeMenu">Connexion</router-link>
                    </li>
                    <li class="header__item">
                        <router-link to="/register" class="header__link" @click="closeMenu">Inscription</router-link>
                    </li>
                </ul>
            </nav>

        </div>
    </header>
</template>