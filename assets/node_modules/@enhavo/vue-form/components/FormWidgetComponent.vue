<template>
    <div>
        <form-row v-if="form.compound" v-for="child in form.children" :form="child" :key="child.name"></form-row>
        <component v-if="!form.compound" :is="form.component" :form="form" />
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";

@Component({})
export default class FormWidgetComponent extends Vue
{
    name = 'form-widget';
    @Prop()
    form: Form
}
</script>
