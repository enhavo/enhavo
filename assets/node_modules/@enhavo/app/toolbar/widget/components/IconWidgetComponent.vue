<template>
    <div @click="open()">
        <i :class="getIcon()"></i>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import IconWidget from "@enhavo/app/toolbar/widget/model/IconWidget";

@Options({})
export default class IconWidgetComponent extends Vue {
    name: 'icon-widget';

    @Prop()
    data: IconWidget;

    getIcon()
    {
        if(this.data.icon) {
            return 'icon icon-'+this.data.icon;
        }
        return ''
    }

    open() {
        this.data.open();
    }
}
</script>