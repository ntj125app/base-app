import { defineStore } from 'pinia'

import {
  landingPage,
  dashboard,
  profile,
  serverHorizon,
  serverLogs,
  userMan,
} from './AppPath'

export const useMainStore = defineStore('main', {
  state: () => ({
  /** Define route here because if not defined and get from XHR it will be race condition */
  /** WEB requests */
  landingPage: landingPage,
  dashboard: dashboard,
  profile: profile,
  serverHorizon: serverHorizon,
  serverLogs: serverLogs,
  userMan: userMan,
  
  /** API request */
  postLogin: '/api/post-login',
  postLogout: '/api/post-logout',
  postProfile: '/api/update-profile',
  appConst: '/api/app-const',
  getAllUserPermission: '/api/get-all-user-permission',
  logAgent: '/api/log-agent',
  getServerLogs: '/api/get-server-logs',
  getUserList: '/api/get-user-list',

  /** Additional data */
  browserSuppport: false,
  permissionsData: new Array(),
  })
})