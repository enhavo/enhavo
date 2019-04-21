import * as URI from 'urijs';
import View from "@enhavo/app/ViewStack/Model/View";
import DataStorageEntry from "@enhavo/app/ViewStack/DataStorageEntry";
import * as pako from "pako";

export default class StateManager
{
    private baseUrl: string;
    private views: View[];
    private storage: DataStorageEntry[];

    constructor()
    {
        this.baseUrl = window.location.href;
    }

    public setStorage(entries: DataStorageEntry[])
    {
        this.storage = entries;
    }

    public updateStorage(entries: DataStorageEntry[])
    {
        this.storage = entries;
        let stateObject = this.createStateObject();
        history.pushState(stateObject, "enhavo", this.generateUrl(stateObject));
    }

    public setViews(views: View[])
    {
        this.views = views;
    }

    public updateViews(views: View[])
    {
        this.views = views;
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
        for(let view of this.views) {
            if(view.removed) {
                continue;
            }
            viewData.push({
                url: view.url,
                label: view.label,
                id: view.id
            });
        }

        return {
            views: viewData,
            storage: this.storage
        };
    }
}