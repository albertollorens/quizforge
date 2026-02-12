import { createRouter, createWebHistory } from "vue-router";

// Vistes
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Dashboard from "../views/Dashboard.vue";

// Layout
import DashboardLayout from "../layouts/DashboardLayout.vue";

// Serveis
import authService from "../services/authService";

const routes = [
  // 🔓 Públiques
  { path: "/", redirect: "/login" },
  { path: "/login", component: Login },
  { path: "/register", component: Register },

  // 🔒 Protegides (SB Admin Layout)
  {
    path: "/",
    component: DashboardLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "dashboard",
        component: Dashboard
      }
      // ací més vistes:
      // { path: "quizzes", component: Quizzes }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Guard global JWT
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !authService.isAuthenticated()) {
    next("/login");
  } else {
    next();
  }
});

export default router;