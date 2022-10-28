<template>
    <input :type="form.type" :id="form.id" :name="form.fullName" v-model="form.value" ref="element" />
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"
import {Util} from "@enhavo/vue-form/form/Util";

@Options({})
export default class extends Vue
{
    @Prop()
    form: FormData

    updated()
    {
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }

    mounted()
    {
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }
}
</script>
