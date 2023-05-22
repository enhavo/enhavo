<template>
    <form-widget :form="form.get('conditional')" />
    <form-widget :form="form.get('key')" />
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form;

    updated()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        FormUtil.updateAttributes(this.form.element, this.form.attr);
    }

    mounted()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        FormUtil.updateAttributes(this.form.element, this.form.attr);
    }
}
</script>
