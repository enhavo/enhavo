<template>
    <div class="tab-head" @click="changeTab" :class="{'selected': selected}">
        {{ tab.label }}
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Tab from "@enhavo/app/Form/Tab";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import FormApplication from "@enhavo/app/Form/FormApplication";
    const application = <FormApplication>ApplicationBag.getApplication();

    @Component()
    export default class AppView extends Vue {
        name = 'tab-head';

        @Prop()
        tab: Tab;

        @Prop()
        selected: boolean;

        changeTab() {
            application.getForm().changeTab(this.tab.key);
        }
    }
</script>

<style lang="scss">
.tab-head {
    height: 100px;
    padding: 30px;
    background-color: lightgrey;
    float: left;
    &.selected {
        font-weight: bold;
    }
}
</style>


