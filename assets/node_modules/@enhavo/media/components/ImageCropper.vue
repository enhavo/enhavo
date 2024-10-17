<template>
    <div class="app-view">
        <view-view></view-view>
        <flash-messages></flash-messages>
        <action-bar></action-bar>
        <image-cropper></image-cropper>
    </div>
</template>

<script setup lang="ts">
import {onMounted} from "vue";
import '@enhavo/app/assets/styles/view.scss';

const props = defineProps<{
    format: any
}>()

onMounted(() => {
    $(document).on('change', ':input', () => {
        props.format.changed = true;
    });
})

</script>

<style lang="css">
    .app-view { height: 100vh }
</style>
