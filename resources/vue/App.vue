<script lang="ts">
  import { defineComponent } from 'vue'
  import axios from 'axios'
  import CmpAppSet from './Components/CmpAppSet.vue'

  import PgLogin from './AuthPages/PgLogin.vue'
  import PgDashboard from './DashboardPages/PgDashboard.vue'
  import PgProfile from './DashboardPages/PgProfile.vue'
  import PgUserMan from './SuperPages/PgUserMan.vue'
  import PgServerLog from './SuperPages/PgServerLog.vue'

  export default defineComponent({
  name: 'App',
  components: {
    CmpAppSet,
    PgLogin,
    PgDashboard,
    PgProfile,
    PgUserMan,
    PgServerLog,
  },

  setup() {
    /**
     * Get new CSRF Token set everytime app is created
     */
    axios.get('/sanctum/csrf-cookie').then(() => {
    console.log('csrf cookie init')
    })
  },
  })
</script>