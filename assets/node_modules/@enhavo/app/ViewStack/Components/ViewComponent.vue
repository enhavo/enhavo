<template>
    <div class="view-component" :style="{order: position, maxWidth: data.width}" :class="{minimized: data.minimize}">
        <div class="toolbar">
            <strong v-if="!data.minimize">{{ data.label }}</strong>
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
        <div class="view-resizer" @click="data.minimize ? maximize() : null">
            <div class="view-label" v-if="data.minimize">{{ data.label }}</div>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import CloseEvent from  '@enhavo/app/ViewStack/Event/CloseEvent';
    import SaveStateEvent from  '@enhavo/app/ViewStack/Event/SaveStateEvent';
    import ArrangeEvent from  '@enhavo/app/ViewStack/Event/ArrangeEvent';
    import OverlayContainer from "@enhavo/app/Main/Components/OverlayContainer.vue";
    import LoadingScreen from "@enhavo/app/Main/Components/LoadingScreen.vue";
    import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import MainApplication from "@enhavo/app/Main/MainApplication";
    let application = <MainApplication>ApplicationBag.getApplication();

    Vue.component('overlay-container', OverlayContainer);
    Vue.component('loading-screen', LoadingScreen);

    @Component({
        components: application.getViewRegistry().getComponents()
    })
    export default class ViewComponent extends Vue {
        name: 'view-component';

        @Prop()
        data: ViewInterface;

        created() {
            application.getEventDispatcher().dispatch(new ArrangeEvent());
        }

        destroyed() {
            application.getEventDispatcher().dispatch(new ArrangeEvent());
        }

        close() {
            application.getEventDispatcher().dispatch(new CloseEvent(this.data.id)).then(() => {
                // delay so the view stack has time to remove the view
                window.setTimeout(() => {
                    application.getEventDispatcher().dispatch(new SaveStateEvent());
                }, 10)
            });
        }

        minimize() {
            this.data.minimize = true;
            this.data.customMinimized = true;
            application.getEventDispatcher().dispatch(new ArrangeEvent());
        }

        maximize() {
            this.data.minimize = false;
            this.data.customMinimized = true;
            application.getEventDispatcher().dispatch(new ArrangeEvent());
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