<template>
    <user-app>
        <form-form :form="userManger.resetPasswordConfirmForm" v-if="userManger.resetPasswordConfirmForm" class="login-form" :key="userManger.resetPasswordConfirmForm.key">

            <div class="feedback-messages" v-if="userManger.resetPasswordConfirmForm.errors.length > 0">
                <div class="feedback-message error">
                    <div v-for="error in userManger.resetPasswordConfirmForm.errors">{{ error.message }}</div>
                </div>
            </div>

            <div class="form-row">
                <div class="input-container">
                    <form-label :form="userManger.resetPasswordConfirmForm.get('plainPassword.first')"></form-label>
                    <router-link :to="{name: 'enhavo_user_admin_login'}" class="reset-pw-link">{{ translator.trans('enhavo_user.reset_password.label.login', null, 'javascript') }}</router-link>
                    <form-widget :form="userManger.resetPasswordConfirmForm.get('plainPassword.first')" class="textfield" autofocus></form-widget>
                    <form-errors :form="userManger.resetPasswordConfirmForm.get('plainPassword.first')"></form-errors>
                </div>
            </div>

            <div class="form-row">
                <div class="input-container">
                    <form-label :form="userManger.resetPasswordConfirmForm.get('plainPassword.second')"></form-label>
                    <form-widget :form="userManger.resetPasswordConfirmForm.get('plainPassword.second')" class="textfield" autofocus></form-widget>
                    <form-errors :form="userManger.resetPasswordConfirmForm.get('plainPassword.second')"></form-errors>
                </div>
            </div>

            <div class="button-row">
                <button class="btn login-button" type="submit" id="_submit" name="_submit" @click.prevent="submit()">{{ translator.trans('enhavo_user.reset_password.confirm.submit', null, 'javascript') }}</button>
            </div>

            <form-widget :form="userManger.resetPasswordConfirmForm.get('_token')"></form-widget>
        </form-form>

        <div v-if="userManger.resetPasswordConfirmTokenError" class="login-form">
            <div class="feedback-messages">
                <div class="feedback-message error">
                    <div>{{ translator.trans('enhavo_user.reset_password.confirm.invalid_token', null, 'javascript') }}</div>
                </div>
            </div>

            <div class="button-row">
                <router-link :to="{name: 'enhavo_user_admin_reset_password_request'}" class="btn login-button">{{ translator.trans('enhavo_user.reset_password.confirm.renew_request', null, 'javascript') }}</router-link>
            </div>
        </div>
    </user-app>
</template>

<script setup lang="ts">
import {inject, onMounted} from "vue";
import {UserManager, ResetPasswordData} from "../../manager/UserManager";
import {useRouter, useRoute} from "vue-router";
import {Translator} from "@enhavo/app/translation/Translator";

const router = useRouter();
const token = useRoute().params.token as string;
const userManger = inject('userManager') as UserManager
const translator = inject('translator') as Translator

onMounted(() => {
    userManger.loadResetPasswordConfirm(token);
})

function submit()
{
    userManger.resetPasswordConfirm(token).then((data: ResetPasswordData) => {
        if (data.success) {
            router.push({path: data.url})
        }
    })
}
</script>
