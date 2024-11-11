<template>
    <action-action :data="data" :click-stop="clickStop" ></action-action>
</template>

<script setup lang="ts">
import {PreviewAction} from "@enhavo/app/action/model/PreviewAction";
import {onMounted} from "vue";

const props = defineProps<{
    data: PreviewAction,
    clickStop?: boolean,
}>()

onMounted(() => {
    props.data.mounted()
})
</script>
