<template>
    <div class="tab-container">
        <div v-once ref="container"></div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Tab from "@enhavo/app/Form/Tab";
    import * as $ from 'jquery';

    @Component()
    export default class AppView extends Vue {
        name = 'tab-container';

        @Prop()
        tab: Tab;

        mounted() {
            $(this.$refs.container).append(this.tab.element);
        }
    }
</script>

<style lang="scss">

</style>
