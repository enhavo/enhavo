<template>
    <li class="form-list-item">
        <ul class="form-list-item-buttons-row" v-if="sortable || deletable">
            <slot name="buttons">
                <slot name="down-button">
                    <li v-if="sortable" class="form-list-item-buttons-button" @click="$emit('down', form)"><i class="icon icon-arrow_downward">down</i></li>
                </slot>

                <slot name="up-button">
                    <li v-if="sortable" class="form-list-item-buttons-button" @click="$emit('up', form)"><i class="icon icon-arrow_upward">up</i></li>
                </slot>

                <slot name="delete-button">
                    <li  v-if="deletable" class="form-list-item-buttons-button" @click="$emit('delete', form)"><i class="icon icon-close">x</i></li>
                </slot>
            </slot>
        </ul>
        <slot>
            <div class="form-list-item-label" v-if="blockName">{{ blockName }}</div>
            <form-widget :form="form" />
        </slot>
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
