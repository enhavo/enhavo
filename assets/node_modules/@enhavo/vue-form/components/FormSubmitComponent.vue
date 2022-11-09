<template>
    <button type="submit">{{ form.label }}</button>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form";

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form
}
</script>
