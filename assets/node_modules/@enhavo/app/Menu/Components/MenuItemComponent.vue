<template>
    <div class="menu-child-title menu-item" @click="open()">
        <div class="symbol-container">
            <i v-bind:class="['icon', icon]" aria-hidden="true"></i>
        </div>
        <div class="label-container">
            {{ label }}
        </div>
        <menu-notification v-if="notification" v-bind:data="notification"></menu-notification>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
    import ClearEvent from '@enhavo/app/ViewStack/Event/ClearEvent';
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import ApplicationInterface from "@enhavo/app/ApplicationInterface";
    let application = <ApplicationInterface>ApplicationBag.getApplication();

    @Component
    export default class MenuItemComponent extends Vue {
        name: string = 'menu-item';

        @Prop()
        data: object;

        get label(): string {
            return (this.data && this.data.label) ? this.data.label : false;
        }

        get icon(): string {
            return (this.data && this.data.icon) ? 'icon-' + this.data.icon : '';
        }

        get notification(): object {
            return (this.data && this.data.notification) ? this.data.notification : false;
        }

        open(): void {
            application.getEventDispatcher().dispatch(new ClearEvent())
                .then(() => {
                    application.getEventDispatcher().dispatch(new CreateEvent({
                        label: 'table',
                        component: 'iframe-view',
                        url: this.data.url
                    }));
                })
                .catch(() => {})
            ;
        }
    }
</script>

<style lang="scss" scoped>
    .menu-item {
    }
</style>






