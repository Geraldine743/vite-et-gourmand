<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';
import type { ChiffreAffaire } from '@/types';
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
} from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler);

const donnees = ref<ChiffreAffaire[]>([]);

const fetchData = async () => {
    try {
        const response = await api.get('/chiffre-affaires');
        donnees.value = response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération du CA :", error);
    } 
};

const genererDonnees = async () => {
    if (!confirm("Attention, cela va remplacer les données actuelles par de fausses données. Continuer ?")) return;
    try {
        await api.post('/chiffre-affaires/generate');
        await fetchData(); 
        alert("Fausses données générées avec succès !");
    } catch (error) {
        console.error("Erreur lors de la génération :", error);
    }
};


const totalRevenus = computed(() => {
    return donnees.value.reduce((total, d) => total + d.montant_total, 0).toFixed(2);
});

const totalCommandes = computed(() => {
    return donnees.value.reduce((total, d) => total + d.nombre_commandes, 0);
});


const chartData = computed(() => {
    return {
        labels: donnees.value.map(d => new Date(d.date).toLocaleDateString('fr-FR')),
        datasets: [
            {
                label: 'Chiffre d\'Affaires (€)',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',     
                borderWidth: 2,
                pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                data: donnees.value.map(d => d.montant_total), 
                fill: true, 
                tension: 0.4 
            }
        ]
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false, 
    plugins: {
        legend: { display: false },
        tooltip: {
            callbacks: {
                label: (context: any) => `${context.parsed.y} €` 
            }
        }
    },
    scales: {
        y: { beginAtZero: true }
    }
};

onMounted(() => fetchData());
</script>

<template>
    <div class="admin-dashboard">
        <div class="header-actions">
            <div>
                <h1 class="admin-title">Tableau de bord financier</h1>
            </div>
            <button @click="genererDonnees" class="button button--outline">
                🧪 Générer données de test
            </button>
        </div>


        <div class="admin-content">
            <div class="kpi-grid">
                <div class="kpi-card">
                    <h3>Revenus (30 jours)</h3>
                    <div class="kpi-value">{{ totalRevenus }} €</div>
                </div>
                <div class="kpi-card">
                    <h3>Total Commandes</h3>
                    <div class="kpi-value">{{ totalCommandes }}</div>
                </div>
                <div class="kpi-card">
                    <h3>Panier Moyen</h3>
                    <div class="kpi-value">
                        {{ totalCommandes > 0 ? (Number(totalRevenus) / totalCommandes).toFixed(2) : 0 }} €
                    </div>
                </div>
            </div>

            <div class="chart-container">
                <h3 class="mb-4">Évolution du Chiffre d'Affaires</h3>
                <div style="height: 400px;"> <Line :data="chartData" :options="chartOptions" />
                </div>
            </div>
        </div>
    </div>
</template>

