<template>
    <label v-if="form.label !== false">{{ form.label }}</label>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";

@Component({})
export default class FormLabelComponent extends Vue
{
    @Prop()
    form: Form
}
</script>
