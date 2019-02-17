<template>
    <div class="view-component" :style="{width: width, left: position}">
        <div class="toolbar">
            <i @click="close()">X</i>
            <i @click="minimize()" v-if="!data.minimize"><</i>
            <i @click="maximize()" v-if="data.minimize">></i>
            <strong>{{ data.id }}</strong>
        </div>
        <div class="view-component-inner" v-show="!data.minimize">
            <overlay-container v-if="!loaded">
                <slot>
                    <loading-screen></loading-screen>
                </slot>
            </overlay-container>
            <component v-bind:is="data.component" v-bind:data="data"></component>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import registry from '../registry';
    import dispatcher from '../dispatcher';
    import CloseEvent from  '../Event/CloseEvent';
    import ArrangeEvent from  '../Event/ArrangeEvent';
    import OverlayContainer from "../../Main/Components/OverlayContainer.vue";
    import LoadingScreen from "../../Main/Components/LoadingScreen.vue";
    import ViewInterface from "../ViewInterface";

    Vue.component('overlay-container', OverlayContainer);
    Vue.component('loading-screen', LoadingScreen);

    @Component({
        components: registry.getComponents()
    })
    export default class ViewComponent extends Vue {
        name: 'view-component';

        @Prop({ type: Object })
        data: ViewInterface;

        created() {
            dispatcher.dispatch(new ArrangeEvent());
        }

        destroyed() {
            dispatcher.dispatch(new ArrangeEvent());
        }

        close() {
            dispatcher.dispatch(new CloseEvent(this.data.id));
        }

        minimize() {
            this.data.minimize = true;
            dispatcher.dispatch(new ArrangeEvent());
        }

        maximize() {
            this.data.minimize = false;
            dispatcher.dispatch(new ArrangeEvent());
        }

        get width(): string
        {
            return this.data.width + 'px';
        }

        get position(): string
        {
            return this.data.position + 'px';
        }

        get loaded(): boolean
        {
            return this.data.loaded;
        }
    }

</script>

<style lang="scss" scoped>
    .view-component {
        height: 100%; background-color: yellow; position: absolute; top: 0;
        transition: width 0.1s, transform;
        .toolbar { height: 20px; background-color: blue }
        .view-component-inner { height: calc(100% - 20px); position: relative }
    }
</style>
