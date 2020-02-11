<template>
    <div class="toolbar-dropdown" v-click-outside="close" v-bind:class="{'selected': isOpen}">
        <div class="toolbar-dropdown-title" v-on:click="toggle">
            <i class="icon icon-person"></i>
            <i v-bind:class="['open-indicator', 'icon icon-keyboard_arrow_down', {'icon-keyboard_arrow_up': isOpen }]" aria-hidden="true"></i>
        </div>
        <div class="toolbar-dropdown-menu" v-show="isOpen">
            <quick-menu-item v-on:click="logout();close();" :label="label('enhavo_app.logout')"></quick-menu-item>
            <quick-menu-item v-on:click="changePassword();close();" :label="label('enhavo_app.change_password')"></quick-menu-item>
            <quick-menu-item v-on:click="openHomepage();close();" :label="label('enhavo_app.open_homepage')"></quick-menu-item>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import QuickMenuItem from "@enhavo/app/Toolbar/Widget/Components/QuickMenuItem.vue";
    import QuickMenuWidget from "@enhavo/app/Toolbar/Widget/Model/QuickMenuWidget";
    import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
    import ClearEvent from '@enhavo/app/ViewStack/Event/ClearEvent';

    @Component({
        components: {'quick-menu-item': QuickMenuItem}
    })
    export default class QuickMenuWidgetComponent extends Vue {
        name: 'quick-menu-widget';

        @Prop()
        data: QuickMenuWidget;

        isOpen: boolean = false;

        toggle (): void {
            this.isOpen = !this.isOpen;
        }

        label(value): void
        {
            return this.data.getApplication().getTranslator().trans(value);
        }

        close(): void {
            this.isOpen = false;
        }

        logout(): void
        {
            window.location.href = this.data.getApplication().getRouter().generate('enhavo_user_security_logout');
        }

        changePassword(): void
        {
            this.data.getApplication().getEventDispatcher().dispatch(new ClearEvent())
                .then(() => {
                    this.data.getApplication().getEventDispatcher()
                        .dispatch(new CreateEvent({
                            label: this.label('enhavo_app.change_password'),
                            component: 'iframe-view',
                            url: this.data.getApplication().getRouter().generate('enhavo_user_change_password_change')
                        }))
                        .then(() => {
                            this.data.getApplication().getMenuManager().clearSelections();
                        });
                })
                .catch(() => {})
            ;
        }

        openHomepage()
        {
            window.open('/', '_blank');
        }
    }
</script>
