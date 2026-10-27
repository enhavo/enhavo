<template>
    <user-app>
        <div class="login-form" v-if="translator">
            <div class="feedback-messages">
                <div class="feedback-message success">
                    <div>{{ translator.trans('enhavo_user.reset_password.finish.success', null, 'javascript') }}</div>
                </div>
            </div>

            <div class="button-row">
                <router-link :to="{name: 'enhavo_user_admin_login'}" class="btn login-button">{{ translator.trans('enhavo_user.reset_password.label.login', null, 'javascript') }}</router-link>
            </div>
        </div>
    </user-app>
</template>

<script setup lang="ts">
import {Translator} from "@enhavo/framework/translation/Translator";
import {useAsyncService} from "@enhavo/framework/composables/service";

const translator = useAsyncService<Translator>('translator')
</script>
