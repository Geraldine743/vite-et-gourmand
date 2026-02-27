<script setup lang="ts">
import type { Avis } from '@/types';
const props = defineProps<{
    avis: Avis;
}>();
const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('fr-FR', { month: 'long', year: 'numeric' }).format(date);
};

const formatNote = (n: number) => {
    return Number.isInteger(n) ? `${n}.0` : n.toString();
};
</script>

<template>
    <article class="reviews__card">
        <div class="reviews__rating">
            <span class="reviews__score">{{ formatNote(avis.note) }}</span>
            <span class="reviews__max">/5</span>
        </div>

        <blockquote class="reviews__content">
            "{{ avis.description }}"
        </blockquote>

        <div class="reviews__author">
            <div class="reviews__meta">
            <span class="name">{{ avis.user.firstname }} {{ avis.user.lastname }}</span>
            <span class="date">Visité en {{ formatDate(avis.created_at) }}</span>
            </div>
        </div>
    </article>
</template>