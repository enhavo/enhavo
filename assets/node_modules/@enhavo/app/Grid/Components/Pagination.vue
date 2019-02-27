<template>
    <div class="view-table-pagination">

        <div class="pagination-select">
            Ergebnisse pro Seite: 
            <select v-model="pagination">
                <option 
                    v-for="(step, index) in paginationSteps"
                    v-bind:key="index" v-bind:value="step">
                        {{ step }}
                </option>
            </select>
        </div>

        <div class="pagination-nav">

            <div class="pagination-nav-item button button--prev">
                <i class="icon navigate_before"></i>
            </div>
            <div class="pagination-nav-item number">1</div>
            <div class="pagination-nav-item spacer">...</div>

            <div class="pagination-nav-item number">3</div>
            <div class="pagination-nav-item number">4</div>
            <div class="pagination-nav-item number number--active">5</div>
            <div class="pagination-nav-item number">6</div>
            <div class="pagination-nav-item number">7</div>

            <div class="pagination-nav-item spacer">...</div>
            <div class="pagination-nav-item number">{{ lastPage }}</div>

            <div class="pagination-nav-item button button--next">
                <i class="icon navigate_next"></i>
            </div>

        </div>

    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch } from "vue-property-decorator";

    @Component
    export default class Pagination extends Vue {
        name: string = 'view-table-pagination';
    
        @Prop()
        page: Array<object>;

        get paginationSteps(): Array<number> {
            if( this.page && this.page.hasOwnProperty('pagination_steps') ) {
                return this.page['pagination_steps'];
            }
            return null;
        }

        get pagination(): number {
            if( this.page && this.page.hasOwnProperty('pagination') ) {
                return this.page['pagination'];
            }
            return null;
        }

        get currentPage(): number {
            if( this.page && this.page.hasOwnProperty('current') ) {
                return this.page['current'];
            }
            return null;
        }

        get lastPage(): number {
            if( this.page && this.page.hasOwnProperty('last') ) {
                return this.page['last'];
            }
            return null;
        }

        mounted() {
        }
    }
</script>

<style lang="scss" scoped>
.view-table-pagination {
    display: flex;
    justify-content: space-between;
    margin-bottom: 60px;

    .pagination-select {

    }

    .pagination-nav {
        display: flex;

        .pagination-nav-item {
            min-width: 20px;
            margin: 0 15px;
            color: white;
            background-color: firebrick;
        }
    }
}
</style>






