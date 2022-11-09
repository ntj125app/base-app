import { createApp } from 'vue'
import { createPinia } from 'pinia'
const pinia: any = createPinia()
import PrimeVue from 'primevue/config'

/** Vue router needed for navigation menu */
import { router } from './AppRouter'

// Mount Application Instances
import App from '../vue/App.vue'
const MainApp: any = createApp(App)
  .use(router)
  .use(pinia)
  .use(PrimeVue)

router.isReady().then(() => {
  MainApp.mount('#app')
})