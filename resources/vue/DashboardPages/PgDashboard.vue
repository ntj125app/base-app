<script lang="ts">
  import { defineComponent } from 'vue'
  import axios from 'axios'
  import { useResponse, useError } from '../AppAxiosResp'
  import { useMainStore } from '../AppState'
  import CmpLayout from '../Components/CmpLayout.vue'

  import { timeGreetings, timeView } from '../AppCommon'

  export default defineComponent({
  name: 'PgDash',
  props: {
    appName: null,
    greetings: null,
  },

  components: {
    CmpLayout,
  },
  
  data() {
    return {
    clock: new Date().toLocaleString("en-UK"),
    }
  },

  methods: {
  },

  setup() {
    const main = useMainStore()
    const timeGreet = timeGreetings()
    return { main, timeGreet }
  },

  created() {
    const main = useMainStore()

    /** Ticking clock */
    setInterval(() => {
    this.clock = new Date().toLocaleString("en-UK")
    })
  },
  })
</script>

<template>
  <CmpLayout>
  <div class="my-3 mx-5 p-5 bg-white rounded-lg drop-shadow-lg">
    <h2 class="title-font font-bold">{{ timeGreet + greetings }}</h2>
    <h3 class="title-font">Welcome to {{ appName }}</h3>
  </div>
  </CmpLayout>
</template>