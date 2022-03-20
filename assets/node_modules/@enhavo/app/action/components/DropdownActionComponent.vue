<template>
    <div class="action">
        <div @click="toggleOpen()">
            <div class="action-icon">
                <i v-bind:class="['icon', icon]" aria-hidden="true"></i>
            </div>
            <div class="label">{{ data.label }}</div>
        </div>
        <ul class="dropdown-action-list" v-if="open" @click="itemClick()">
            <template v-for="action in data.items">
                <component class="action-container" v-bind:is="action.component" v-bind:data="action" :data-action="action.key"></component>
            </template>
        </ul>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import DropdownAction from "@enhavo/app/action/model/DropdownAction";

@Options({})
export default class DropdownActionComponent extends Vue
{
    @Prop()
    data: DropdownAction;

    open: boolean = false;

    get icon(): string {
        return (this.data && this.data.icon) ? 'icon-' + this.data.icon : '';
    }

    toggleOpen()
    {
        this.open = !this.open;
    }

    itemClick()
    {
        if (this.data.closeAfter) {
            this.open = false;
        }
    }

    // Close when clicked outside
    close(e: Event) {
        if (!this.$el.contains(<Node>e.target)) {
            this.open = false;
        }
    }

    mounted() {
        document.addEventListener('click', this.close)
    }

    beforeDestroy() {
        document.removeEventListener('click',this.close)
    }
}
</script>
