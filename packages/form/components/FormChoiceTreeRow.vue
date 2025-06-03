<template>
    <div class="choice-row">
        <form-widget :form="form.get(name)"></form-widget>
        <form-label :form="form.get(name)"></form-label>
        <div class="entity-tree-children" v-if="children.length > 0">
            <template v-for="choice in children">
                <form-choice-tree-row :form="form" :children="choice.children" :name="choice.name" />
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ChoiceTreeForm, ChoiceRow} from "@enhavo/form/form/model/ChoiceTreeForm";

const props = defineProps<{
    form: ChoiceTreeForm
    name: string,
    children: ChoiceRow[]
}>()
</script>
