<template>
    <form-compound-widget v-if="form.compound && !this.form.component" :form="form" />
    <component v-else :is="getComponent()" :form="form" />
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
        if (this.form.component) {
            return this.form.component;
        }

        return 'form-simple';
    }
}
</script>
