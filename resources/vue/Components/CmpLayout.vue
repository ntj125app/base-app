<script lang="ts">
  import { defineComponent } from 'vue'
  import { useMainStore } from '../AppState'
  import { useResponse, useError } from '../AppAxiosResp'
  import axios from 'axios'

  import CmpHeader from '../Components/CmpHeader.vue'
  import CmpFooter from '../Components/CmpFooter.vue'

  export default defineComponent({
  name: 'CmpLayout',

  components: {
    CmpHeader,
    CmpFooter,
  }
  })
</script>

<template>
  <div class="min-h-screen h-auto bg-slate-200">
  <CmpHeader />
    <div class="flex flex-col min-h-fit content-start">
    <slot />
    </div>
  <CmpFooter />
  </div>
</template>