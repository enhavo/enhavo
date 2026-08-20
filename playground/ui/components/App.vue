<template>
    <div class="playground">
        <nav class="playground__nav">
            <h2><router-link :to="{name: 'index'}">UI Playground</router-link></h2>
            <ul>
                <li><router-link :to="{name: 'application'}">Application</router-link></li>
                <li><router-link :to="{name: 'select'}">Select</router-link></li>
                <li><router-link :to="{name: 'datepicker'}">Datepicker</router-link></li>
            </ul>
        </nav>
        <main class="playground__content">
            <router-view />
        </main>
    </div>
</template>

<script lang="ts" setup>

</script>
