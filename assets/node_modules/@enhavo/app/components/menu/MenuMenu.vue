<template>
    <div class="menu style-compact">
        <template v-for="item in items">
            <component class="menu-child" v-bind:is="item.component" v-bind:data="item"></component>
        </template>
    </div>
</template>

<script setup lang="ts">
import {MenuItemInterface} from "../../menu/MenuItemInterface";

const props = defineProps<{
    items: MenuItemInterface[],
}>()

</script>
