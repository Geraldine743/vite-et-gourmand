<script setup lang="ts">
import { RouterView, useRoute } from 'vue-router';
import AppHeader from '@/components/AppHeader.vue';
import AppFooter from '@/components/AppFooter.vue';

const route = useRoute();
</script>

<template>
  <AppHeader v-if="!route.meta.hidePublicNav" />
  
  <main>
    <RouterView />
  </main>
  
  <AppFooter v-if="!route.meta.hidePublicNav" />
</template>


