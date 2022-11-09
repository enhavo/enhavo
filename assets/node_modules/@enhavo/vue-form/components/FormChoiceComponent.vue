<template>
    <component :is="getComponent()" :form="form"></component>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {ChoiceForm} from "@enhavo/vue-form/model/ChoiceForm";

@Options({})
export default class extends Vue
{
    @Prop()
    form: ChoiceForm

    getComponent()
    {
        if (this.form.expanded) {
            return 'form-choice-expanded';
        } else {
            return 'form-choice-collapsed'
        }
    }
}
</script>
