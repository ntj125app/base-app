<script lang="ts">
  import { defineComponent } from 'vue'
  import axios from 'axios'
  import { useResponse, useError } from '../AppAxiosResp'
  import { useMainStore } from '../AppState'

  import Button from 'primevue/button'
  import TieredMenu from 'primevue/tieredmenu'

  const win: Window = window; // Window location workaround

  export default defineComponent({
  name: 'CmpHeader',
  components: {
    Button,
    TieredMenu,
  },

  data() {
    let main = useMainStore()
    return {
    echoEnable: true,
    pusherState: window.Echo.connector.pusher.connection.state,

    standardMenuItems: [
      /** Standard Responsibility */
      {
      label: 'Dashboard',
      icon: 'pi pi-home',
      url: main.dashboard,
      },
      {
      label: 'Edit Profile',
      icon: 'pi pi-user-edit',
      url: main.profile,
      },
      {
      label: 'Logout',
      icon: 'pi pi-power-off',
      command: () => {
        this.logoutSubmit()
      }
      },
    ],

    rootMenuItems: [
      /** Permission SU */
      {
      label: 'Server Systems',
      items: [
        {
        label: 'Server Queue - Horizon',
        icon: 'pi pi-bolt',
        url: main.serverHorizon,
        },
        {
        label: 'Server Log',
        icon: 'pi pi-server',
        url: main.serverLogs,
        },
        {
        label: 'User Managment',
        icon: 'pi pi-users',
        url: main.userMan,
        },
      ],
      },
    ],
    }
  },

  methods: {
    logoutSubmit() {
    let main = useMainStore()
    axios.post(main.postLogout).then(response => {
      useResponse(response)
    }).catch(error => {
      useError(error)
    })
    },

    toggleMenu(event: any) {
    (this.$refs.menu as any).toggle(event);
    }
  },

  computed: {
    showMenu(): any {
    let main = useMainStore()
    let menu = this.standardMenuItems

    /** Use Concat not push because we create new array / merge 2 array, not pushing object of array into existing array */

    if (main.permissionsData?.includes('root')) {
      menu = menu.concat(this.rootMenuItems as any)
    }

    return menu
    }
  },

  setup() {
    const main = useMainStore()
    return { main }
  },

  created() {
    const main = useMainStore()
    /** Ticking status for pusher */
    setInterval(() => {
    this.pusherState = window.Echo.connector.pusher.connection.state
    let EchoStatusElement = document.getElementById("EchoStatus");
    if (EchoStatusElement !== null) {
      switch(this.pusherState) {
      case 'connecting':
        EchoStatusElement.className = "pi pi-spin pi-spinner echo-connect-loading";
        break;
      case 'connected':
        EchoStatusElement.className = "pi pi-circle-fill echo-connect-connected";
        break;
      case 'unavailable':
        EchoStatusElement.className = "pi pi-circle-fill echo-connect-failed";
        break;
      case 'failed':
        EchoStatusElement.className = "pi pi-circle-fill echo-connect-failed";
        break;
      case 'disconnected':
        EchoStatusElement.className = "pi pi-spin pi-spinner echo-connect-loading";
        break;
      default:
        EchoStatusElement.className = "pi pi-spin pi-spinner echo-connect-loading";
      }
    }
    }, 1000)
  },
  })
</script>

<template>
  <div class="sticky top-0 w-full z-50 drop-shadow-xl">
  <div class="bg-indigo-800 py-3 px-5 flex flex-row">
    <div class="flex flex-row w-full">
    <Button type="button" @click.prevent="toggleMenu" icon="pi pi-bars" label="Menu" class="p-button-sm" />
    <TieredMenu ref="menu" :model="showMenu" :popup="true" />
    </div>

    <div class="flex flex-row-reverse w-full">
    <div class="flex flex-row-reverse w-full mt-1 mb-1" v-if="echoEnable && main.browserSuppport">
      <button id="EchoStatus" class="pi pi-spin pi-spinner echo-connect-loading"></button>
    </div>
    <div class="flex flex-row-reverse w-full mt-1 mb-1" v-if="!main.browserSuppport">
      <Button class="p-button-sm p-button-danger" label="Browser Unsupported" />
    </div>
    </div>
  </div>
  </div>
</template>