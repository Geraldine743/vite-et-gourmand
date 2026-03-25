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