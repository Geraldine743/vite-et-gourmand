import { defineStore } from 'pinia';
import api from '@/services/api';
import type { User } from '@/types';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null as User | null,
        token: localStorage.getItem('token') || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        isAdmin: (state) => state.user?.role_id === 1, 
    },

    actions: {
      async login(credentials: any) {
        const response = await api.post('/login', credentials);
        console.log("Réponse de Laravel :", response.data);
        this.token = response.data.access_token;
        this.user = response.data.user;
        localStorage.setItem('token', this.token as string);
      },

      logout() {
        this.user = null;
        this.token = null;
        localStorage.removeItem('token');
      },

      async register(userData: any) {
        const response = await api.post('/register', userData);
        const monToken = response.data.access_token; 

        if (monToken) {
          this.token = monToken;
          this.user = response.data.user;
          localStorage.setItem('token', monToken as string);
        } else {
          throw new Error("Aucun token reçu après l'inscription");
        }
      }
    }
});