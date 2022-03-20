<template>
    <component v-if="shouldRender" :is="getComponent()" :form="form" />
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Options({})
export default class FormWidgetComponent extends Vue
{
    @Prop()
    form: FormData

    render: boolean;

    getComponent()
    {
        if (!this.form.compound || this.form.component !== null) {
            return this.form.component;
        }
        return 'form-rows';
    }

    shouldRender()
    {
        if (!this.form.rendered) {
            this.form.rendered = true;
            this.render = true;
        }

        return this.render;
    }
}
</script>
