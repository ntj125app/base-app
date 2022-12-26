import { createRouter, createMemoryHistory } from 'vue-router'

/** WEB Routes */
import {
  landingPage,
  dashboard,
  profile,
  serverHorizon,
  serverLogs,
  userMan,
} from './AppPath'

const routes = [
  { path: landingPage, name: 'landingPage', component: () => import('./AuthPages/PgLogin.vue') },
  { path: dashboard, name: 'dashboard', component: () => import('./DashboardPages/PgDashboard.vue') },
  { path: profile, name: 'profile', component: () => import('./DashboardPages/PgProfile.vue') },
  { path: serverHorizon, name: 'serverHorizon', redirect: '/horizon' },
  { path: serverLogs, name: 'serverLogs', component: () => import('./SuperPages/PgServerLog.vue') },
  { path: userMan, name: 'userMan', component: () => import('./SuperPages/PgUserMan.vue') },
]

export const router = createRouter({
  history: createMemoryHistory(),
  routes,
})