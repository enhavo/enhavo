<template>
    <div class="form-list-item" ref="element">
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

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";
import {MediaItemForm} from "@enhavo/media/form/model/MediaItemForm";
import {MediaUtil} from "@enhavo/media/form/MediaUtil";

@Options({})
export default class extends Vue
{
    @Prop()
    form: MediaItemForm;

    @Prop()
    sortable: boolean

    @Prop()
    deletable: boolean

    @Prop()
    blockName: string

    updated()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        FormUtil.updateAttributes(this.form.element, this.form.attr);
    }

    mounted()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        FormUtil.updateAttributes(this.form.element, this.form.attr);
    }

    isImage(file: File): boolean
    {
        return MediaUtil.isImage(this.form.file);
    }

    getIcon(file: File): string
    {
        return MediaUtil.getIcon(this.form.file);
    }
}
</script>
