<script lang="ts">
  import { defineComponent } from 'vue'
  import axios from 'axios'
  import { useResponse, useError } from '../AppAxiosResp'
  import { useMainStore } from '../AppState'
  import CmpLayout from '../Components/CmpLayout.vue'

  import { timeGreetings, timeView } from '../AppCommon'

  import InputText from 'primevue/inputtext'
  import Button from 'primevue/button'

  export default defineComponent({
  name: 'PgProfile',
  props: {
    appName: null,
    greetings: null,
    userName: null,
  },

  components: {
    CmpLayout,
    InputText,
    Button,
  },

  data() {
    return {
    name: this.userName,
    }
  },

  methods: {
    postProfileData() {
    let main = useMainStore()
    axios.post(main.postProfile, {
      name: this.name,
    }).then(response => {
      useResponse(response)
    }).catch(error => {
      useError(error)
    })
    }
  },

  setup() {
    const main = useMainStore()
    const timeGreet = timeGreetings()
    return { main, timeGreet }
  },

  created() {
    const main = useMainStore()
  },
  })
</script>

<template>
  <CmpLayout>
  <div class="my-3 mx-5 p-5 bg-white rounded-lg drop-shadow-lg">
    <h2 class="title-font font-bold">{{ timeGreet + greetings }}</h2>
    <h3 class="title-font">Update profile in {{ appName }}</h3>
    <div class="mt-10 mb-5">
    <span class="p-float-label w-full">
      <InputText type="text" id="name" class="w-full" v-model="name" @keyup.enter="postProfileData" />
      <label class="w-full" for="name">Name</label>
    </span>
    </div>
    <div class="flex justify-center">
    <Button class="p-button-primary p-button-sm" label="Update Profile" @click="postProfileData" />
    </div>
  </div>
  </CmpLayout>
</template>