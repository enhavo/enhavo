<template>
    <div :class="{'tab-container': true, 'tab-full-width': tab.fullWidth }">
        <div v-once ref="container"></div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Tab from "@enhavo/app/Form/Tab";
    import FormInitializer from "@enhavo/app/Form/FormInitializer";

    @Component()
    export default class AppView extends Vue
    {
        @Prop()
        tab: Tab;

        mounted() {
            let $tab = $('[data-tab-container]').find('[data-tab='+this.tab.key+']');
            this.tab.error = $tab.find('[data-form-error]').length > 0;
            let element = <HTMLElement>$tab[0];
            let initializer = new FormInitializer();
            initializer.setElement(element);
            initializer.append(this.$refs.container);
        }
    }
</script>