<template>
    <div class="modal">
        <div class="modal-form-container" v-show="!modal.loading">
            <div ref="output" style="color: #fff; overflow-y: scroll; width: 500px; height: 500px; background-color: #000"><pre v-html="convert(modal.output)"></pre></div>
            <div class="buttons">
                <button @click="modal.close()" class="modal-btn">{{ modal.closeLabel }}</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch } from "vue-property-decorator";
    import OutputStreamModal from "@enhavo/app/Modal/Model/OutputStreamModal"
    import * as Convert from "ansi-to-html";
    import * as $ from "jquery";

    @Component({})
    export default class OutputStreamModalComponent extends Vue {
        name: string = 'output-stream-modal';

        @Prop()
        modal: OutputStreamModal;


        convert(value: string): string
        {
            var convert = new Convert();
            return convert.toHtml(value)
        }

        @Watch('modal.output')
        scrollDown()
        {
            window.setTimeout(() => {
                $(this.$refs.output).scrollTop($(this.$refs.output).height());
            }, 100);
        }
    }
</script>
