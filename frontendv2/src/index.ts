import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'Landing',
      component: () => import('../views/Landing.vue'),
      meta: {
        title: 'Quizforge | Craft your quizzes',
      },
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: () => import('../views/Dashboard.vue'),
      meta: {
        title: 'Quizforge | Dashboard',
      },
    },
    {
      path: '/dashboard/quizzes',
      name: 'Quizzes',
      component: () => import('../views/Quiz/DashboardQuiz.vue'),
      meta: {
        title: 'Quizzes',
      },
    },
    {
      path: '/dashboard/nouquiz',
      name: 'Nou Quiz',
      component: () => import('../views/Quiz/DashboardQuiz.vue'),
      meta: {
        title: 'Nou Quiz',
      },
    },
    {
      path: '/dashboard/aiquiz',
      name: 'AI Quiz',
      component: () => import('../views/Quiz/DashboardAIQuiz.vue'),
      meta: {
        title: 'AI Quiz',
      },
    },
    {
      path: '/profile',
      name: 'Profile',
      component: () => import('../views/Others/UserProfile.vue'),
      meta: {
        title: 'Profile',
      },
    },
    {
      path: '/userplan',
      name: 'UserPlan',
      component: () => import('../views/Others/UserPlan.vue'),
      meta: {
        title: 'User Plan',
      },
    },
    {
      path: '/settings',
      name: 'Settings',
      component: () => import('../views/Others/UserSettings.vue'),
      meta: {
        title: 'User Settings',
      },
    },
    {
      path: '/chat',
      name: 'Xat',
      component: () => import('../views/Support/Chat.vue'),
      meta: {
        title: 'Xat',
      },
    },
    {
      path: '/email',
      name: 'Email',
      component: () => import('../views/Support/Email.vue'),
      meta: {
        title: 'Email',
      },
    },
    {
      path: '/ticketlist',
      name: 'Tickets de suport',
      component: () => import('../views/Support/TicketList.vue'),
      meta: {
        title: 'Tickets de suport',
      },
    },
    {
      path: '/ticketreply',
      name: 'Resposta del Ticket',
      component: () => import('../views/Support/TicketReply.vue'),
      meta: {
        title: 'Resposta del Ticket',
      },
    },
    {
      path: '/help',
      name: 'Ajuda',
      component: () => import('../views/Support/Help.vue'),
      meta: {
        title: 'Ajuda',
      },
    },
    {
      path: '/badge',
      name: 'Badge',
      component: () => import('../views/UiElements/Badges.vue'),
      meta: {
        title: 'Badge',
      },
    },

    {
      path: '/blank',
      name: 'Blank',
      component: () => import('../views/Pages/BlankPage.vue'),
      meta: {
        title: 'Blank',
      },
    },

    {
      path: '/error-404',
      name: '404 Error',
      component: () => import('../views/Errors/FourZeroFour.vue'),
      meta: {
        title: '404 Error',
      },
    },

    {
      path: '/signin',
      name: 'Signin',
      component: () => import('../views/Auth/Signin.vue'),
      meta: {
        title: 'Signin',
      },
    },
    {
      path: '/signup',
      name: 'Signup',
      component: () => import('../views/Auth/Signup.vue'),
      meta: {
        title: 'Signup',
      },
    },
  ],
})

export default router

router.beforeEach((to, from, next) => {
  //document.title = `Vue.js ${to.meta.title} | TailAdmin - Vue.js Tailwind CSS Dashboard Template`
  document.title = `Quizforge | Dashboard`
  next()
})
