<template>
    <div>
        <select v-if="!form.expanded & !form.multiple">
            <option>1</option>
        </select>
        <input type="checkbox" />
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";

@Component({})
export default class FormChoiceComponent extends Vue
{
    @Prop()
    form: Form
}
</script>
