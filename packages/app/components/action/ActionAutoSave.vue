<template>
    <action-action :data="data" :click-stop="clickStop" ></action-action>
</template>

<script setup lang="ts">
import {AutoSaveAction} from "@enhavo/app/action/model/AutoSaveAction";
import {onMounted} from "vue";

const props = defineProps<{
    data: AutoSaveAction,
    clickStop?: boolean,
}>()

onMounted(() => {
    props.data.mounted()
})
</script>
