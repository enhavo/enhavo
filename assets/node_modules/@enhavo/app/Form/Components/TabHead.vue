<template>
    <div class="tab-head" @click="changeTab" :class="{'selected': tab.active, 'has-error': tab.error}">
        {{ tab.label }}
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Tab from "@enhavo/app/Form/Tab";

    @Component()
    export default class TabHead extends Vue
    {
        @Prop()
        tab: Tab;

        changeTab() {
            this.$form.changeTab(this.tab.key);
        }
    }
</script>


