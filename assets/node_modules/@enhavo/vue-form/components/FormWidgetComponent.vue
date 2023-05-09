<template>
    <form-compound-widget v-if="form.compound && !this.form.component" :form="form" :style="getStyle()" />
    <component v-else :is="getComponent()" :form="form" :style="getStyle()" />
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form"

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form

    getComponent()
    {
        if (this.form.component) {
            return this.form.component;
        }

        return 'form-simple';
    }

    getStyle()
    {
        if (this.form.visible === false) {
            return 'display: none';
        }
    }
}
</script>
