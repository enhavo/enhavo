<template>
    <input type="text" :name="form.fullName" v-model="form.value" />
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form"

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form
}
</script>
