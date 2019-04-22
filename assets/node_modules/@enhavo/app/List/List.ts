import ListData from "@enhavo/app/List/ListData";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import * as _ from "lodash";
import ColumnManager from "@enhavo/app/Grid/Column/ColumnManager";
import Router from "@enhavo/core/Router";
import Editable from "@enhavo/app/Action/Editable";
import axios from "axios";
import Item from "@enhavo/app/List/Item";
import RowData from "@enhavo/app/Grid/Column/RowData";
import ExistsEvent from "@enhavo/app/ViewStack/Event/ExistsEvent";
import ExistsData from "@enhavo/app/ViewStack/ExistsData";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import SaveDataEvent from "@enhavo/app/ViewStack/Event/SaveDataEvent";
import LoadDataEvent from "@enhavo/app/ViewStack/Event/LoadDataEvent";
import Translator from "@enhavo/core/Translator";
import RemovedEvent from "@enhavo/app/ViewStack/Event/RemovedEvent";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";

export default class List implements Editable
{
    private columnManager: ColumnManager;
    private router: Router;
    private eventDispatcher: EventDispatcher;
    private data: ListData;
    private view: View;
    private translator: Translator;

    constructor(data: ListData, eventDispatcher: EventDispatcher, view: View, columnManager: ColumnManager, router: Router, translator: Translator)
    {
        _.extend(data, new ListData);
        this.data = data;
        this.eventDispatcher = eventDispatcher;
        this.columnManager = columnManager;
        this.view = view;
        this.router = router;
        this.translator = translator;

        let key = this.getEditKey();
        this.eventDispatcher.dispatch(new LoadDataEvent(key))
            .then((data: EditViewData) => {
                if(data.id) {
                    this.data.editView = data.id;
                }
            });

        this.eventDispatcher.on('removed', (event: RemovedEvent) => {
            if(event.id == this.data.editView) {
                this.data.editView = null;
            }
        });

        this.eventDispatcher.on('updated', (event: UpdatedEvent) => {
            if(event.id == this.data.editView) {
                this.load();
            }
        });
    }

    public load()
    {
        let url = this.router.generate(this.data.dataRoute);
        this.data.loading = true;
        axios
            .post(url)
            // executed on success
            .then(response => {
                this.data.items = this.createItemsData(response.data.resources);
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
        }
        return resources;
    }

    public getEditView(): number
    {
        return this.data.editView;
    }

    public setEditView(id: number): void
    {
        this.data.editView = id;
        let key = this.getEditKey();
        this.eventDispatcher.dispatch(new SaveDataEvent(key, {
            id: id
        }));
    }

    private getEditKey()
    {
        return 'edit-view-' + this.view.getId();
    }

    public open(row: RowData)
    {
        if(this.data.editView != null) {
            this.eventDispatcher.dispatch(new ExistsEvent(this.getEditView())).then((data: ExistsData) => {
                if(data.exists) {
                    this.eventDispatcher.dispatch(new CloseEvent(this.data.editView))
                        .then(() => {
                            this.openView(row);
                        })
                        .catch(() => {})
                    ;
                } else {
                    this.openView(row);
                }
            });
        } else {
            this.openView(row);
        }
    }

    protected openView(row: RowData)
    {
        let url = this.router.generate(this.data.updateRoute, {
            id: row.id
        });

        this.eventDispatcher.dispatch(new CreateEvent({
            label: this.translator.trans('enhavo_app.edit'),
            component: 'iframe-view',
            url: url
        }, this.view.getId())).then((view: ViewInterface) => {
            this.setEditView(view.id);
        }).catch(() => {});
    }
}

interface EditViewData {
    id: number;
}