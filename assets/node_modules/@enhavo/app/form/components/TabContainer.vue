<template>
    <div :class="{'tab-container': true, 'tab-full-width': tab.fullWidth }">
        <div v-once ref="container"></div>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import Tab from "@enhavo/app/form/Tab";
import FormInitializer from "@enhavo/app/form/FormInitializer";

@Options({})
export default class extends Vue
{
    @Prop()
    tab: Tab;

    @Prop()
    tabKey: number;

    mounted() {
        let $tab = $('[data-tab-container]').find('[data-tab='+this.tabKey+']');
        this.tab.error = $tab.find('[data-form-error]').length > 0;
        let element = <HTMLElement>$tab[0];
        let initializer = new FormInitializer();
        initializer.setElement(element);
        initializer.append(this.$refs.container);
    }
}
</script>