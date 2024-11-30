<template>
    <div class="tab-container">
        <template v-if="tab.arrangement.length > 0">
            <div v-for="row in tab.arrangement" class="form-row">
                <div class="row-form">
                    <template v-for="column in row">
                        <div v-if="form.has(column.key)" :class="getSizeClass(column, row)">
                            <form-row :form="form.get(column.key)"></form-row>
                        </div>
                    </template>
                </div>
            </div>
        </template>
        <template v-else>
            <div v-for="child in form.children" class="form-row">
                <form-row :form="form.get(child.name)"></form-row>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import {FormTab} from "../../tab/model/FormTab";
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    tab: FormTab,
    form: Form,
}>()

type Column = {
    size: number,
    key: string,
}

function getSizeClass(column: Column, row: Column[]): string
{
    let total = 0;
    for (let oneColumn of row) {
        total += oneColumn.size;
    }

    let size = Math.round(column.size / total * 12);
    return 'col-form-m-' + size;
}
</script>
