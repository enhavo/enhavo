<template>
    <div class="viewstack-dropdown-container" v-if="hasMoreThanOneView" v-click-outside="close" v-bind:class="{'selected': isOpen}">
        <div class="dropdown-trigger" @click="toggle()">
            <span class="icon icon-more_horiz"></span>
        </div>
        <ul v-if="isOpen" class="viewstack-dropdown">
            <template v-for="view in view_stack.views">
                <li v-if="!view.removed" @click="maximize(view);close();">{{ view.label }}</li>
            </template>
        </ul>
    </div>
</template>

<script lang="ts">

import { Vue, Component, Prop } from "vue-property-decorator";
import ViewStackData from "@enhavo/app/ViewStack/ViewStackData";

@Component()
export default class ViewstackDropdown extends Vue {
    name: 'viewstack-dropdown';

    @Prop()
    view_stack: ViewStackData;
    isOpen: boolean = false;

    toggle (): void {
        this.isOpen = !this.isOpen;
    }

    get hasMoreThanOneView() {
        let count = 0;
        for(let i = 0;i<this.view_stack.views.length;i++) {
            if(this.view_stack.views[i].removed == false) {
                count++;
            }
        }
        return count > 1;
    }

    maximize(element) {
        for (let view of this.view_stack.views) {
            view.minimize = true;
            view.focus = false;
        }
        element.minimize = false;
        element.focus = true;
    }

    close(): void {
        this.isOpen = false;
    }

}
</script>






