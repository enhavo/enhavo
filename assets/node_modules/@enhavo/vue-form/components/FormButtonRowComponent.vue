<template>
    <div>
        <form-widget :form="form"></form-widget>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form"

@Options({})
export default class FormRowComponent extends Vue
{
    @Prop()
    form: Form
}
</script>
