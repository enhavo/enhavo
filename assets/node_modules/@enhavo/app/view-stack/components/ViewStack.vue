<template>
    <div class="view-stack" ref="container">
        <div class="view-container" v-bind:class="{'has-viewstack-dropdown': hasMoreThanOneView}">
            <template v-for="view in viewStack.views">
                <view-stack-view v-bind:data="view" v-if="!view.removed"></view-stack-view>
            </template>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Inject } from "vue-property-decorator";
import ViewStack from "@enhavo/app/view-stack/ViewStack";

@Options({})
export default class extends Vue
{
    @Inject()
    viewStack: ViewStack

    mounted() {
        this.viewStack.data.width = this.getWidth();
    }

    private getWidth(): number
    {
        return this.$refs.container.clientWidth;
    }

    get hasMoreThanOneView() {
        let count = 0;
        for (let i = 0; i < this.viewStack.views.length;i++) {
            if (this.viewStack.views[i].removed == false) {
                count++;
            }
        }
        return count > 1;
    }
}
</script>

<style lang="scss" scoped>
.view-stack {width:100%;flex:1 0 0;
    .view-container {height:100%;display:flex;}
}
</style>






