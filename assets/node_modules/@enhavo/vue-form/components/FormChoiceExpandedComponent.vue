<template>
    <div :id="form.id" ref="element">
        <template v-for="child in form.children">
            <form-widget :form="child"></form-widget>
            <form-label :form="child"></form-label>
        </template>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {ChoiceFormData} from "@enhavo/vue-form/data/ChoiceFormData";
import {Util} from "@enhavo/vue-form/form/Util";

@Options({})
export default class FormChoiceExpandedComponent extends Vue
{
    @Prop()
    form: ChoiceFormData

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
