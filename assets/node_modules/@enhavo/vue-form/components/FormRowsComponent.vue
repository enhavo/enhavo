<template>
    <component :is="child.rowComponent" v-for="child of form.children" :form="child" :key="child.name" />
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
