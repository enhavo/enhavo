<template>
    <div v-if="form.errors">

    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";

@Component({})
export default class FormErrorsComponent extends Vue
{
    @Prop()
    form: Form
}
</script>
