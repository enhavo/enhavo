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

            <div v-on:click="clickPrev" v-bind:class="['pagination-nav-item', 'button', 'button--prev', {'disabled': !hasPrevPage}]">
                <i class="icon icon-navigate_before"></i>
            </div>

            <div class="pagination-nav-item number" v-on:click="clickFirst">1</div>
            <div class="pagination-nav-item spacer">...</div>

            <div class="pagination-nav-item number active">{{ currentPage }}</div>

            <div class="pagination-nav-item spacer">...</div>
            <div class="pagination-nav-item number" v-on:click="clickLast">{{ lastPage }}</div>

            <div v-on:click="clickNext" v-bind:class="['pagination-nav-item', 'button', 'button--next', {'disabled': !hasNextPage}]">
                <i class="icon icon-navigate_next"></i>
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

        itemsAround: number = 2;

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

        get hasPrevPage(): boolean {
            if( this.currentPage == 1 ) {
                return false;
            }
            return true;
        }

        get hasNextPage(): boolean {
            if( this.lastPage == this.currentPage ) {
                return false;
            }
            return true;
        }

        get itemsBefore(): Array<number> {

            let items: Array<number> = [];

            let loop: number = this.itemsAround;
            while (
                this.currentPage > 1 && 
                this.currentPage - this.itemsAround > 1 && 
                loop > 0 && 
                loop < this.currentPage
            ) {
                items.push(this.currentPage - loop);
                loop--;
                console.log("Loop: ", loop, this.currentPage);
            }

            console.log("itemsBefore: ", items);

            return items;
        }

        clickPage(page: number): void {
            console.log("Click page: ", page);
            this.page['current'] = page;
        }

        clickFirst(): void {
            this.clickPage(1);
        }

        clickLast(): void {
            this.clickPage(this.page['last']);
        }

        clickPrev(): void {
            let page = this.currentPage;
            if(page > 1) {
                this.clickPage(page - 1);
            }
        }

        clickNext(): void {
            let page = this.currentPage;
            if(page < this.lastPage {
                this.clickPage(page + 1);
            }
        }

        /*

        wenn seite kleiner als (MIN + items around)
        ==> zeige current + 2x items around

        wenn seite größer als (MAX - items around)
        ==> zeige letzte seiten komplett

        wenn seite größer items around
        ==> zeige itemsAround + current + itemsAround

        */

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
            background-color: green;

            &.number,
            &.button {
                cursor: pointer;
            }

            &.active {
                background-color: yellowgreen;
                cursor: default;
            }

            &.disabled {
                background-color: gray;
                color: black;
            }
        }
    }
}
</style>






