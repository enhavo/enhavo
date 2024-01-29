<template>
    <form-form :form="userManger.resetPasswordRequestForm" v-if="userManger.resetPasswordRequestForm" class="login-form">

        <div class="feedback-messages" v-if="userManger.resetPasswordRequestForm.errors.length > 0">
            <div class="feedback-message error">
                <div v-for="error in userManger.resetPasswordRequestForm.errors">{{ error.message }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="input-container">
                <form-label :form="userManger.resetPasswordRequestForm.get('userIdentifier')"></form-label>
                <router-link :to="{name: 'enhavo_user_login'}" class="reset-pw-link">{{ translator.trans('reset_password.label.login', null, 'EnhavoUserBundle') }}</router-link>
                <form-widget :form="userManger.resetPasswordRequestForm.get('userIdentifier')" class="textfield" autofocus></form-widget>
                <form-errors :form="userManger.resetPasswordRequestForm.get('userIdentifier')"></form-errors>
            </div>
        </div>

        <div class="button-row">
            <button class="btn login-button" type="submit" id="_submit" name="_submit" @click.prevent="submit()">{{ translator.trans('reset_password.request.submit', null, 'EnhavoUserBundle') }}</button>
        </div>

        <form-widget :form="userManger.resetPasswordRequestForm.get('_token')"></form-widget>
    </form-form>
</template>

<script setup lang="ts">
import {inject, onMounted} from "vue";
import {UserManager, ResetPasswordData} from "../../manager/UserManager";
import {useRouter} from "vue-router";
import {Translator} from "@enhavo/app/translation/Translator";

const router = useRouter();
const userManger = inject('userManager') as UserManager
const translator = inject('translator') as Translator

onMounted(() => {
    userManger.loadResetPasswordRequest();
})

function submit()
{
    userManger.resetPasswordRequest().then((data: ResetPasswordData) => {
        if (data.success) {
            router.push({path: data.url})
        }
    })
}

</script>
