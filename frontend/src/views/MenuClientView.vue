<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import type {Allergene, Plat, Regime, Theme, Menu} from '@/types';

const menus = ref<Menu[]>([]);
const carouselIndexes = ref<Record<number, number>>({});

const fetchMenus = async () => {
    try {
        const response = await api.get('/menus');
        menus.value = response.data;
        menus.value.forEach(menu => {
            carouselIndexes.value[menu.id] = 0;
        });
    } catch (error) {
        console.error("Erreur de chargement des menus :", error);
    }
};
onMounted(() => fetchMenus());
</script>

<template>
    <div class="client-menus">
        <h1 class="page-title text-center mb-5">Découvrez nos Menus</h1>
        <div class="menu-grid">
            <div v-for="menu in menus" :key="menu.id" class="menu-card">
                
                <div class="menu-card__carousel">
                    <div class="badges-container">
                        <span v-if="menu.theme" class="badge badge--theme">{{ menu.theme.libelle }}</span>
                        <span v-if="menu.regime" class="badge badge--regime">{{ menu.regime.libelle }}</span>
                    </div>

                </div>

                <div class="menu-card__content">
                    <h2 class="menu-title">{{ menu.titre }}</h2>
                    <p class="menu-description">{{ menu.description }}</p>

                    <div class="menu-composition mt-3">
                        <h3 class="section-subtitle">Composition :</h3>
                        <ul>
                            <li v-for="plat in menu.plats" :key="plat.id">{{ plat.titre_plat }}</li>
                        </ul>
                    </div>
                </div>

                <div class="menu-card__footer">
                    <div class="infos-pratiques">
                        <span class="price">{{ menu.prix_par_personne }} € <small>/ pers.</small></span>
                        <span class="min-pers">👥 Min. {{ menu.nb_personne_min }} pers.</span>
                    </div>
                    
                    <button 
                        class="button btn-commander"
                        :disabled="menu.stock <= 0"
                        :class="{'out-of-stock': menu.stock <= 0}"
                    >
                        {{ menu.stock > 0 ? 'Commander' : 'Rupture de stock' }}
                    </button>
                </div>
            </div>
            
        </div>        
    </div>
</template>