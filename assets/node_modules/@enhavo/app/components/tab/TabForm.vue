<template>
    <div>
        <div v-for="row in tab.arrangement" class="form-row">
            <template v-for="column in row">
                <div v-if="form.has(column.key)" :class="getSizeClass(column, row)">
                    <form-row :form="form.get(column.key)"></form-row>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {FormTab} from "../../tab/model/FormTab";
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    tab: FormTab,
    form: Form,
}>()

const tab = props.tab;
const form = props.form;

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
    return 'md-col-' + size;
}
</script>
