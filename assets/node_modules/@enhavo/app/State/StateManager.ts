import * as URI from 'urijs';
import ViewStack from "@enhavo/app/ViewStack/ViewStack";
import * as pako from "pako";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import GlobalDataStorageManager from "@enhavo/app/ViewStack/GlobalDataStorageManager";

export default class StateManager
{
    private baseUrl: string;
    private viewStack: ViewStack;
    private eventDispatcher: EventDispatcher;
    private dataStorage: GlobalDataStorageManager;

    constructor(viewStack: ViewStack, eventDispatcher: EventDispatcher, dataStorage: GlobalDataStorageManager)
    {
        this.baseUrl = window.location.href;
        this.viewStack = viewStack;
        this.eventDispatcher = eventDispatcher;
        this.dataStorage = dataStorage;

        this.eventDispatcher.on('save-state', () => {
            window.setTimeout(() => {
                this.saveState();
            }, 50);
        });

        window.onpopstate = (event) => {
            this.applyState(event.state);
        };
    }

    private applyState(state: any)
    {
        location.reload();
    }

    private saveState()
    {
        let stateObject = this.createStateObject();
        history.pushState(stateObject, "enhavo", this.generateUrl(stateObject));
    }

    private generateUrl(stateObject: object): string
    {
        let dataArray = pako.deflate(JSON.stringify(stateObject));
        let b64encoded = btoa(String.fromCharCode.apply(null, dataArray));
        let uri = new URI(this.baseUrl);
        return uri.setQuery('state',  b64encoded) + '';
    }

    private createStateObject(): object
    {
        let viewData = [];
        for(let view of this.viewStack.getViews()) {
            viewData.push({
                url: view.url,
                id: view.id,
                storage: view.storage
            });
        }

        return {
            views: viewData,
            storage: this.dataStorage.getStorage()
        };
    }
}