<template>
    <div class="actions">
        <div class="primary-actions">
            <template v-for="action in primary">
                <component :is="action.component" :data="action"></component>
            </template>
        </div>
        <div class="secondary-actions">
            <template v-for="action in secondary">
                <component :is="action.component" :data="action"></component>
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
