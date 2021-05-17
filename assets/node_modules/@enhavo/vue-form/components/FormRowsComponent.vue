<template>
    <div>
        <component :is="child.rowComponent" v-for="child in form.children" :form="child" :key="child.name"></component>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Component({})
export default class FormRowsComponent extends Vue
{
    @Prop()
    form: FormData
}
</script>
