<template>
    <div class="menu-child-title menu-item" @click="open()">
        <i v-bind:class="['fa', data.icon]" aria-hidden="true"></i>
        {{ label }}
        <menu-notification v-if="notification" v-bind:data="notification"></menu-notification>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import CreateEvent from  '../../ViewStack/Event/CreateEvent';
    import dispatcher from '../../ViewStack/dispatcher';

    @Component
    export default class MenuItem extends Vue {
        name: string = 'menu-item';

        @Prop()
        data: object;

        get label(): string {
            return (this.data && this.data.label) ? this.data.label : false;
        }

        get icon(): string {
            return (this.data && this.data.icon) ? this.data.icon : false;
        }

        get notification(): object {
            return (this.data && this.data.notification) ? this.data.notification : false;
        }

        open(): void {
            dispatcher.dispatch(new CreateEvent({
                label: 'table',
                component: 'iframe-view',
                url: '/admin/view'
            }));
        }
    }
</script>

<style lang="scss" scoped>
    .menu-item { 
        height: 50px; cursor: pointer; 
    }
</style>






