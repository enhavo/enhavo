<template>
    <div>

    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";

@Component({})
export default class FormStart extends Vue
{
    @Prop()
    form: Form
}
</script>
