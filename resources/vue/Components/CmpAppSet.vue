<script lang="ts">
  import { defineComponent } from 'vue'
  import axios from 'axios'
  import { useResponse, useError } from '../AppAxiosResp'
  import { useMainStore } from '../AppState'

  import { supportedBrowsers } from '../../ts/browser'

  import DynamicDialog from 'primevue/dynamicdialog'

  export default defineComponent({
    name: 'CmpAppSet',

    components: {
      DynamicDialog,
    },

    methods: {
      init(main: any) {
        /** Get Constant */
        axios.post(main.appConst).then((response) => {
          /** Send response data to after init function & if user authenticated */
          if (response.data.isAuth) {
            this.authInit(main)
          }
        }).catch(error => {
          console.error(error.response.data)
        })
      },

      authInit(main: any) {
        axios.post(main.getAllUserPermission).then(response => {
          main.$patch({permissionsData: response.data})
        }).catch(error => {
          console.error(error.response.data)
        })
      },

      logAgent() {
        let main = useMainStore()
        axios.post(main.logAgent)
      }
    },

    setup() {
      const main = useMainStore()
      return { main }
    },

    created() {
      const main = useMainStore()

      /** Init all constant */
      this.init(main)

      /**
       * Test if browser is compatible
       */
      if (supportedBrowsers.test(navigator.userAgent)) {
        main.$patch({browserSuppport: true})
      } else {
        main.$patch({browserSuppport: false})
        this.logAgent()
      }
    },
  })
</script>

<template>
  <DynamicDialog />
</template>