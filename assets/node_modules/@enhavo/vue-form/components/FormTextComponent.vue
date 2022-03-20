<template>
    <input type="text" :name="form.fullName" v-model="form.value" />
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Options({})
export default class FormTextComponent extends Vue
{
    @Prop()
    form: FormData
}
</script>
