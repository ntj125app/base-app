<script setup lang="ts">
import { storeToRefs } from 'pinia';

import { useMainStore } from '../AppState';

import MenuPanel from '../volt/MenuPanel.vue';

const main = useMainStore();
const { appName, menuItems, expandedKeysMenu } = storeToRefs(main);
</script>

<template>
    <div class="flex m-5 w-full">
        <div class="flex flex-col w-full">
            <div class="flex flex-row">
                <div class="flex font-bold text-center mx-1">
                    {{ appName }}
                </div>
            </div>
            <div class="flex w-full mt-10">
                <MenuPanel :expandedKeys="expandedKeysMenu" :model="menuItems" />
            </div>
        </div>
    </div>
</template>
