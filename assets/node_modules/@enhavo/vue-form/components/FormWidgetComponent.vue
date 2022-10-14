<template>
    <component :is="getComponent()" :form="form" />
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Options({})
export default class extends Vue
{
    @Prop()
    form: FormData

    getComponent()
    {
        if (!this.form.compound || this.form.component) {
            return this.form.component;
        }

        return 'form-rows';
    }
}
</script>
