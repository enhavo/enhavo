<template>
    <div class="app-view">
        <view-view></view-view>
        <flash-messages></flash-messages>
        <action-bar></action-bar>
        <list-list></list-list>
    </div>
</template>

<script lang="ts">
    import { Vue, Component } from "vue-property-decorator";
    import '@enhavo/app/assets/styles/view.scss'

    @Component
    export default class ListApplicationComponent extends Vue
    {

    }
</script>
