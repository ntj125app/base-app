<script setup lang="ts">
import { ref, onBeforeMount } from 'vue';
import { storeToRefs } from 'pinia';
import { timeGreetings } from '../AppCommon';
import { useApiStore, useMainStore } from '../AppState';

import axios from 'axios';

import CmpLayout from '../Components/CmpLayout.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const timeGreet = timeGreetings();
const api = useApiStore();
const main = useMainStore();
const { appName, userName } = storeToRefs(main);

type ServerLogDataType = Array<{
    id: number;
    log_level: string;
    log_message: string;
    log_extra: string;
    created_at: string;
    updated_at: string;
}>;
type LogLevelSelectType = Array<{
    label: string;
    value: string;
}>;

const loadingstat = ref<boolean>(true);
const serverLogData = ref<Array<ServerLogDataType>>(Array<ServerLogDataType>());
const dateStartData = ref<Date>(new Date());
const dateEndData = ref<Date>(new Date());
const logLevelSelect = ref<LogLevelSelectType>([
    { label: 'All', value: 'all' },
    { label: 'Debug', value: 'debug' },
    { label: 'Info', value: 'info' },
    { label: 'Notice', value: 'notice' },
    { label: 'Warning', value: 'warning' },
    { label: 'Error', value: 'error' },
    { label: 'Critical', value: 'critical' },
    { label: 'Alert', value: 'alert' },
    { label: 'Emergency', value: 'emergency' },
]);
const logLevelData = ref<string>('all');
const logMessageData = ref<string>('');
const logExtraData = ref<string>('');

const getServerLogData = () => {
    loadingstat.value = true;
    axios
        .post(api.getServerLogs, {
            date_start: dateStartData.value,
            date_end: dateEndData.value,
            log_level: logLevelData.value,
            log_message: logMessageData.value,
            log_extra: logExtraData.value,
        })
        .then((response) => {
            serverLogData.value = response.data;
            loadingstat.value = false;
        })
        .catch((error) => {
            console.error(error.response.data);
        });
};

onBeforeMount(() => {
    getServerLogData();
});
</script>

<template>
    <CmpLayout>
        <div class="my-3 mx-5 p-5 bg-surface-200 rounded-lg drop-shadow-lg">
            <div class="flex justify-between">
                <div>
                    <h2 class="title-font font-bold">
                        {{ timeGreet + userName }}
                    </h2>
                    <h3 class="title-font">Server Log in {{ appName }}</h3>
                </div>
            </div>
        </div>
        <div class="my-3 mx-5 p-5 bg-surface-200 rounded-lg drop-shadow-lg">
            <div class="flex flex-row my-2">
                <div class="flex w-full px-1">
                    <div class="w-28 my-auto text-sm m-auto">Date Start</div>
                    <div class="flex w-full text-sm m-auto">
                        <DatePicker v-model="dateStartData" dateFormat="dd/mm/yy" class="w-full" />
                    </div>
                </div>
                <div class="flex w-full px-1">
                    <div class="w-28 my-auto text-sm m-auto">Date End</div>
                    <div class="flex w-full text-sm m-auto">
                        <DatePicker v-model="dateEndData" dateFormat="dd/mm/yy" class="w-full" />
                    </div>
                </div>
            </div>
            <div class="flex flex-row my-2">
                <div class="flex w-full px-1">
                    <div class="w-28 my-auto text-sm m-auto">Log Level Minimal</div>
                    <div class="flex w-full text-sm m-auto">
                        <Select
                            v-model="logLevelData"
                            :options="logLevelSelect"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select a Log Level"
                            class="w-full"
                        />
                    </div>
                </div>
                <div class="flex w-full px-1">
                    <div class="w-28 my-auto text-sm m-auto">Log Message</div>
                    <div class="flex w-full text-sm m-auto">
                        <InputText
                            v-model="logMessageData"
                            class="w-full"
                            placeholder="Log Message"
                        />
                    </div>
                </div>
            </div>
            <div class="flex flex-row my-2">
                <div class="flex w-full px-1">
                    <div class="w-28 my-auto text-sm m-auto">Log Extra</div>
                    <div class="flex w-full text-sm m-auto">
                        <InputText v-model="logExtraData" class="w-full" placeholder="Log Extra" />
                    </div>
                </div>
                <div class="flex w-full">
                    <div class="w-28 my-auto text-sm m-auto"></div>
                    <div class="flex w-full text-sm m-auto">
                        <Button icon="pi pi-search" label="Search" @click="getServerLogData" />
                    </div>
                </div>
            </div>
        </div>
        <div class="my-3 mx-5 p-5 bg-surface-200 rounded-lg drop-shadow-lg">
            <DataTable
                class="p-datatable-sm text-sm"
                :value="serverLogData"
                showGridlines
                paginator
                :rows="20"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageSelect"
                :rowsPerPageOptions="[10, 20, 50, 100]"
                :loading="loadingstat"
            >
                <template #footer>
                    <div class="flex text-sm">Total records: {{ serverLogData.length }}</div>
                </template>
                <template #empty>
                    <div class="flex justify-center">No data found</div>
                </template>
                <template #loading>
                    <i class="pi pi-spin pi-spinner mr-2.5"></i> Loading data. Please wait.
                </template>
                <Column field="created_at" header="Log Date">
                    <template #body="slotProps">
                        {{ new Date(slotProps.data.created_at).toLocaleString('en-UK') }}
                    </template>
                </Column>
                <Column field="message" header="Log Message" />
                <Column field="context" header="Log Extra" />
                <Column field="level_name" header="Level Name" />
            </DataTable>
        </div>
    </CmpLayout>
</template>
