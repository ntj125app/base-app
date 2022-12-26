<script lang="ts">
  import { defineComponent } from 'vue'
  import axios from 'axios'
  import { useResponse, useError } from '../AppAxiosResp'
  import { useMainStore } from '../AppState'
  import CmpLayout from '../Components/CmpLayout.vue'

  import { timeGreetings, timeView } from '../AppCommon'
  
  import Button from 'primevue/button'
  import DataTable from 'primevue/datatable'
  import Column from 'primevue/column'
  import ColumnGroup from 'primevue/columngroup'
  import Row from 'primevue/row'
  import { FilterMatchMode } from 'primevue/api'
  import InputText from 'primevue/inputtext'

  export default defineComponent({
  name: 'PgUserMan',
  props: {
    appName: null,
    greetings: null,
  },

  components: {
    CmpLayout,
    Button,
    DataTable,
    Column,
    ColumnGroup,
    Row,
    InputText,
  },
  
  data() {
    return {
    loadingstat: true,
    userListData: new Array(),
    filters1:  {
      'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
      'id': {value: null, matchMode: FilterMatchMode.EQUALS},
      'name': {value: null, matchMode: FilterMatchMode.CONTAINS},
      'username': {value: null, matchMode: FilterMatchMode.CONTAINS},
    }
    }
  },

  methods: {
    getUserListData() {
    let main = useMainStore()
    this.loadingstat = true
    axios.post(main.getUserList).then(response => {
      //console.log(response.data)
      this.userListData = response.data
      this.loadingstat = false
    }).catch(error => {
      console.error(error.response.data)
    })
    },

    clearFilter() {
    this.filters1 = {
      'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
      'id': {value: null, matchMode: FilterMatchMode.EQUALS},
      'name': {value: null, matchMode: FilterMatchMode.CONTAINS},
      'username': {value: null, matchMode: FilterMatchMode.CONTAINS},
    }
    },
  },

  setup() {
    const main = useMainStore()
    const timeGreet = timeGreetings()
    return { main, timeGreet }
  },

  created() {
    const main = useMainStore()
    this.getUserListData()
  },
  })
</script>

<template>
  <CmpLayout>
  <div class="my-3 mx-5 p-5 bg-white rounded-lg drop-shadow-lg">
    <h2 class="title-font font-bold">{{ timeGreet + greetings }}</h2>
    <h3 class="title-font">User management in {{ appName }}</h3>
  </div>
  <div class="my-3 mx-5 p-1 bg-white rounded-lg drop-shadow-lg overflow-auto">
    <DataTable class="p-datatable-sm text-sm" :value="userListData" :dataKey="'id'" :resizableColumns="true" columnResizeMode="expand" showGridlines responsiveLayout="scroll" :paginator="true" :rows="20" :sortField="'id'" :sortOrder="-1" v-model:filters="filters1" filterDisplay="row" :globalFilterFields="['id','name','username']" :loading="loadingstat" >
    <template #header>
      <div class="flex justify-content-between w-full">
      <Button type="button" icon="pi pi-filter-slash" label="Clear" class="p-button-outlined mx-1" @click.prevent="clearFilter()"/>
      <span class="p-input-icon-left ml-1 w-full">
        <i class="pi pi-search" />
        <InputText v-model="filters1['global'].value" placeholder="Search User ID, User Name, User Login" class="w-full" />
      </span>
      </div>
    </template>
    <template #empty>
      No data found.
    </template>
    <template #loading>
      <i class="pi pi-spin pi-spinner mr-2.5"></i> Loading data. Please wait.
    </template>
    <template #footer>
      <div class="w-full flex flex-row-reverse">
      Total of {{userListData?.length}} records
      </div>
    </template>
    <Column field="user_action" header="Actions">
    </Column>
    <Column field="id" header="User ID">
      <template #filter="{filterModel,filterCallback}">
      <InputText type="text" v-model="filterModel.value" @change="filterCallback()" />
      </template>
    </Column>
    <Column field="name" header="User Name">
      <template #filter="{filterModel,filterCallback}">
      <InputText type="text" v-model="filterModel.value" @change="filterCallback()" />
      </template>
    </Column>
    <Column field="username" header="User Login">
      <template #filter="{filterModel,filterCallback}">
      <InputText type="text" v-model="filterModel.value" @change="filterCallback()" />
      </template>
    </Column>
    </DataTable>
  </div>
  </CmpLayout>
</template>