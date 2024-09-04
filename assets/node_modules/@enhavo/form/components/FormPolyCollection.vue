<template>
    <div class="form-list" :ref="(el) => form.setElement(el as HTMLElement)">
        <slot name="list">
            <ul>
                <draggable
                    v-model="form.children"
                    item-key="name"
                    @change="event => { form.changeOrder(event) }"
                    @start="event => { form.dragStart(event) }"
                    @end="event => { form.dragEnd(event) }"
                    :group="form.draggableGroup"
                    :handle="form.draggableHandle"
                    :disabled="!form.sortable"
                    tag="li"
                >
                    <template #item="{ element }">
                        <component
                            :is="form.itemComponent"
                            :form="element"
                            :deletable="form.allowDelete"
                            :sortable="form.sortable"
                            :block-name="form.blockName"
                            @delete="event => { form.deleteItem(event) }"
                            @up="event => { form.moveItemUp(event) }"
                            @down="event => { form.moveItemDown(event) }"
                        >
                            <template v-slot>
                                <slot name="item"></slot>
                            </template>
                            <template v-slot:buttons><slot name="item-buttons"></slot></template>
                            <template v-slot:down-button><slot name="item-down-button"></slot></template>
                            <template v-slot:up-button><slot name="item-up-button"></slot></template>
                            <template v-slot:delete-button><slot name="item-delete-button"></slot></template>
                            <template v-slot:drag-button><slot name="item-drag-button"></slot></template>
                        </component>
                    </template>
                </draggable>
            </ul>
        </slot>

        <slot name="button-row">
            <div class="form-list-button-row" v-if="form.allowAdd">
                <slot name="buttons">
                    <div class="form-list-button" @click.prevent="form.toggleMenu()">
                        <slot name="add-button">
                            <i class="icon icon-add_box">+</i>
                        </slot>
                    </div>
                </slot>
                <div v-if="form.isOpen">
                    <div v-for="entryLabel of form.entryLabels" @click.prevent="form.addItem(entryLabel.key); form.toggleMenu()">{{ entryLabel.label }}</div>
                </div>
            </div>
        </slot>
    </div>
</template>

<script setup lang="ts">
import {PolyCollectionForm} from "@enhavo/form/form/model/PolyCollectionForm";
import * as draggableComponent from 'vuedraggable'

const draggable = draggableComponent;
const props = defineProps<{
    form: PolyCollectionForm
}>()
</script>
