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

const nextImage = (menu: Menu) => {
    if (menu.plats.length === 0) return;
    const currentIndex = carouselIndexes.value[menu.id] ?? 0;
    carouselIndexes.value[menu.id] = (currentIndex + 1) % menu.plats.length;
};

const prevImage = (menu: Menu) => {
    if (menu.plats.length === 0) return;
    const currentIndex = carouselIndexes.value[menu.id] ?? 0;
    carouselIndexes.value[menu.id] = currentIndex === 0 ? menu.plats.length - 1 : currentIndex - 1;
};

const getUniqueAllergenes = (menu: Menu): Allergene[] => {
    if (!menu.plats) return [];
    const tousLesAllergenes = menu.plats.flatMap(plat => plat.allergenes || []);
    return Array.from(new Map(tousLesAllergenes.map(a => [a.id, a])).values());
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
                    <div v-if="menu.plats && menu.plats.length > 0" class="carousel-wrapper">
                        <img 
                            :src="'http://localhost:8000/storage/' +menu.plats[carouselIndexes[menu.id] ?? 0]?.image || '/placeholder-food.jpg'" 
                            :alt="menu.plats[carouselIndexes[menu.id] ?? 0]?.titre_plat"
                            class="carousel-image"
                        />
                        
                        <button v-if="menu.plats.length > 1" @click.stop="prevImage(menu)" class="carousel-btn prev">❮</button>
                        <button v-if="menu.plats.length > 1" @click.stop="nextImage(menu)" class="carousel-btn next">❯</button>
                        
                        <div v-if="menu.plats.length > 1" class="carousel-dots">
                            <span 
                                v-for="(_, index) in menu.plats" 
                                :key="index"
                                class="dot"
                                :class="{ active: carouselIndexes[menu.id] === index }"
                            ></span>
                        </div>
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
                    <div class="menu-allergenes mt-3" v-if="getUniqueAllergenes(menu).length > 0">
                        <h3 class="section-subtitle">Allergènes présents :</h3>
                        <div class="allergenes-list">
                            <span v-for="alg in getUniqueAllergenes(menu)" :key="alg.id" class="allergene-badge">
                                ⚠️ {{ alg.libelle }}
                            </span>
                        </div>
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