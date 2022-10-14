<template>
    <div>
        <component :is="child.rowComponent" v-for="child in form.children" :form="child" :key="child.name"></component>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Options({})
export default class extends Vue
{
    @Prop()
    form: FormData
}
</script>
