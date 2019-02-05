<template>
    <div class="view-table-row">
        Row
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    
    @Component
    export default class Row extends Vue {
        name: string = 'view-table-row';

        @Prop()
        data: object;
    }
</script>

<style lang="scss" scoped>
    .view-table-row { 
        height: 50px;
        color: #FFF;
        background-color: midnightblue;
    }
</style>






