<template>
    <div class="viewstack-dropdown-container" v-if="hasMoreThanOneView" v-click-outside="close" v-bind:class="{'selected': isOpen}">
        <div class="dropdown-trigger" @click="toggle()">
            <span class="icon icon-more_horiz"></span>
        </div>
        <ul v-if="isOpen" class="viewstack-dropdown">
            <template v-for="view in $viewStack.data.views">
                <li v-if="!view.removed" @click="maximize(view);close();">{{ view.label }}</li>
            </template>
        </ul>
    </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";

@Component()
export default class ViewstackDropdown extends Vue
{
    isOpen: boolean = false;

    toggle (): void {
        this.isOpen = !this.isOpen;
    }

    get hasMoreThanOneView() {
        let count = 0;
        for(let i = 0; i < this.$viewStack.data.views.length;i++) {
            if(this.$viewStack.data.views[i].removed == false) {
                count++;
            }
        }
        return count > 1;
    }

    maximize(element) {
        for (let view of this.$viewStack.data.views) {
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
