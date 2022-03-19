<template>
    <div class="tab-head" @click="changeTab" :class="{'selected': tab.active, 'has-error': tab.error}">
        {{ tab.label }}
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop, Inject } from "vue-property-decorator";
import Tab from "@enhavo/app/form/Tab";
import Form from "@enhavo/app/form/Form";

@Options({})
export default class extends Vue
{
    @Prop()
    tab: Tab;

    @Inject()
    form: Form

    changeTab() {
        this.form.changeTab(this.tab.key);
    }
}
</script>


