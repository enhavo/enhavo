<template>
    <div class="menu style-compact">
        <template v-for="item in $menuManager.data.items">
            <component class="menu-child" v-bind:is="item.component" v-bind:data="item"></component>
        </template>
    </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";

@Component()
export default class Menu extends Vue {

}
</script>
