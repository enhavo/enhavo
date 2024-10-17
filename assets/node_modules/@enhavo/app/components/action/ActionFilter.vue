<template>
    <div class="action" v-click-outside="clickOutside">
        <div @click="data.toggleOpen()">
            <div class="action-icon">
                <i :class="['icon', 'icon-' + data.icon]"></i>
            </div>
            <div class="label">{{ data.label }}</div>
        </div>
        <ul v-if="data.open && data.loaded" class="dropdown-action-list" >
            <template v-for="filter in data.getFilters()">
                <div @click="data.toggleFilter(filter)" class="action">
                    <div class="action-icon">
                        <i class="icon" :class="getIcon(filter)"></i>
                    </div>
                    <div class="label">{{ filter.label }}</div>
                </div>
            </template>
        </ul>
    </div>
</template>

<script setup lang="ts">
import {FilterAction} from "@enhavo/app/action/model/FilterAction"
import {FilterInterface} from "@enhavo/app/filter/FilterInterface";
import {onMounted} from "vue";

const props = defineProps<{
    data: FilterAction
}>()


onMounted(() => {
    props.data.mounted();
})


function clickOutside()
{
    props.data.open = false;
}

function getIcon(filter: FilterInterface)
{
    if (filter.active) {
        return 'icon-remove_circle_outline';
    } else {
        return 'icon-add_circle_outline';
    }
}
</script>
