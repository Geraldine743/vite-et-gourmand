<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';

// ✅ ON IMPORTE L'INTERFACE AU LIEU DE LA CRÉER
import type { Horaire } from '@/types'; 

// TypeScript reconnait maintenant "Horaire" grâce à l'import
const horaires = ref<Horaire[]>([]);

// ... le reste du code reste identique (formatHeure, onMounted...)
const formatHeure = (time: string) => {
    if (!time) return 'Fermé';
    return time.slice(0, 5).replace(':', 'h');
};

onMounted(async () => {
    try {
        const response = await api.get('/horaires');
        horaires.value = response.data;
    } catch (error) {
        console.error("Erreur chargement horaires", error);
    }
});
</script>

<template>
    <footer class="footer">
        <div class="footer__container">
    
            <div class="footer__column">
                <h3 class="footer__title">Nos Horaires</h3>
                <ul class="footer__schedule">
                    <li v-for="h in horaires" :key="h.id" class="footer__schedule-item">
                        <span class="day">{{ h.jour }}</span>
                        <span class="hours">
                            {{ formatHeure(h.heure_ouverture) }} - {{ formatHeure(h.heure_fermeture) }}
                        </span>
                    </li>
                </ul>
            </div>

            <div class="footer__column">
                <h3 class="footer__title">Informations</h3>
                    <ul class="footer__links">
                        <li class="footer__link-item">
                            <router-link to="/cgv" class="footer__link">Conditions Générales de Vente</router-link>
                        </li>
                        <li class="footer__link-item">
                            <router-link to="/mentions-legales" class="footer__link">Mentions Légales</router-link>
                        </li>
                        <li class="footer__link-item">
                            <router-link to="/contact" class="footer__link">Nous contacter</router-link>
                        </li>
                    </ul>
            </div>

        </div>

        <div class="footer__copyright">
            &copy; {{ new Date().getFullYear() }} Vite&Gourmand - Tous droits réservés.
        </div>
    </footer>
</template>