import * as URI from 'urijs';
import View from "@enhavo/app/ViewStack/Model/View";
import DataStorageEntry from "@enhavo/app/ViewStack/DataStorageEntry";

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
        let uri = new URI(this.baseUrl);
        return uri.setQuery('state',  JSON.stringify(stateObject)) + '';
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