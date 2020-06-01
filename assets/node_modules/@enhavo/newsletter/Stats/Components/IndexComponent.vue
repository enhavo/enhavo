<template>
    <div class="app-view" style="padding-top: 0">
        <view-view v-bind:data="view"></view-view>
        <!--<action-bar v-bind:primary="actions"></action-bar>-->
        <table class="table">
            <tr>
                <th scope="col">{{ trans('enhavo_newsletter.stats.label.email') }}</th>
                <th scope="col">{{ trans('enhavo_newsletter.stats.label.sentAt') }}</th>
                <th scope="col">{{ trans('enhavo_newsletter.stats.label.readAt') }}</th>
            </tr>
            <tr v-for="receiver in resource.receivers">
                <td>{{ receiver.email }}</td>
                <td>{{ sentAt(receiver) }}</td>
                <td><span v-if="receiver.isRead()">{{ readAt(receiver) }}</span><span v-if="!receiver.isRead()">-</span></td>
            </tr>
        </table>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ActionBar from "@enhavo/app/Action/Components/ActionBar.vue";
    import '@enhavo/app/assets/styles/view.scss'
    import 'bootstrap/dist/css/bootstrap.css'
    import ViewData from "@enhavo/app/View/ViewData";
    import ViewComponent from "@enhavo/app/View/Components/ViewComponent";
    import Newsletter from "@enhavo/newsletter/Model/Newsletter";
    import Receiver from "@enhavo/newsletter/Model/Receiver";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import StatsApplication from "@enhavo/newsletter/Stats/StatsApplication";
    import * as moment from "moment";
    const application = <StatsApplication>ApplicationBag.getApplication();

    @Component({
        components: {ActionBar, 'view-view': ViewComponent}
    })
    export default class AppView extends Vue
    {
        name = 'app-view';

        @Prop()
        resource: Newsletter;

        @Prop()
        view: ViewData;

        @Prop()
        actions: Array<object>;

        trans(value) {
            return application.getTranslator().trans(value);
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
