<template>
    <table>
        <tbody>
            <tr v-if="type">
                <td>Type</td>
                <td><code>{{ type }}</code></td>
            </tr>
            <tr v-if="className">
                <td>Class</td>
                <td><code>{{ className }}</code></td>
            </tr>
            <tr v-if="parent">
                <td>Parent</td>
                <td><code>{{ parent }}</code></td>
            </tr>
            <tr v-if="slots.options">
                <td>Options</td>
                <td>
                    <slot name="options"></slot>
                </td>
            </tr>
            <tr v-if="slots.inherit">
                <td>Inherit Options</td>
                <td>
                    <slot name="inherit"></slot>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script setup lang="ts">
import {defineProps} from "vue";
import { useSlots } from 'vue'

const slots = useSlots()
const props = defineProps<{
    type: string
    className: string
    parent: string
}>()
</script>