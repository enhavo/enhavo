<template>
    <input type="checkbox" ref="element" :value="form.value" :checked="form.checked" :id="form.id" :name="form.fullName" :disabled="form.disabled" :required="form.required" />
</template>

<script lang="ts">
import {Vue, Component, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"
import {Util} from "@enhavo/vue-form/form/Util";

@Component({})
export default class FormRadioComponent extends Vue
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
