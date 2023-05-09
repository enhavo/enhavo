<template>
    <component v-if="form.rowComponent !== 'form-row'" :is="form.rowComponent" :style="getStyle()" />
    <div v-else :style="getStyle()">
        <form-label :form="form"></form-label>
        <form-errors :form="form"></form-errors>
        <form-widget :form="form"></form-widget>
        <form-help :form="form"></form-help>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form"

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form

    getStyle()
    {
        if (this.form.visible === false) {
            return 'display: none';
        }
    }
}
</script>
