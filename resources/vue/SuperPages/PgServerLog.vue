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
  name: 'PgServerLog',
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
    serverLogData: new Array(),
    filters1: {
      'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
      'id': {value: null, matchMode: FilterMatchMode.EQUALS},
      'message': {value: null, matchMode: FilterMatchMode.CONTAINS},
      'context': {value: null, matchMode: FilterMatchMode.CONTAINS},
      'level': {value: null, matchMode: FilterMatchMode.EQUALS},
      'level_name': {value: null, matchMode: FilterMatchMode.CONTAINS},
    }
    }
  },

  methods: {
    getServerLogData() {
    let main = useMainStore()
    this.loadingstat = true
    axios.post(main.getServerLogs).then(response => {
      //console.log(response.data)
      this.serverLogData = response.data
      this.loadingstat = false
    }).catch(error => {
      console.error(error.response.data)
    })
    },

    clearFilter() {
    this.filters1 = {
      'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
      'id': {value: null, matchMode: FilterMatchMode.EQUALS},
      'message': {value: null, matchMode: FilterMatchMode.CONTAINS},
      'context': {value: null, matchMode: FilterMatchMode.CONTAINS},
      'level': {value: null, matchMode: FilterMatchMode.EQUALS},
      'level_name': {value: null, matchMode: FilterMatchMode.CONTAINS},
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
    this.getServerLogData()
  },
  })
</script>

<template>
  <CmpLayout>
  <div class="my-3 mx-5 p-5 bg-white rounded-lg drop-shadow-lg">
    <div class="flex justify-between">
    <div>
      <h2 class="title-font font-bold">{{ timeGreet + greetings }}</h2>
      <h3 class="title-font">Server Log in {{ appName }}</h3>
    </div>
    <div>
      <Button class="p-button-sm" icon="pi pi-refresh" @click.prevent="getServerLogData()" />
    </div>
    </div>
  </div>
  <div class="my-3 mx-5 p-1 bg-white rounded-lg drop-shadow-lg overflow-auto">
    <DataTable class="p-datatable-sm text-sm" :value="serverLogData" :dataKey="'id'" :resizableColumns="true" columnResizeMode="expand" showGridlines responsiveLayout="scroll" :paginator="true" :rows="20" :sortField="'id'" :sortOrder="-1" v-model:filters="filters1" filterDisplay="row" :globalFilterFields="['id','message','context','level','level_name']" :loading="loadingstat" >
    <template #header>
      <div class="flex justify-content-between w-full">
      <Button type="button" icon="pi pi-filter-slash" label="Clear" class="p-button-outlined mx-1" @click.prevent="clearFilter()"/>
      <span class="p-input-icon-left ml-1 w-full">
        <i class="pi pi-search" />
        <InputText v-model="filters1['global'].value" placeholder="Search Log ID, Message, Extra, Level ID, Level name" class="w-full" />
      </span>
      </div>
    </template>
    <template #empty>
      <div class="flex justify-center">No data found</div>
    </template>
    <template #loading>
      <i class="pi pi-spin pi-spinner mr-2.5"></i> Loading data. Please wait.
    </template>
    <template #footer>
      <div class="w-full flex flex-row-reverse">
      Total of {{serverLogData?.length}} records
      </div>
    </template>
    <Column field="id" header="Log ID">
      <template #filter="{filterModel,filterCallback}">
      <InputText type="text" v-model="filterModel.value" @change="filterCallback()" />
      </template>
    </Column>
    <Column field="created_at" header="Log Date">
      <template #body="slotProps">
      {{ new Date(slotProps.data.created_at).toLocaleString("en-UK") }}
      </template>
    </Column>
    <Column field="message" header="Log Message">
      <template #filter="{filterModel,filterCallback}">
      <InputText type="text" v-model="filterModel.value" @change="filterCallback()" />
      </template>
    </Column>
    <Column field="context" header="Log Extra">
      <template #filter="{filterModel,filterCallback}">
      <InputText type="text" v-model="filterModel.value" @change="filterCallback()" />
      </template>
    </Column>
    <Column field="level" header="Level ID">
      <template #filter="{filterModel,filterCallback}">
      <InputText type="text" v-model="filterModel.value" @change="filterCallback()" />
      </template>
    </Column>
    <Column field="level_name" header="Level Name">
      <template #filter="{filterModel,filterCallback}">
      <InputText type="text" v-model="filterModel.value" @change="filterCallback()" />
      </template>
    </Column>
    </DataTable>
  </div>
  </CmpLayout>
</template>