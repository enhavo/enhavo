<template>
    <div class="action" v-click-outside="() => data.close()">
        <div @click="execute($event)">
            <div class="action-icon">
                <i :class="['icon', 'icon-' + data.icon]"></i>
            </div>
            <div class="label">{{ data.label }}</div>
        </div>
        <ul class="dropdown-action-list" v-show="data.isOpen" @click="() => data.closeAfter ? data.close() : false">
            <template v-for="action in data.items">
                <li>
                    <component class="action-container" :is="action.component" :data="action"></component>
                </li>
            </template>
        </ul>
    </div>
</template>

<script setup lang="ts">
import {DropdownAction} from "@enhavo/app/action/model/DropdownAction";
import {onMounted} from "vue";

const props = defineProps<{
    data: DropdownAction,
    clickStop?: boolean,
}>();

function getIcon(): string
{
    return (props.data && props.data.icon) ? 'icon-' + props.data.icon : '';
}

function execute(event: Event)
{
    if (props.clickStop) {
        event.stopPropagation();
    }
    props.data.execute()
}

onMounted(() => {
    props.data.mounted()
})

</script>
