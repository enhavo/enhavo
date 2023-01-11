<template>
    <input
        type="checkbox"
        ref="element"
        :value="form.value"
        :checked="form.checked"
        :id="form.id"
        :name="form.fullName"
        :disabled="form.disabled"
        :required="form.required"
        @change="form.checked = form.element.checked; form.dispatchChange()"
    />
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Util} from "@enhavo/vue-form/form/Util";
import {CheckboxForm} from "@enhavo/vue-form/model/CheckboxForm";

@Options({})
export default class extends Vue
{
    @Prop()
    form: CheckboxForm

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
