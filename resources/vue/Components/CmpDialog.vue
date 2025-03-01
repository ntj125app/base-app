<script setup lang="ts">
import { ref } from 'vue';

import Dialog from 'primevue/dialog';
import Button from 'primevue/button';

const props = defineProps<{
    dialogTypeSuccess: boolean;
    dialogOpenProps: boolean;
    dialogHeaderProps: string;
    dialogMessageProps: string;
}>();

const dialogOpen = ref(props.dialogOpenProps);
const autoCloseSuccessSeconds = ref(5);
const autoCloseFailedSeconds = ref(300);

const openDialog = () => {
    dialogOpen.value = true;
    autoCloseDialog();
};

const closeDialog = () => {
    dialogOpen.value = false;
};

const autoCloseDialog = () => {
    if (props.dialogTypeSuccess) {
        setTimeout(() => {
            closeDialog();
        }, autoCloseSuccessSeconds.value * 1000);
    } else {
        setTimeout(() => {
            closeDialog();
        }, autoCloseFailedSeconds.value * 1000);
    }
};

defineExpose({
    openDialog,
    closeDialog,
});
</script>

<template>
    <Dialog :visible="dialogOpen" modal :draggable="false" :closable="false">
        <template #default>
            <div class="flex w-full justify-center my-2.5 text-lg font-bold">
                <i
                    v-if="props.dialogTypeSuccess"
                    class="pi pi-check-circle text-8xl text-green-500"
                />
                <i v-else class="pi pi-times-circle text-8xl text-red-500" />
            </div>
            <div class="flex w-full justify-center my-2.5 text-lg font-bold">
                {{ props.dialogHeaderProps }}
            </div>
            <div class="flex w-full justify-center my-2.5">
                {{ props.dialogMessageProps }}
            </div>
            <div class="flex w-full justify-center my-2.5">
                <Button label="Close" @click="closeDialog" />
            </div>
        </template>
    </Dialog>
</template>
