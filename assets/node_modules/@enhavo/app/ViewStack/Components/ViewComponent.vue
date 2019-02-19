<template>
    <div class="view-component" :style="{order: position}" :class="{minimized: data.minimize}">
        <div class="toolbar">
            <strong v-if="!data.minimize">{{ data.id }}</strong>
            <div class="actions">
                <div @click="close()" v-if="!data.minimize" class="action"><span class="icon icon-close"></span></div>
                <div @click="minimize()" v-if="!data.minimize" class="action"><span class="icon icon-keyboard_arrow_left"></span></div>
                <div @click="maximize()" v-if="data.minimize" class="action"><span class="icon icon-keyboard_arrow_right"></span></div>
            </div>
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

        get position(): number
        {
            return this.data.position;
        }

        get loaded(): boolean
        {
            return this.data.loaded;
        }
    }

</script>

<style lang="scss" scoped>
    .view-component {height:100%;flex:1 0 0;
        &.minimized {flex:0;}
        .toolbar {height:30px;line-height:30px;position:relative;display:flex;
            .actions {display:flex;margin-left:auto;align-items:center;
                .action {padding:5px;cursor:pointer;}
            }
        }
        .view-component-inner {height:calc(100% - 30px);position:relative;
            iframe {display:block;}
        }
    }
</style>
