import ActionManager from "@enhavo/app/action/ActionManager";
import View from "@enhavo/app/view/View";
import * as _ from "lodash";
import Newsletter from "@enhavo/newsletter/model/Newsletter";
import Receiver from "@enhavo/newsletter/model/Receiver";
import Tracking from "@enhavo/newsletter/model/Tracking";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class StatsApp
{
    public newsletter: Newsletter;

    private actionManager: ActionManager;
    private view: View;
    private componentRegistry: ComponentRegistryInterface;

    constructor(data: any, view: View, actionManager: ActionManager, componentRegistry: ComponentRegistryInterface)
    {
        this.newsletter = _.assign(new Newsletter(), data);
        this.view = view;
        this.actionManager = actionManager;
        this.componentRegistry = componentRegistry;
    }

    init()
    {
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

        this.componentRegistry.registerStore('statsApp', this);
        this.componentRegistry.registerData(this);

        this.view.init();
        this.actionManager.init();
        this.view.addDefaultCloseListener();
        this.view.ready();
    }
}
