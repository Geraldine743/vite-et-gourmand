<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
    firstname: '',
    lastname: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    postal_code: '',
    password: '',
    password_confirmation: '' 
});

const errorMessage = ref('');
const isLoading = ref(false);

const handleRegister = async () => {
    if (form.value.password !== form.value.password_confirmation) {
        errorMessage.value = "Les mots de passe ne correspondent pas.";
        return;
    }
    errorMessage.value = '';

    try {
        await authStore.register(form.value);
        router.push('/');
    } catch (error: any) {
        if (error.response?.status === 422) {
            errorMessage.value = "Veuillez vérifier vos informations. Cet e-mail est peut-être déjà utilisé.";
        } else {
            errorMessage.value = "Une erreur est survenue lors de l'inscription.";
        }
    } 
};
</script>

<template>
    <div class="auth-page">
        <div class="container auth-page__container">

            <form class="auth-form" @submit.prevent="handleRegister">
                <h1 class="auth-form__title">Créer un compte</h1>
                <p class="auth-form__subtitle">Rejoignez Vite & Gourmand pour commander vos menus.</p>

                <div v-if="errorMessage" class="auth-form__error">
                    {{ errorMessage }}
                </div>

                <div class="auth-form__row">
                    <div class="auth-form__group">
                    <label for="firstname" class="auth-form__label">Prénom</label>
                    <input type="text" id="firstname" v-model="form.firstname" class="auth-form__input" required />
                    </div>
                </div>
                <div class="auth-form__group">
                    <label for="lastname" class="auth-form__label">Nom</label>
                    <input type="text" id="lastname" v-model="form.lastname" class="auth-form__input" required />
                </div>

                <div class="auth-form__group">
                    <label for="email" class="auth-form__label">Adresse e-mail</label>
                    <input type="email" id="email" v-model="form.email" class="auth-form__input" required />
                </div>

                <div class="auth-form__group">
                    <label for="phone" class="auth-form__label">Téléphone</label>
                    <input type="tel" id="phone" v-model="form.phone" class="auth-form__input" required />
                </div>

                <div class="auth-form__group">
                    <label for="address" class="auth-form__label">Adresse</label>
                    <input type="text" id="address" v-model="form.address" class="auth-form__input" required />
                </div>

                <div class="auth-form__group">
                    <label for="city" class="auth-form__label">Ville</label>
                    <input type="text" id="city" v-model="form.city" class="auth-form__input" required />
                </div> 

                <div class="auth-form__group">
                    <label for="postal_code" class="auth-form__label">Code postal</label>
                    <input type="text" id="postal_code" v-model="form.postal_code" class="auth-form__input" required />
                </div>

                <div class="auth-form__row">
                    <div class="auth-form__group">
                        <label for="password" class="auth-form__label">Mot de passe</label>
                        <input type="password" id="password" v-model="form.password" class="auth-form__input" required minlength="8" />
                    </div>
                    <div class="auth-form__group">
                        <label for="password_confirmation" class="auth-form__label">Confirmer</label>
                        <input type="password" id="password_confirmation" v-model="form.password_confirmation" class="auth-form__input" required minlength="8" />
                    </div>
                </div>

                <button type="submit" class="button button--primary auth-form__submit"">
                    S'inscrire
                </button>

                <div class="auth-form__footer">
                    Déjà un compte ? 
                    <router-link to="/login" class="auth-form__link">Se connecter</router-link>
                </div>
            </form>
        </div>
    </div>
</template>


