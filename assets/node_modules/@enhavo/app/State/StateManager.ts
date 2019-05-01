import * as URI from 'urijs';
import DataStorageEntry from "@enhavo/app/ViewStack/DataStorageEntry";
import ViewStack from "@enhavo/app/ViewStack/ViewStack";
import * as pako from "pako";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

export default class StateManager
{
    private baseUrl: string;
    private storage: DataStorageEntry[];
    private viewStack: ViewStack;
    private eventDispatcher: EventDispatcher;

    constructor(viewStack: ViewStack, eventDispatcher: EventDispatcher)
    {
        this.baseUrl = window.location.href;
        this.viewStack = viewStack;
        this.eventDispatcher = eventDispatcher;

        this.eventDispatcher.on('save-state', () => {
            this.saveState();
        });

        window.onpopstate = (event) => {
            this.applyState(event.state);
        };
    }

    private applyState(state: any)
    {
        console.log(state);
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
            if(view.removed) {
                continue;
            }
            viewData.push({
                url: view.url,
                label: view.label,
                id: view.id,
                storage: view.storage
            });
        }

        return {
            views: viewData
        };
    }
}