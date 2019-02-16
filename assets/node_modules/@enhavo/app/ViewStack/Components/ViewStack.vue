<template>
    <div class="view-stack" id="view-stack" ref="container">
        <div class="view-container" v-for="view in data.views">
            <view-component v-bind:data="view"></view-component>
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
.view-stack {
    width: calc(100% - 200px); height: calc(100% - 50px); margin-left: 200px; background-color: green;
    .view-container { float: left; height: 100% }
}
</style>






