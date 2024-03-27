<template>
    <div :class="{'tab-container': true, 'tab-full-width': tab.fullWidth }">
        <div v-once :ref="(el) => container = el"></div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import FormInitializer from "../../form/FormInitializer";
import Tab from "../../form/Tab";

const props = defineProps<{
    tab: Tab,
    tabKey: number,
}>()

const tab = props.tab;
const tabKey = props.tabKey;
let container: HTMLElement = null;

onMounted(() => {
    let $tab = $('[data-tab-container]').find('[data-tab=' + tabKey + ']');
    tab.error = $tab.find('[data-form-error]').length > 0;
    let element = <HTMLElement>$tab[0];
    let initializer = new FormInitializer();
    initializer.setElement(element);
    initializer.append(container);
});

</script>
