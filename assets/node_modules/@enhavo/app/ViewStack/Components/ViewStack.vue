<template>
    <div class="view-stack" ref="container">
        <div class="view-container">
            <template v-for="view in data.views">
                <view-component v-bind:data="view" v-if="!view.removed"></view-component>
            </template>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import ViewComponent from './ViewComponent.vue'
import ViewStackData from '../ViewStackData'
import dispatcher from '../dispatcher'
import ArrangeEvent from '../Event/ArrangeEvent'

Vue.component('view-component', ViewComponent);

@Component
export default class ViewStack extends Vue {
    name: 'view-stack';
    @Prop()
    data: ViewStackData;

    mounted() {
        this.data.width = this.getWidth();
        dispatcher.dispatch(new ArrangeEvent());
        $(window).resize(() => {
            this.data.width = this.getWidth();
            dispatcher.dispatch(new ArrangeEvent());
        });
    }

    private getWidth(): number
    {
        return this.$refs.container.clientWidth;
    }
}
</script>

<style lang="scss" scoped>
.view-stack {width:100%;flex:1 0 0;
    .view-container {height:100%;display:flex;}
}
</style>






