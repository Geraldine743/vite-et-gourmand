import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AvisView from '@/views/AvisView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import MenuClientView from '@/views/MenuClientView.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import DashboardHome from '@/views/admin/DashboardHome.vue'
import { useAuthStore } from '@/stores/auth'
import PlatsAdminView from '@/views/admin/PlatsAdminView.vue'
import HorairesAdminView from '@/views/admin/HorairesAdminView.vue'
import MenusAdminView from '@/views/admin/MenusAdminView.vue'
import ChiffreAffaireAdminView from '@/views/admin/ChiffreAffaireAdminView.vue'
import EquipeAdminView from '@/views/admin/EquipeAdminView.vue'
import AvisAdminView from '@/views/admin/AvisAdminView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/avis',
      name: 'avis',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: AvisView,
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },

    {
      path: '/register',
      name: 'register',
      component: RegisterView
    },

    {
      path: '/menus',
      name: 'menus',
      component: MenuClientView
    },

    {
      path: '/admin',
      component: DashboardLayout, 
      meta: { requiresAuth: true, requiresStaff: true, hidePublicNav: true},
      children: [
        {
          path: '', 
          name: 'admin-home',
          component: DashboardHome
        },
        {
          path: 'plats', 
          name: 'admin-plats',
          component: PlatsAdminView
        },
        {
          path: 'horaires', 
          name: 'admin-horaires',
          component: HorairesAdminView
        },
        {
          path: 'menus', 
          name: 'admin-menus',
          component: MenusAdminView
        },
        {
          path: 'statistiques', 
          name: 'admin-statistiques',
          component: ChiffreAffaireAdminView
        },
        {
          path: 'equipe', 
          name: 'admin-equipe',
          component: EquipeAdminView
        },
        {
          path: 'avis', 
          name: 'admin-avis',
          component: AvisAdminView
        },
      ],
    },
  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return next('/login');
  }
  if (to.meta.requiresStaff && !authStore.isStaff) {
    return next('/');
  }
  next();
  
});

export default router
