<template>
    <div class="menu-child-title menu-item" @click="open()">
        <i v-bind:class="['icon', icon]" aria-hidden="true"></i>
        {{ label }}
        <menu-notification v-if="notification" v-bind:data="notification"></menu-notification>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import CreateEvent from '../../ViewStack/Event/CreateEvent';
    import ClearEvent from '../../ViewStack/Event/ClearEvent';
    import dispatcher from '../../ViewStack/dispatcher';

    @Component
    export default class MenuItemComponent extends Vue {
        name: string = 'menu-item';

        @Prop()
        data: object;

        get label(): string {
            return (this.data && this.data.label) ? this.data.label : false;
        }

        get icon(): string {
            return (this.data && this.data.icon) ? 'icon-' + this.data.icon : false;
        }

        get notification(): object {
            return (this.data && this.data.notification) ? this.data.notification : false;
        }

        open(): void {
            dispatcher.dispatch(new ClearEvent())
                .then(() => {
                    dispatcher.dispatch(new CreateEvent({
                        label: 'table',
                        component: 'iframe-view',
                        url: '/admin/view'
                    }));
                })
                .catch(() => {})
            ;

        }
    }
</script>

<style lang="scss" scoped>
    .menu-item { 
        height: 50px; cursor: pointer; 
    }
</style>






