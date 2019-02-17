<template>
    <div class="menu-dropdown">
        <div class="menu-child-title menu-dropdown-child menu-dropdown-label">
            <i v-bind:class="['icon', icon]" aria-hidden="true"></i>
            {{ label }} 
            <menu-notification v-if="notification" v-bind:data="notification"></menu-notification>
        </div>
        <div class="menu-dropdown-child menu-dropdown-input">
            <select v-model="selected" v-on:change="onChange">
                <option v-for="(choiceLabel, choiceValue) in choices" v-bind:value="choiceValue">
                    {{ choiceLabel }}
                </option>
            </select>
        </div>

    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch } from "vue-property-decorator";

    @Component
    export default class MenuDropdown extends Vue {
        name: string = 'menu-dropdown';

        @Prop()
        data: object;

        selected: string = '';

        get label(): string {
            return (this.data && this.data.label) ? this.data.label : false;
        }

        get icon(): string {
            return (this.data && this.data.icon) ? 'icon-' + this.data.icon : false;
        }

        get notification(): object {
            return (this.data && this.data.notification) ? this.data.notification : false;
        }

        get choices(): array {
            return (this.data && this.data.choices) ? this.data.choices : false;
        }

        onChange(): string {
            // --> fire change event
            
            return this.selected;
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
