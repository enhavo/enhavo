<template>
    <div class="menu-dropdown">
        <div class="menu-dropdown-child menu-dropdown-input">
            <v-select clearable @input="change" label="label" :placeholder="data.label" :value="data.value" :options="data.choices"></v-select>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import MenuNotificationComponent from '@enhavo/app/Menu/Components/MenuNotificationComponent';
    import MenuDropdown from '@enhavo/app/Menu/Model/MenuDropdown';

    @Component({
        components: {'menu-notification': MenuNotificationComponent}
    })
    export default class MenuDropdownComponent extends Vue {
        name: string = 'menu-dropdown';

        @Prop()
        data: MenuDropdown;

        change(value): any {
            this.data.change(value);
        }
    }
</script>

<style lang="scss" scoped>
    .menu-dropdown {
        height: 50px; flex-wrap: wrap;

        .menu-dropdown-child {
            flex-basis: 100%;

            select { width: 100%; }
        }
    }
</style>
