<template>
    <div class="toolbar-dropdown" v-click-outside="close" v-bind:class="{'selected': isOpen}">
        <div class="toolbar-dropdown-title" v-on:click="toggle">
            <i class="icon icon-person"></i>
            <i v-bind:class="['open-indicator', 'icon icon-keyboard_arrow_down', {'icon-keyboard_arrow_up': isOpen }]" aria-hidden="true"></i>
        </div>
        <div class="toolbar-dropdown-menu" v-show="isOpen">
            <toolbar-dropdown-item v-on:click="logout" :label="label('enhavo_app.logout')"></toolbar-dropdown-item>
            <toolbar-dropdown-item v-on:click="changePassword" :label="label('enhavo_app.change_password')"></toolbar-dropdown-item>
            <toolbar-dropdown-item v-on:click="openHomepage" :label="label('enhavo_app.open_homepage')"></toolbar-dropdown-item>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import DropdownItem from "@enhavo/app/Toolbar/Components/ToolbarDropdownItem.vue"
import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
import ClearEvent from '@enhavo/app/ViewStack/Event/ClearEvent';
import ApplicationBag from "@enhavo/app/ApplicationBag";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

@Component({
    components: {'toolbar-dropdown-item': DropdownItem}
})
export default class ToolbarDropdown extends Vue {
    name: 'toolbar-dropdown';

    isOpen: boolean = false;

    toggle (): void {
        this.isOpen = !this.isOpen;
    }

    label(value): void
    {
        return this.getApplication().getTranslator().trans(value);
    }

    close(): void {
        this.isOpen = false;
    }

    logout(): void
    {
        window.location.href = this.getApplication().getRouter().generate('enhavo_user_security_logout');
    }

    changePassword(): void
    {
        this.getApplication().getEventDispatcher().dispatch(new ClearEvent())
            .then(() => {
                this.getApplication().getEventDispatcher()
                    .dispatch(new CreateEvent({
                        label: this.label('enhavo_app.change_password'),
                        component: 'iframe-view',
                        url: this.getApplication().getRouter().generate('enhavo_user_change_password_change')
                    }))
                    .then(() => {
                        this.getApplication().getMenuManager().clearSelections();
                    });
            })
            .catch(() => {})
        ;
    }

    openHomepage()
    {
        window.open('/', '_blank');
    }

    private getApplication(): ApplicationInterface
    {
        return <ApplicationInterface>ApplicationBag.getApplication();
    }
}
</script>






