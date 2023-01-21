<template>
    <textarea
        :id="form.id"
        :placeholder="form.placeholder"
        :name="form.fullName"
        :required="form.required"
        :disabled="form.disabled"
        :title="form.attr.title"
        v-model="form.value"
        ref="element"
        @change="form.dispatchChange()"
    >{{ form.value }}</textarea>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form"
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form

    updated()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        FormUtil.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }

    mounted()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        FormUtil.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }
}
</script>
