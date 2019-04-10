<template>
    <div class="view">
        <div v-if="data.confirm">
            {{ data.confirm.message }}
            <div @click="data.confirm.accept()">{{ data.confirm.acceptText }}</div>
            <div @click="data.confirm.deny()">{{ data.confirm.denyText }}</div>
        </div>

        <div v-if="data.alert">
            {{ data.alert }}
            <div @click="data.alert = null">Ok</div>
        </div>

        <div v-if="data.loading">Loading</div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ViewData from "@enhavo/app/View/ViewData";

    @Component()
    export default class ViewComponent extends Vue {
        name = 'view-view';

        @Prop()
        data: ViewData;
    }
</script>