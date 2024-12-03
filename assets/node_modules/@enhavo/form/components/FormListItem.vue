<template>
    <div class="form-list-item" :ref="(el) => form.setElement(el as HTMLElement)" v-show="form.isVisible()">
        <div class="buttons-container" v-if="sortable || deletable">
            <slot name="buttons">
                <slot name="drag-button">
                    <div v-if="sortable" class="button drag-button"><i class="icon icon-drag_handle"></i></div>
                </slot>
                <slot name="delete-button">
                    <div v-if="deletable" class="button delete-button" @click="$emit('delete', form)"><i class="icon icon-close"></i></div>
                </slot>
                <slot name="up-button">
                    <div v-if="sortable" class="button" @click="$emit('up', form)"><i class="icon icon-arrow_upward"></i></div>
                </slot>
                <slot name="down-button">
                    <div v-if="sortable" class="button" @click="$emit('down', form)"><i class="icon icon-arrow_downward"></i></div>
                </slot>
            </slot>
        </div>
        <slot>
            <form-widget :form="form" />
        </slot>
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form,
    sortable: boolean,
    deletable: boolean,
    blockName: string,
}>()
</script>
