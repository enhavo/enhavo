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
import {Vue, Component, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"
import {Util} from "@enhavo/vue-form/form/Util";

@Component({})
export default class FormLabelComponent extends Vue
{
    @Prop()
    form: FormData;

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
        return Util.humanize(this.form.name);
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
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.labelAttr);
    }

    mounted()
    {
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.labelAttr);
    }
}
</script>
