<template>
    <div class="menu-dropdown">
        <div class="menu-dropdown-child menu-dropdown-input">
            <v-select
                label="label"
                :placeholder="data.label"
                :options="data.choices"
                v-model="data.selectedValue"
                @update:modelValue="change"
                :searchable="false"
                :clearable="false"
             ></v-select>
        </div>
    </div>
</template>

<script setup lang="ts">
import MenuDropdown from '@enhavo/app/menu/model/MenuDropdown';

const props = defineProps<{
    data: MenuDropdown
}>()

function change(value: any)
{
    props.data.change(value);
}
</script>