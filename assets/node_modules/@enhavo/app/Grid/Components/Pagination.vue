<template>
    <div class="view-table-pagination">

        <div class="pagination-select">
            Ergebnisse pro Seite: 
            <select v-model="pagination">
                <option 
                    v-for="(step, index) in paginationSteps"
                    v-bind:key="index" 
                    v-bind:value="step"
                    >
                        {{ step }}
                </option>
            </select>

            <span>Selected: {{ pagination }}</span>
        </div>

        <div class="pagination-nav">
            <div v-on:click="clickPrev" v-bind:class="['pagination-nav-item', 'button', 'button--prev', {'disabled': !hasPrevPage}]">
                <i class="icon icon-navigate_before"></i>
            </div>

            <template v-if="!isFirstSegment">
                <div class="pagination-nav-item number" v-on:click="clickFirst">1</div>
                <div class="pagination-nav-item spacer">...</div>
            </template>

            <div 
                v-for="page in pages" 
                v-bind:key="page"
                v-bind:class="['pagination-nav-item', 'number', {active: currentPage == page}]" 
                v-on:click="clickPage(page)">
                {{ page }}
            </div>

            <template v-if="!isLastSegment">
                <div class="pagination-nav-item spacer">...</div>
                <div class="pagination-nav-item number" v-on:click="clickLast">{{ lastPage }}</div>
            </template>

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
        name: string = 'table-pagination';
    
        @Prop()
        page: Array<object>;

        paginationValue: number = 0; 
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
        set pagination(value: number) {
            if( this.page && this.page.hasOwnProperty('pagination') ) {
                this.page['pagination'] = value;
            }
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

        get isFirstPage(): boolean {
            return this.currentPage == 1;
        }

        get isLastPage(): boolean {
            return this.currentPage == this.lastPage;
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

        get segmentLength(): number {
            return this.itemsAround * 2 + 1; // 2 items each side plus the current page
        }

        get isFirstSegment(): boolean {
            return this.currentPage <= this.segmentLength;
        }

        get isLastSegment(): boolean {
            return this.currentPage > (this.lastPage - this.segmentLength);
        }

        get pages(): Array<number> {

            const firstPage: number = 1;
            let items: Array<number> = [];

            if(this.isFirstSegment) {

                let loop: number = firstPage;
                while (this.segmentLength > items.length) {
                    items.push(loop);
                    loop++;
                }

                return items;
            } 
            else if(this.isLastSegment) {
                
                let loop: number = this.lastPage;
                while (this.segmentLength > items.length) {
                    items.unshift(loop);
                    loop--;
                }
                
                return items;
            }

            let loop: number = this.currentPage - this.itemsAround;
            while (this.segmentLength > items.length) {
                items.push(loop);
                loop++
            }

            return items;
        }

        clickPage(page: number): void {
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
            if(page < this.lastPage) {
                this.clickPage(page + 1);
            }
        }

        changePagination(data: any): void {
            console.log(data);
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
