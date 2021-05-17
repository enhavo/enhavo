<template>
    <div v-if="false">
        Help
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData"

@Component({})
export default class FormHelpComponent extends Vue
{
    @Prop()
    form: FormData
}
</script>
