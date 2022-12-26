<script lang="ts">
  import { defineComponent } from 'vue'
  import axios from 'axios'
  import { useResponse, useError } from '../AppAxiosResp'
  import { useMainStore } from '../AppState'

  export default defineComponent({
  name: 'CmpReCaptcha',
  props: {
    captchaSiteKey: null,
  },
  
  data() {
    return {
    widgetId: null,
    }
  },

  methods: {
    execute() {
    this.init()
    },
    reset() {
    window.grecaptcha.reset(this.widgetId)
    },
    init() {
    if (window.grecaptcha && this.widgetId == null) {
      console.log('grecaptcha init')
      return this.widgetId = window.grecaptcha.render('recaptcha', {
      sitekey: this.captchaSiteKey,
      badge: 'inline',
      size: 'invisible',
      callback: (response: any) => {
        this.$emit('verify', response)
        this.reset()
      }
      })
    } else {
      console.log('grecaptcha exec')
      window.grecaptcha.execute(this.widgetId)
    }
    },
  },

  watch: {
    widgetId(newVal) {
    this.init()
    }
  }
  })
</script>

<template>
  <div id="recaptcha"></div>
</template>