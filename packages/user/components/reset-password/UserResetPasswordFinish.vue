<template>
    <user-app>
        <div class="login-form">
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
import {Translator} from "@enhavo/app/translation/Translator";
import {inject} from "vue";

const translator = inject('translator') as Translator
</script>
