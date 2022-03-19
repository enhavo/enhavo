<template>
    <div>
        <div v-for="error in form.errors" :key="error.message">{{ error.message }}</div>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Options({})
export default class FormErrorsComponent extends Vue
{
    @Prop()
    form: FormData
}
</script>
