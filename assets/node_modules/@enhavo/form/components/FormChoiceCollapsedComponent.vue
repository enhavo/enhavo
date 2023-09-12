<template>
    <select :multiple="getMultiple()" v-model="form.value" :name="form.fullName" ref="element" @change="form.dispatchChange()">
        <option v-if="form.placeholder" value="" >{{ form.placeholder }}</option>
        <component v-if="size(form.preferredChoices) > 0" :is="getChoiceComponent(choice)" v-for="choice of form.preferredChoices" :choice="choice" :key="choice.label + '_preferred'" :preferredChoices="true" />
        <option v-if="size(form.preferredChoices) > 0" disabled="disabled">{{ form.separator }}</option>-->
        <component :is="getChoiceComponent(choice)" v-for="choice of form.choices" :choice="choice" :key="choice.label" :preferredChoices="false" />
    </select>
</template>

<script lang="ts">
import {Vue, Options, Prop, Inject} from "vue-property-decorator";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";
import * as _ from "lodash";
import {ChoiceForm} from "@enhavo/vue-form/model/ChoiceForm";
import * as $ from "jquery";
import "select2";
import 'select2/select2.css'

@Options({})
export default class extends Vue
{
    @Prop()
    form: ChoiceForm;

    size(object: object)
    {
        return _.size((object));
    }

    isRequired()
    {
        return !(
            this.form.required &&
            this.form.placeholder === null &&
            !this.form.placeholderInChoices &&
            !this.form.multiple && this.isSizeOneOrLess()
        );
    }

    getMultiple()
    {
        if (this.form.multiple) {
            return 'multiple';
        }
        return false;
    }

    isSizeOneOrLess(): boolean
    {
        let size: number = null;
        if (this.form.attr.hasOwnProperty('size')) {
            size = this.form.attr['size'];
        }

        return size === null || size <= 1;
    }

    getChoiceComponent(choice: any)
    {
        if (choice.choices.length > 0) {
            return 'form-choice-optgroup';
        }
        return 'form-choice-option';
    }

    updated()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        FormUtil.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }

    mounted()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        FormUtil.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);

        $(this.$refs.element).select2();
    }

    destroyed()
    {

    }
}
</script>
