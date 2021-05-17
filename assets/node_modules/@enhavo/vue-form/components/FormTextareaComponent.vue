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
    >{{ form.value }}</textarea>
</template>

<script lang="ts">
import {Vue, Component, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"
import {Util} from "@enhavo/vue-form/form/Util";

@Component({})
export default class FormTextareaComponent extends Vue
{
    @Prop()
    form: FormData

    updated()
    {
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }

    mounted()
    {
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }
}
</script>
