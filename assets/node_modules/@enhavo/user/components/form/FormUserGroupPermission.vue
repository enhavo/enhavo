<template>
<div class="choice-list">
    <div class="choice-input-container">
        <table>
            <tr v-for="group in form.groups">
                <td v-for="index in group">
                    <span class="choice-input-with-label">
                        <form-widget :form="form.get(index.toString())" />
                        <form-label :form="form.get(index.toString())" />
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>
</template>

<script setup lang="ts">
import {UserGroupPermissionForm} from "@enhavo/user/form/model/UserGroupPermissionForm";

const props = defineProps<{
    form: UserGroupPermissionForm
}>()

</script>

