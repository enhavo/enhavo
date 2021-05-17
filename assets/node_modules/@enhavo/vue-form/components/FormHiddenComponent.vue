<template>
    <input type="hidden" :value="form.value">
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Component({})
export default class FormHiddenComponent extends Vue
{
    @Prop()
    form: FormData
}
</script>
