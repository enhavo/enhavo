<template>
    <div class="actions">
        <div class="primary-actions">
            <template v-for="action in primary">
                <component class="action-container" v-bind:is="action.component" v-bind:data="action" :data-action="action.key"></component>
            </template>
        </div>
        <div class="secondary-actions">
            <template v-for="action in secondary">
                <component class="action-container" v-bind:is="action.component" v-bind:data="action"></component>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ActionInterface} from "../../action/ActionInterface";

const props = defineProps<{
    primary?: ActionInterface[],
    secondary?: ActionInterface[],
}>()
</script>
