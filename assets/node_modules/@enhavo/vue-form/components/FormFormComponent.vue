<template>
    <form>
        <form-row v-for="child in form.children" :form="child" :key="child.name"></form-row>
    </form>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";

@Component({})
export default class FormForm extends Vue
{
    @Prop()
    form: Form
}
</script>
