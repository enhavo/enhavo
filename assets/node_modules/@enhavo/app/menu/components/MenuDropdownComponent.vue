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

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import MenuDropdown from '@enhavo/app/menu/model/MenuDropdown';

@Options({})
export default class extends Vue
{
    @Prop()
    data: MenuDropdown;

    change(value: any) {
        this.data.change(value);
    }
}
</script>