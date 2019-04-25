<template>
    <div class="app-view">
        <view-view v-bind:data="view"></view-view>
        <flash-messages v-bind:messages="messages"></flash-messages>
        <action-bar v-bind:primary="actions"></action-bar>
        <div v-bind:class="{'preview-view': true, 'tablet': iframeClass === 'tablet', 'mobile': iframeClass === 'mobile', 'desktop': iframeClass === 'desktop'}">
            <iframe class="iframe" name="preview" v-once></iframe>
        </div>
        <form v-bind:action="url" method="post" target="preview">
            <template v-for="input in inputs">
                <input type="hidden" v-bind:name="input.name" v-bind:value="input.value" />
            </template>
        </form>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import '@enhavo/app/assets/styles/view.scss';
import ActionBar from "@enhavo/app/Action/Components/ActionBar.vue";
import FlashMessages from "@enhavo/app/FlashMessage/Components/FlashMessages.vue";
import ViewData from "@enhavo/app/View/ViewData";
import ViewComponent from "@enhavo/app/View/Components/ViewComponent";
import FormInput from "@enhavo/app/Preview/FormInput";
import * as $ from "jquery";

@Component({
    components: {FlashMessages, ActionBar, 'view-view': ViewComponent}
})
export default class ApplicationComponent extends Vue
{
    @Prop()
    view: ViewData;

    @Prop()
    actions: Array<object>;

    @Prop()
    messages: Array<object>;

    @Prop()
    inputs: FormInput[];

    @Prop()
    url: string;

    iframeClass: string = 'desktop';

    mounted() {
        $(document).on('tablet', ()  => {
            this.iframeClass = 'tablet';
            this.$forceUpdate();
        });

        $(document).on('desktop', ()  => {
            console.log('hello');
            this.iframeClass = 'desktop';
            this.$forceUpdate();
        });

        $(document).on('mobile', ()  => {
            this.iframeClass = 'mobile';
            this.$forceUpdate();
        });
    }
}
</script>

