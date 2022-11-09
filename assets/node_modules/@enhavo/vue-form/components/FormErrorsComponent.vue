<template>
    <div v-if="form.error">
        <div v-for="error in form.errors" :key="error.message">{{ error.message }}</div>
    </div>
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
