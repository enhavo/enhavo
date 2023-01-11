<template>
    <form :name="getName()" :method="getMethod()" :action="getAction()" ref="element">
        <slot name="form-before"></slot>
        <input v-if="getDifferentMethod()" type="hidden" name="_method" :value="getDifferentMethod()" />
        <slot>
            <form-widget :form="form"></form-widget>
        </slot>
        <slot name="form-after"></slot>
    </form>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form";

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form

    getMethod()
    {
        if (typeof this.form.method === 'string') {
            let method = this.form.method.toLowerCase();
            if (['get', 'post'].indexOf(method) >= 0) {
                return method;
            }
        }
        return null;
    }

    getDifferentMethod()
    {
        if (typeof this.form.method === 'string') {
            let method = this.form.method.toLowerCase();
            if (['get', 'post'].indexOf(method) == -1) {
                return method;
            }
        }
        return null;
    }

    getAction()
    {
        if (typeof this.form.action === 'string') {
            return this.form.action;
        }
        return null;
    }

    getName()
    {
        if (typeof this.form.name === 'string') {
            return this.form.name;
        }
        return null;
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
