<template>
    <div class="view-table-filter-search">
        <span class="label">{{ data.label }}</span>
        <v-select :options="data.choices" @search="fetchOptions" :placeholder="data.label" v-model="data.selected" @update:modelValue="change"></v-select>
    </div>
</template>

<script setup lang="ts">
import AutoCompleteEntityFilter from "@enhavo/app/grid/filter/model/AutoCompleteEntityFilter";
import axios from 'axios';
import * as URI from 'urijs';

const props = defineProps<{
    data: AutoCompleteEntityFilter
}>()

const data = props.data;


function change(value: any)
{
    if (value == null) {
        data.value = null;
        return;
    }
    data.value = value.code;
}

function fetchOptions(search: string, loading: (value: boolean) => void)
{
    if (search.length < data.minimumInputLength) {
        return;
    }
    loading(true);

    let uri = new URI(data.url);
    uri.addQuery('q', search);
    uri.addQuery('page', 1);
    let uriString = uri + '';

    axios
        .get(uriString)
        .then((data) => {
            data.choices = [];
            for (let result of data.data.results) {
                data.choices.push({
                    label: result.text,
                    code: result.id
                })
            }
            loading(false);
        });
}
</script>






