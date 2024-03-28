<template>
    <div class="tab-head" @click="changeTab" :class="{'selected': tab.active, 'has-error': tab.error}">
        {{ tab.label }}
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import Tab from "@enhavo/app/form/Tab";
import Form from "@enhavo/app/form/Form";

const props = defineProps<{
    tab: Tab,
}>()

const form = inject<Form>('form');

function changeTab()
{
    form.changeTab(props.tab.key);
}
</script>
