<template>
    <input type="hidden" :value="form.value">
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";

@Component({})
export default class FormHiddenComponent extends Vue
{
    @Prop()
    form: Form
}
</script>
