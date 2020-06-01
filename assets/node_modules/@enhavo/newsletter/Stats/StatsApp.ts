import DataLoader from '@enhavo/app/DataLoader';
import ActionManager from "@enhavo/app/Action/ActionManager";
import ViewApp from "@enhavo/app/ViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import * as _ from "lodash";
import Newsletter from "@enhavo/newsletter/Model/Newsletter";
import Receiver from "@enhavo/newsletter/Model/Receiver";
import Tracking from "@enhavo/newsletter/Model/Tracking";

export default class StatsApp extends ViewApp
{
    private actionManager: ActionManager;
    private newsletter: Newsletter;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View, actionManager: ActionManager)
    {
        super(loader, eventDispatcher, view);
        this.actionManager = actionManager;
        this.initData();
    }

    private initData()
    {
        let data = this.getData();
        this.newsletter = _.assign(new Newsletter(), data.resource);
        for(let i in this.newsletter.receivers) {
            this.newsletter.receivers[i] = _.assign(new Receiver(), this.newsletter.receivers[i]);
            let receiver = this.newsletter.receivers[i];
            if(receiver.sentAt) {
                receiver.sentAt = new Date(receiver.sentAt);
            }

            for(let i in receiver.tracking) {
                receiver.tracking[i] = _.assign(new Tracking(), receiver.tracking[i]);
                receiver.tracking[i].date = new Date(receiver.tracking[i].date);
            }
        }
    }
}
