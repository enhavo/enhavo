<template>
    <div class="menu-dropdown">
        <div class="menu-dropdown-child menu-dropdown-label">
            {{ data.label }} 
        </div>
        <div class="menu-dropdown-child menu-dropdown-input">
            <select v-model="selected" v-on:change="onChange">
                <option v-for="(choiceLabel, choiceValue) in data.choices" v-bind:value="choiceValue">
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
