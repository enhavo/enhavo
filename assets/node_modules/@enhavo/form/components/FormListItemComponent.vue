<template>
    <li class="listElement">
        <ul class="buttons-container" v-if="sortable || deletable">
            <li v-if="sortable" class="button button-down" @click="$emit('down', form)">
                <i class="icon icon-arrow_downward">down</i>
            </li>
            <li v-if="sortable" class="button button-up" @click="$emit('up', form)">
                <i class="icon icon-arrow_upward">up</i>
            </li>
            <li  v-if="deletable" class="button button-delete" @click="$emit('delete', form)">
                <i class="icon icon-close">x</i>
            </li>
        </ul>
        <div class="block-name">{{ blockName }}</div>
        <form-widget :form="form" />
    </li>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";

@Options({})
export default class extends Vue
{
    @Prop()
    form: Form;

    @Prop()
    sortable: boolean

    @Prop()
    deletable: boolean

    @Prop()
    blockName: string
}
</script>
