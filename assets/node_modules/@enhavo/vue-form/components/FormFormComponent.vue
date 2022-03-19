<template>
    <form :method="getMethod()" :action="getAction()" :name="getName()" ref="element">
        <slot name="form-before"></slot>
        <slot>
            <component :is="child.rowComponent" v-if="form.compound" v-for="child in form.children" :form="child" :key="child.name"></component>
            <component :is="form.rowComponent" v-if="!form.compound" :form="form"></component>
        </slot>
        <slot name="form-after"></slot>
    </form>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";

@Options({})
export default class FormForm extends Vue
{
    @Prop()
    form: Form

    getMethod()
    {
        if (typeof this.form.method === 'string') {
            return this.form.method.toLowerCase()
        }
        return false;
    }

    getAction()
    {
        if (typeof this.form.action === 'string') {
            return this.form.action;
        }
        return false;
    }

    getName()
    {
        if (typeof this.form.name === 'string') {
            return this.form.name;
        }
        return false;
    }

    updated()
    {
        this.form.element = <HTMLElement>this.$refs.element;
    }

    mounted()
    {
        this.form.element = <HTMLElement>this.$refs.element;
    }
}
</script>
