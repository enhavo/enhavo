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
    export default class AppView extends Vue {
        name = 'tab-container';

        @Prop()
        tab: Tab;

        mounted() {
            let initializer = new FormInitializer();
            initializer.setElement(this.tab.element);
            initializer.append(this.$refs.container);
        }
    }
</script>