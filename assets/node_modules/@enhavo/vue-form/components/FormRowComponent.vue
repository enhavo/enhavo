<template>
    <div>
        <form-label :form="form"></form-label>
        <form-errors :form="form"></form-errors>
        <form-widget :form="form"></form-widget>
        <form-help :form="form"></form-help>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Options({})
export default class FormRowComponent extends Vue
{
    @Prop()
    form: FormData
}
</script>
