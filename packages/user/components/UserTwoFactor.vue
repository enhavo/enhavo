<template>
    <user-app>
        <form-form :form="userManager.twoFactorForm" v-if="userManager.twoFactorForm" class="login-form" :key="userManager.twoFactorForm.key">

            <div class="feedback-messages" v-if="userManager.twoFactorForm.errors.length > 0" style="margin-bottom: 15px">
                <div class="feedback-message error">
                    <div v-for="error in userManager.twoFactorForm.errors">{{ error.message }}</div>
                </div>
            </div>

            <div class="form-row">
                <div class="input-container">
                    <form-label :form="userManager.twoFactorForm.get('_auth_code')"></form-label>
                    <form-widget :form="userManager.twoFactorForm.get('_auth_code')" class="textfield" autofocus></form-widget>
                </div>
            </div>

            <div class="button-row">
                <a class="btn cancel-button" :href="userManager.getLogoutUrl()">{{ translator.trans('enhavo_user.two_factor.cancel', null, 'javascript') }}</a>
                <button class="btn login-button" type="submit" @click.prevent="userManager.submitTwoFactor()" :disabled="userManager.loading">{{ translator.trans('enhavo_user.two_factor.submit', null, 'javascript') }}</button>
            </div>

        </form-form>
    </user-app>
</template>

<script setup lang="ts">
import {inject, onMounted} from "vue";
import {UserManager} from "../manager/UserManager";
import {Translator} from "@enhavo/app/translation/Translator";

const userManager = inject('userManager') as UserManager
const translator = inject('translator') as Translator

onMounted(() => {
    userManager.loadTwoFactor();
})
</script>
