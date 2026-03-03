import { createRouter, createWebHistory } from "vue-router";

// Vistes
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Dashboard from "../views/Dashboard.vue";
import Profile from "../views/Profile.vue";

// Layout
import DashboardLayout from "../layouts/DashboardLayout.vue";

// Serveis
import authService from "../services/authService";
import QuizList from "../components/QuizList.vue";
import QuizBuilder from "../components/QuizBuilder.vue";
import AIGenerator from "../components/AIGenerator.vue";

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
        path: "profile",
        component: Profile
      },
      {
        path: "dashboard",
        component: Dashboard,
        children: [
          {
            path: '/',
            component: QuizList
          },
          {
            path: "nouquiz",
            component: QuizBuilder,
            props: { mode: 'create' }
          },
          {
            path: "editquiz/:id",
            component: QuizBuilder,
            props: true
          }
        ]
      },
      {
        path: "/aiquiz",
        component: AIGenerator
      }
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