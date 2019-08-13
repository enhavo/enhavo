import * as $ from 'jquery';
import * as _ from "lodash";
import Tab from "@enhavo/app/Form/Tab";
import FormData from "@enhavo/app/Form/FormData";
import FormRegistry from "@enhavo/app/Form/FormRegistry";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";
import View from "@enhavo/app/View/View";

export default class Form
{
    private tabs: Tab[];
    private data: FormData;
    private registry: FormRegistry;
    private flashMessenger: FlashMessenger;
    private eventDispatcher: EventDispatcher;

    constructor(
        data: FormData,
        registry: FormRegistry,
        flashMessenger: FlashMessenger,
        eventDispatcher: EventDispatcher,
        view: View,
    ) {
        this.data = _.extend(data, new FormData);
        for (let i in data.tabs) {
            _.extend(data.tabs[i], new Tab);
        }
        this.tabs = data.tabs;
        this.registry = registry;
        this.flashMessenger = flashMessenger;
        this.eventDispatcher = eventDispatcher;

        if(this.flashMessenger.has('success')) {
            this.eventDispatcher.dispatch(new UpdatedEvent(view.getId(), data.resource))
        }
    }

    public load()
    {
        for(let tab of this.tabs) {
            if(!this.data.tab) {
                this.data.tab = tab.key;
            }
            let $tab = $('[data-tab-container]').find('[data-tab='+tab.key+']');
            tab.error = $tab.find('[data-form-error]').length > 0;
            tab.element = <HTMLElement>$tab[0];
        }
    }

    public changeTab(key: string)
    {
        this.data.tab = key;
    }

    public changeForm()
    {
        this.data.formChanged = true;
    }
}