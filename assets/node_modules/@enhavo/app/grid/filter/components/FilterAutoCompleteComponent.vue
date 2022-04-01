<template>
    <div class="view-table-filter-search">
        <span class="label">{{ data.label }}</span>
        <v-select :options="data.choices" @search="fetchOptions" :placeholder="data.label" v-model="data.selected" @update:modelValue="change"></v-select>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import AutoCompleteEntityFilter from "@enhavo/app/grid/filter/model/AutoCompleteEntityFilter";
import axios from 'axios';
import * as URI from 'urijs';

@Options({})
export default class FilterAutoCompleteComponent extends Vue
{
    name: string = 'filter-autocomplete';

    @Prop()
    data: AutoCompleteEntityFilter;

    change(value: any) {
        if(value == null) {
            this.data.value = null;
            return;
        }
        this.data.value = value.code;
    }

    fetchOptions(search: string, loading: (value: boolean) => void)
    {
        if(search.length < this.data.minimumInputLength) {
            return;
        }
        loading(true);

        let uri = new URI(this.data.url);
        uri.addQuery('q', search);
        uri.addQuery('page', 1);
        let uriString = uri + '';

        axios
            .get(uriString)
            .then((data) => {
                this.data.choices = [];
                for(let result of data.data.results) {
                    this.data.choices.push({
                        label: result.text,
                        code: result.id
                    })
                }
                loading(false);
            });
    }
}
</script>






