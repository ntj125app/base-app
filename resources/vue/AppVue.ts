import { createApp } from 'vue'
import { createPinia } from 'pinia'
const pinia: any = createPinia()
import PrimeVue from 'primevue/config'

/** Vue router needed for navigation menu */
import { router } from './AppRouter'

/** Primevue Globals */
import DialogService from 'primevue/dialogservice'

// Mount Application Instances
import App from '../vue/App.vue'
const MainApp: any = createApp(App)
  .use(router)
  .use(pinia)
  .use(PrimeVue)
  .use(DialogService)

router.isReady().then(() => {
  MainApp.mount('#app')
})