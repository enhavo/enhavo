<template>
    <button>{{ form.label }}</button>
</template>

<script lang="ts">
import {Vue, Component, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData";

@Component({})
export default class FormButtonComponent extends Vue
{
    @Prop()
    form: FormData
}
</script>
