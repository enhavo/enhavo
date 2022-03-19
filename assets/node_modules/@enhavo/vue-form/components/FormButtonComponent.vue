<template>
    <button>{{ form.label }}</button>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData";

@Options({})
export default class FormButtonComponent extends Vue
{
    @Prop()
    form: FormData
}
</script>
