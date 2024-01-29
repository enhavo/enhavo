<template>
    <div class="login-form">
        <div class="feedback-messages">
            <div class="feedback-message success">
                <div>{{ translator.trans('reset_password.finish.success', null, 'EnhavoUserBundle') }}</div>
            </div>
        </div>

        <div class="button-row">
            <router-link :to="{name: 'enhavo_user_login'}" class="btn login-button">{{ translator.trans('reset_password.label.login', null, 'EnhavoUserBundle') }}</router-link>
        </div>
    </div>
</template>

<script setup lang="ts">
import {Translator} from "@enhavo/app/translation/Translator";
import {inject} from "vue";

const translator = inject('translator') as Translator
</script>
