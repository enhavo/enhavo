<template>
    <div class="view-table-filter-search">
        <input v-model="value">
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch} from "vue-property-decorator";

    @Component
    export default class FilterSearch extends Vue {
        name: string = 'view-table-filter-search';

        @Prop()
        id: string;

        @Prop()
        label: string;

        @Prop()
        filter: Object;

        @Watch('value', { immediate: false })
        onValueChanged(newValue: boolean, oldValue: boolean): void {
            this.setFilterValue(newValue);
        }

        value: string = '';
        
        setFilterValue(value: any) {
            if(value === null || value === '') {
                this.value = '';
                delete this.filter[this.id];
            } else {
                this.value = value;
                this.filter = Object.assign(this.filter, {[this.id]: value});
            }
        }
    }
</script>

<style lang="scss" scoped>
    .view-table-filter-search { 
        background-color: burlywood;
    }
</style>






