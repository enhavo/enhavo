<template>
    <form-rows :form="form"></form-rows>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Options({})
export default class FormRestComponent extends Vue
{
    @Prop()
    form: FormData
}
</script>
