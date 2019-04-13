<template>
    <div v-bind:class="{'menu-child-title menu-item': true, 'select': data.selected}" @click="open()">
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
    import MenuItem from '@enhavo/app/Menu/Model/MenuItem';
    import MenuNotificationComponent from '@enhavo/app/Menu/Components/MenuNotificationComponent';

    @Component({
        components: {'menu-notification': MenuNotificationComponent}
    })
    export default class MenuItemComponent extends Vue {
        name: string = 'menu-item';

        @Prop()
        data: MenuItem;

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
            this.data.open()
        }
    }
</script>





