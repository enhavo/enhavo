<template>
    <div :id="form.id" ref="element">
        <template v-for="child of form.children">
            <form-widget :form="child"></form-widget>
            <form-label :form="child"></form-label>
        </template>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {ChoiceForm} from "@enhavo/vue-form/model/ChoiceForm";
import {Util} from "@enhavo/vue-form/form/Util";

@Options({})
export default class extends Vue
{
    @Prop()
    form: ChoiceForm

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
