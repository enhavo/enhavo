<template>
    <input type="radio" ref="element" :value="form.value" :checked="form.checked" :id="form.id" :name="form.fullName" :disabled="form.disabled" :required="form.required" />
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form"
import {Util} from "@enhavo/vue-form/form/Util";

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form

    updated()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }

    mounted()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }
}
</script>
