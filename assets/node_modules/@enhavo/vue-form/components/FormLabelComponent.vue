<template>
    <component
        v-if="form.label !== false"
        :for="getFor()"
        :is="getComponent()"
        ref="element">
        {{ getLabel() }}
    </component>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form"
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form;

    @Prop()
    element: string|null;

    getComponent(): string
    {
        if (typeof this.element === 'string') {
            return this.element;
        }
        return 'label';
    }

    getLabel(): string
    {
        if (this.form.label) {
            return this.form.label;
        }
        if (this.form.labelFormat) {
            return this.format()
        }
        return FormUtil.humanize(this.form.name);
    }

    getFor(): string|boolean
    {
        if (!this.form.compound) {
            return this.form.id
        }
        return false;
    }

    format(): string
    {
        let label = this.form.labelFormat.replace('%id%', this.form.id);
        label = label.replace('%name%', this.form.name);
        return label;
    }

    updated()
    {
        FormUtil.updateAttributes(<HTMLElement>this.$refs.element, this.form.labelAttr);
    }

    mounted()
    {
        FormUtil.updateAttributes(<HTMLElement>this.$refs.element, this.form.labelAttr);
    }
}
</script>
