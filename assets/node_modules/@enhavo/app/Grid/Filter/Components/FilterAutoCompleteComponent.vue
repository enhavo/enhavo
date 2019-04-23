<template>
    <div class="view-table-filter-search">
        <v-select :options="options" @search="fetchOptions" :placeholder="data.label" :value="data.value" @input="change"></v-select>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import AbstractFilter from "@enhavo/app/Grid/Filter/Model/AbstractFilter";
    import axios from 'axios';
    import * as URI from 'urijs';

    @Component
    export default class FilterAutoCompleteComponent extends Vue
    {
        name: string = 'filter-autocomplete';

        @Prop()
        data: AbstractFilter;

        options: object[] = [];

        change(value) {
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
            uri = uri + '';

            axios
                .get(uri)
                .then((data) => {
                    let options = [];
                    for(result of data.results) {
                        options.push({
                            label: result.text,
                            code: result.id
                        })
                    }
                    loading(false);
                });
        }
    }
</script>






