<template>
    <div v-if="form.expanded" :id="form.id" :ref="(el) => form.setElement(el as HTMLElement)" v-show="form.isVisible()">
        <template v-for="choice in form.tree">
            <form-choice-tree-row :form="form" :children="choice.children" :name="choice.name" />
        </template>
    </div>
    <div v-if="!form.expanded" :id="form.id" :ref="(el) => form.setElement(el as HTMLElement)" v-show="form.isVisible()">
        <form-select2-choice-collapsed :form="form" />
    </div>
</template>

<script setup lang="ts">
import {ChoiceTreeForm} from "@enhavo/form/form/model/ChoiceTreeForm";
import FormSelect2ChoiceCollapsed from "@enhavo/form/components/FormSelect2ChoiceCollapsed.vue";

const props = defineProps<{
    form: ChoiceTreeForm
}>()
</script>
