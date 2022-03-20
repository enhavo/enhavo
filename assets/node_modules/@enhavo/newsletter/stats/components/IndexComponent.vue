<template>
    <div class="app-view" style="padding-top: 0">
        <view-view></view-view>
        <table class="table">
            <tr>
                <th scope="col">{{ trans('enhavo_newsletter.stats.label.email') }}</th>
                <th scope="col">{{ trans('enhavo_newsletter.stats.label.sentAt') }}</th>
                <th scope="col">{{ trans('enhavo_newsletter.stats.label.readAt') }}</th>
            </tr>
            <tr v-for="receiver in statsApp.newsletter.receivers">
                <td>{{ receiver.email }}</td>
                <td>{{ sentAt(receiver) }}</td>
                <td><span v-if="receiver.isRead()">{{ readAt(receiver) }}</span><span v-if="!receiver.isRead()">-</span></td>
            </tr>
        </table>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Inject } from "vue-property-decorator";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/view.scss'
import 'bootstrap/dist/css/bootstrap.css'
import Receiver from "@enhavo/newsletter/model/Receiver";
import * as moment from "moment";
import StatsApp from "@enhavo/newsletter/stats/StatsApp";
import Translator from "@enhavo/core/Translator";

@Options({})
export default class extends Vue
{
    @Inject()
    statsApp: StatsApp;

    @Inject()
    translator: Translator

    trans(value) {
        return this.translator.trans(value);
    }

    sentAt(receiver: Receiver): string
    {
        if(receiver.sentAt) {
            return moment(receiver.sentAt).format('DD.MM.YYYY HH:mm')
        }
        return null
    }

    readAt(receiver: Receiver): string
    {
        let date = receiver.getFirstReadDate();
        if(date) {
            return moment(date).format('DD.MM.YYYY HH:mm')
        }
        return null
    }
}
</script>
