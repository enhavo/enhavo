<template>
    <div class="form-list-item" :ref="(el) => form.setElement(el)">
        <img v-if="isImage(form.file)" :src="form.path('enhavoPreviewThumb')" />
        <div v-else><span :class="'icon_'+getIcon(form.file)"></span></div>

        <ul class="form-list-item-buttons-row" v-if="sortable || deletable">
            <li v-if="sortable" class="form-list-item-buttons-button drag-button"><i class="icon icon-drag">=</i></li>
            <li  v-if="deletable" class="form-list-item-buttons-button" @click="$emit('delete', form)"><i class="icon icon-close">x</i></li>
        </ul>
        <div class="form-list-item-label" v-if="blockName">{{ blockName }}</div>
        <form-widget :form="form" />
    </div>
</template>

<script setup lang="ts">
import {MediaItemForm} from "@enhavo/media/form/model/MediaItemForm";
import {MediaUtil} from "@enhavo/media/form/MediaUtil";

const props = defineProps<{
    form: MediaItemForm,
    sortable: boolean,
    deletable: boolean,
    blockName: string,
}>()

function isImage(file: File): boolean
{
    return MediaUtil.isImage(props.form.file);
}

function getIcon(file: File): string
{
    return MediaUtil.getIcon(props.form.file);
}

</script>
