<template>
    <form-form :form="userManger.loginForm" v-if="userManger.loginForm" class="login-form" :key="userManger.loginForm.key">

        <div class="feedback-messages" v-if="userManger.loginForm.errors.length > 0">
            <div class="feedback-message error">
                <div v-for="error in userManger.loginForm.errors">{{ error.message }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="input-container">
                <form-label :form="userManger.loginForm.get('userIdentifier')"></form-label>
                <form-widget :form="userManger.loginForm.get('userIdentifier')" class="textfield" autofocus></form-widget>
            </div>
        </div>

        <div class="form-row">
            <div class="input-container">
                <form-label :form="userManger.loginForm.get('password')"></form-label>
                <router-link :to="{name: 'enhavo_user_admin_reset_password_request'}" class="reset-pw-link">{{ translator.trans('reset_password.request.submit', null, 'EnhavoUserBundle') }}</router-link>
                <form-widget :form="userManger.loginForm.get('password')" class="textfield"></form-widget>
            </div>
        </div>

        <div class="button-row">
            <div class="checkbox-container">
                <form-widget :form="userManger.loginForm.get('rememberMe')" class="textfield"></form-widget>
                <span><i class="icon icon-check indicator"></i></span>
                <form-label :form="userManger.loginForm.get('rememberMe')"></form-label>
            </div>
            <button class="btn login-button" type="submit" id="_submit" name="_submit" @click.prevent="userManger.login()">{{ translator.trans('security.login.submit', null, 'EnhavoUserBundle') }}</button>
        </div>

        <form-widget :form="userManger.loginForm.get('csrfToken')"></form-widget>

        <input type="hidden" name="_target_path" :value="redirectUrl()" />
    </form-form>
</template>

<script setup lang="ts">
import {inject, onMounted} from "vue";
import {UserManager} from "../manager/UserManager";
import {useRouter} from "vue-router";
import {Translator} from "@enhavo/app/translation/Translator";

const router = useRouter();
const userManger = inject('userManager') as UserManager
const translator = inject('translator') as Translator

onMounted(() => {
    userManger.loadLogin();
})

function redirectUrl(): string
{
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('redirectUrl');
}

</script>
