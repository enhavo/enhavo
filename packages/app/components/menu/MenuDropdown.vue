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
import {DropdownMenuItem} from '@enhavo/app/menu/model/DropdownMenuItem';

const props = defineProps<{
    data: DropdownMenuItem
}>()

function change(value: any)
{
    props.data.change(value);
}
</script>