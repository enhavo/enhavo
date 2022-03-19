<template>
    <div v-if="shouldRender()">
        <form-widget :form="form"></form-widget>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Options({})
export default class FormRowComponent extends Vue
{
    @Prop()
    form: FormData

    render: boolean;

    shouldRender()
    {
        if (!this.form.rendered) {
            this.form.rendered = true;
            this.render = true;
        }

        return this.render;
    }
}
</script>
