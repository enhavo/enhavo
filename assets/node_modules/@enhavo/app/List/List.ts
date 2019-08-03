import ListData from "@enhavo/app/List/ListData";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import * as _ from "lodash";
import ColumnManager from "@enhavo/app/Grid/Column/ColumnManager";
import Router from "@enhavo/core/Router";
import axios from "axios";
import Item from "@enhavo/app/List/Item";
import RowData from "@enhavo/app/Grid/Column/RowData";
import Translator from "@enhavo/core/Translator";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import Message from "@enhavo/app/FlashMessage/Message";

export default class List
{
    private columnManager: ColumnManager;
    private router: Router;
    private eventDispatcher: EventDispatcher;
    private data: ListData;
    private view: View;
    private translator: Translator;
    private flashMessenger: FlashMessenger;

    constructor(
        data: ListData,
        eventDispatcher: EventDispatcher,
        view: View,
        columnManager: ColumnManager,
        router: Router,
        translator: Translator,
        flashMessenger: FlashMessenger
    ) {
        _.extend(data, new ListData);
        this.data = data;
        this.eventDispatcher = eventDispatcher;
        this.columnManager = columnManager;
        this.view = view;
        this.router = router;
        this.translator = translator;
        this.flashMessenger = flashMessenger;

        this.eventDispatcher.on('updated', (event: UpdatedEvent) => {
            if(event.id == this.data.editView) {
                this.load();
            }
        });
    }

    public load()
    {
        let parameters: any = {};
        if(this.data.dataRouteParameters) {
            parameters = this.data.dataRouteParameters;
        }
        let url = this.router.generate(this.data.dataRoute, parameters);

        this.data.loading = true;
        axios
            .get(url)
            // executed on success
            .then(response => {
                this.data.items = this.createItemsData(response.data.resources);
                this.data.token = response.data.token;
                this.data.loading = false;
            })
            // executed on error
            .catch(error => {

            })
    }

    private createItemsData(resources: any): Item[]
    {
        for(let resource of resources) {
            _.extend(resource, new Item);
            if(resource.children) {
                this.createItemsData(resource.children);
            }
            resource.parentProperty = this.data.parentProperty;
            resource.positionProperty = this.data.positionProperty;
        }
        return resources;
    }

    public open(row: RowData)
    {
        let parameters: any = {};
        if(this.data.updateRouteParameters) {
            parameters = this.data.updateRouteParameters;
        }
        parameters.id = row.id
        let url = this.router.generate(this.data.updateRoute, parameters);
        let label = this.translator.trans('enhavo_app.edit');
        this.view.open(label, url, 'edit-view');
    }

    save(parent: Item)
    {
        let items: Item[] = null;
        if(parent === null) {
            items = this.data.items
        } else {
            items = parent.children;
        }

        let ids = [];
        for(let item of items) {
            ids.push(item.id);
        }

        let data = {
            parent: parent ? parent.id : null,
            items: ids
        };

        let url = this.router.generate(this.data.dataRoute, {
            _csrf_token: this.data.token,
        });
        axios
            .post(url, data)
            // executed on success
            .then(response => {
                this.flashMessenger.addMessage(new Message(
                    'success',
                    this.translator.trans('enhavo_app.list.message.save')
                ))
            })
            // executed on error
            .catch(error => {
                this.flashMessenger.addMessage(new Message(
                    'success',
                    this.translator.trans('enhavo_app.list.message.error')
                ))
            })
    }
}