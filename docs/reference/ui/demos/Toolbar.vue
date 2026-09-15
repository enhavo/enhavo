<template>
    <e-toolbar>
        <e-toolbar-brand :collapsed="menuCollapsed"></e-toolbar-brand>
        <e-toolbar-left>
            <button @click="menuCollapsed = !menuCollapsed">Toogle Menu</button>
        </e-toolbar-left>
        <e-toolbar-right>
        </e-toolbar-right>
    </e-toolbar>
</template>

<script lang="ts" setup>
import {ref} from "vue";

const menuCollapsed = ref(false)
</script>
