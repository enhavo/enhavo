<template>
    <div class="overlay">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">

</script>

<style scoped>
    .overlay {
        position: absolute;top:0;left:0;width:100%;height:100%;
    }
</style>
