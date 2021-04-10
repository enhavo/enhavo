<template>
    <div class="form-row">
        <form-label :form="form"></form-label>
        <form-errors :form="form"></form-errors>
        <form-widget :form="form"></form-widget>
        <form-help :form="form"></form-help>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";

@Component({})
export default class FormRowComponent extends Vue
{
    @Prop()
    form: Form
}
</script>
