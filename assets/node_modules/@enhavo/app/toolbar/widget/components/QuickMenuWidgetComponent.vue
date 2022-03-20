<template>
    <div class="toolbar-dropdown" v-click-outside="close" v-bind:class="{'selected': isOpen}">
        <div class="toolbar-dropdown-title" v-on:click="toggle">
            <i :class="getIcon()"></i>
            <i v-bind:class="['open-indicator', 'icon icon-keyboard_arrow_down', {'icon-keyboard_arrow_up': isOpen }]" aria-hidden="true"></i>
        </div>
        <div class="toolbar-dropdown-menu" v-show="isOpen">
            <template v-for="menu in data.menu">
                <div class="toolbar-dropdown-item" @click="open(menu)" >{{ menu.label }}</div>
            </template>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import QuickMenuWidget from "@enhavo/app/toolbar/widget/model/QuickMenuWidget";

@Options({})
export default class QuickMenuWidgetComponent extends Vue {
    @Prop()
    data: QuickMenuWidget;

    isOpen: boolean = false;

    toggle (): void {
        this.isOpen = !this.isOpen;
    }

    close(): void {
        this.isOpen = false;
    }

    open(menu: any) {
        this.data.open(menu);
        this.close();
    }

    getIcon() {
        if(this.data.icon) {
            return 'icon icon-'+this.data.icon;
        }
        return ''
    }
}
</script>
