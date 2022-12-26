<script lang="ts">
  import { defineComponent } from 'vue'
  import { useMainStore } from '../AppState'
  import { useResponse, useError } from '../AppAxiosResp'
  import axios from 'axios'

  import Button from 'primevue/button'
  import InputText from 'primevue/inputtext'
  import Password from 'primevue/password'

  export default defineComponent({
  name: 'PgLogin',
  props: {
    appName: null,
  },

  components: {
    Button,
    InputText,
    Password,
  },

  data() {
    return {
    username: '',
    password: '',
    }
  },

  methods: {
    clearData() {
    this.username = ''
    this.password = ''
    },

    postLogindata() {
    let main = useMainStore()
    axios.post(main.postLogin, {
      username: this.username,
      password: this.password,
    }).then(response => {
      useResponse(response)
      this.clearData()
    }).catch(error => {
      useError(error)
    })
    },
  },

  setup() {
    const main = useMainStore()
    return { main }
  },
  })
</script>

<template>
  <div class="grid content-center w-screen h-screen bg-picture">
  <div class="flex justify-center">
    <div class="bg-white rounded-lg drop-shadow-lg">
    <div class="m-auto p-5">
      <div class="text-center font-bold my-2.5">{{ appName }}</div>
      <div class="text-center font-bold my-2.5" v-if="!main.browserSuppport">
      <Button class="p-button-sm p-button-danger" label="Browser Unsupported" />
      </div>
      <div class="text-center font-bold my-2.5">Login to your account</div>
      <div class="flex justify-center flex-col mt-8 my-2.5 p-float-label">
      <div class="w-full">
        <span class="p-float-label w-full">
        <InputText type="text" id="username" class="text-center w-full" v-model="username" @keyup.enter="postLogindata" />
        <label class="w-full" for="username">Username</label>
        </span>
      </div>
      </div>
      <div class="flex justify-center flex-col mt-8 my-2.5 p-float-label">
      <div class="w-full">
        <span class="p-float-label w-full">
        <Password type="text" id="password" class="w-full" input-class="text-center" v-model="password" :feedback="false" @keyup.enter="postLogindata" />
        <label class="w-full" for="password">Password</label>
        </span>
      </div>
      </div>
      <div class="flex justify-center py-2.5">
      <Button class="p-button-primary p-button-sm" label="Login" @click="postLogindata" />
      </div>
    </div>
    </div>
  </div>
  </div>
</template>