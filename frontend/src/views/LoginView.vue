<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();


const form = ref({
  email: '',
  password: ''
});
const errorMessage = ref('');

const handleLogin = async () => {
  errorMessage.value = '';

  try {
    await authStore.login(form.value);
    router.push('/');
  } catch (error: any) {
    if (error.response?.status === 401 || error.response?.status === 422) {
      errorMessage.value = "Identifiants incorrects. Veuillez réessayer.";
    } else {
      errorMessage.value = "Une erreur est survenue avec le serveur.";
    }
  } 
};
</script>

<template>
  <div class="auth-page">
    <div class="container auth-page__container">
      
      <form class="auth-form" @submit.prevent="handleLogin">
        <h1 class="auth-form__title">Connexion</h1>
        <p class="auth-form__subtitle">Ravi de vous revoir chez Vite & Gourmand.</p>

        <div v-if="errorMessage" class="auth-form__error">
          {{ errorMessage }}
        </div>

        <div class="auth-form__group">
          <label for="email" class="auth-form__label">Adresse e-mail</label>
          <input 
            type="email" 
            id="email" 
            v-model="form.email" 
            class="auth-form__input" 
            required 
            placeholder="jean.dupont@email.com"
          />
        </div>

        <div class="auth-form__group">
          <label for="password" class="auth-form__label">Mot de passe</label>
          <input 
            type="password" 
            id="password" 
            v-model="form.password" 
            class="auth-form__input" 
            required 
            placeholder="••••••••"
          />
        </div>

        <button type="submit" class="button button--primary auth-form__submit" >
        Se connecter
        </button>

        <div class="auth-form__footer">
          Pas encore de compte ? 
          <router-link to="/register" class="auth-form__link">Créer un compte</router-link>
        </div>
      </form>

    </div>
  </div>
</template>

