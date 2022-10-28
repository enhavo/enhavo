<template>
    <div :id="form.id">
        <form-rows :form="form" />
    </div>
</template>

<script lang="ts">
import {Vue, Options, Inject, Prop} from "vue-property-decorator";
import {FormData} from "@enhavo/vue-form/data/FormData";

@Options({})
export default class extends Vue
{
    @Prop()
    form: FormData
}
</script>
