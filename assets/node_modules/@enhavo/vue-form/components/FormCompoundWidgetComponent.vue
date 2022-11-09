<template>
    <div :id="form.id">
        <form-rows :form="form" />
    </div>
</template>

<script lang="ts">
import {Vue, Options, Inject, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/model/Form";

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form
}
</script>
