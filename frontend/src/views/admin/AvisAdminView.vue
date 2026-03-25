<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import type { Avis, User } from '@/types';

const avisList = ref<Avis[]>([]);

const currentPage = ref(1);
const lastPage = ref(1);

const fetchAvis = async (page = 1) => {
    try {
        const response = await api.get(`/avis/all?page=${page}`);
        avisList.value = response.data.data;
        currentPage.value = response.data.current_page;
        lastPage.value = response.data.last_page;
    } catch (error) {
        console.error("Erreur lors du chargement des avis :", error);
    } 
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' }).format(date);
};

onMounted(() => {
    fetchAvis();
});
</script>