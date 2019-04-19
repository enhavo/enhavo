<template>
    <div class="view-table-pagination">
        <div class="pagination-select">
            <div class="label">Ergebnisse pro Seite:</div>
            <v-select @input="changePagination" :value="pagination" :options="options" :clearable="false"></v-select>
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
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import IndexApplication from "@enhavo/app/Index/IndexApplication";
    const application = <IndexApplication>ApplicationBag.getApplication();

    @Component
    export default class Pagination extends Vue {
        name: string = 'table-pagination';
    
        @Prop()
        page: number;

        @Prop()
        paginationSteps: Array<number>;

        @Prop()
        count: number;

        @Prop()
        pagination: number;

        itemsAround: number = 2;

        changePagination(value) {
            application.getGrid().changePagination(value.code);
        }

        get options() {
            let steps = [];
            for(let step of this.paginationSteps) {
                steps.push({
                    label: step,
                    code: step
                })
            }
            return steps;
        }

        get currentPage(): number {
            return this.page;
        }

        get lastPage(): number {
            if(!this.count || !this.pagination) {
                return 1;
            }
            return Math.ceil(this.count/this.pagination);
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

        get pages(): Array<number>
        {
            const firstPage: number = 1;
            let items: Array<number> = [];

            if(this.segmentLength > this.lastPage) {
                let loop: number = firstPage;
                while (this.lastPage > items.length) {
                    items.push(loop);
                    loop++;
                }
            } else if(this.isFirstSegment) {
                let loop: number = firstPage;
                while (this.segmentLength > items.length) {
                    items.push(loop);
                    loop++;
                }
            } else if(this.isLastSegment) {
                
                let loop: number = this.lastPage;
                while (this.segmentLength > items.length) {
                    items.unshift(loop);
                    loop--;
                }
            } else {
                let loop: number = this.currentPage - this.itemsAround;
                while (this.segmentLength > items.length) {
                    items.push(loop);
                    loop++
                }
            }
            return items;
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



        clickPage(page: number): void {
            application.getGrid().changePage(page);
        }
    }
</script>
