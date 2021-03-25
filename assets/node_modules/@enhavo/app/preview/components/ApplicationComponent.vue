<template>
    <div class="app-view">
        <view-view></view-view>
        <action-bar></action-bar>
        <div v-bind:class="{'preview-view': true, 'tablet': iframeClass === 'tablet', 'mobile': iframeClass === 'mobile', 'desktop': iframeClass === 'desktop'}">
            <iframe class="iframe" name="preview" v-once></iframe>
        </div>
        <form v-bind:action="$previewApp.data.url" method="post" target="preview">
            <template v-for="input in $previewApp.data.inputs">
                <input type="hidden" v-bind:name="input.name" v-bind:value="input.value" />
            </template>
        </form>
    </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/view.scss';
import * as $ from "jquery";

@Component
export default class ApplicationComponent extends Vue
{
    iframeClass: string = 'desktop';

    mounted() {
        $(document).on('tablet', ()  => {
            this.iframeClass = 'tablet';
            this.$forceUpdate();
        });

        $(document).on('desktop', ()  => {
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
