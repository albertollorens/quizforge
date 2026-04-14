import { createRouter, createWebHistory } from "vue-router";

// Vistes
import Landing from "../views/Landing.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Dashboard from "../views/Dashboard.vue";
import Profile from "../views/Profile.vue";

// Layouts
import LandingLayout from '@/layouts/LandingLayout.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

// Serveis
import authService from "../services/authService";

// Components
import QuizList from "../components/QuizList.vue";
import QuizBuilder from "../components/QuizBuilder.vue";
import AIGenerator from "../components/AIGenerator.vue";
import AuthSuccess from "../views/AuthSuccess.vue";

const routes = [
  // 🔓 Públiques
  { path: "/", 
    component: LandingLayout,
    children: [
      {path: "", component: Landing }
    ]
   },  
  { path: "/login", component: Login },
  { path: "/register", component: Register },
  { path: '/auth/success', component: AuthSuccess },

  // 🔒 Protegides (SB Admin Layout)
  {
    path: "/dashboard",
    component: DashboardLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "profile",
        component: Profile
      },
      {
        path: "",
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