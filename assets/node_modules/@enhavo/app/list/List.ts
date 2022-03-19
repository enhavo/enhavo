import ListData from "@enhavo/app/list/ListData";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import View from "@enhavo/app/view/View";
import * as _ from "lodash";
import ColumnManager from "@enhavo/app/grid/column/ColumnManager";
import Router from "@enhavo/core/Router";
import axios from "axios";
import Item from "@enhavo/app/list/Item";
import Translator from "@enhavo/core/Translator";
import UpdatedEvent from "@enhavo/app/view-stack/event/UpdatedEvent";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import Message from "@enhavo/app/flash-message/Message";
import * as async from "async";
import ViewInterface from "@enhavo/app/view-stack/ViewInterface";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class List
{
    public data: ListData;

    private readonly eventDispatcher: EventDispatcher;
    private readonly columnManager: ColumnManager;
    private readonly view: View;
    private readonly router: Router;
    private readonly translator: Translator;
    private readonly flashMessenger: FlashMessenger;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(
        data: ListData,
        eventDispatcher: EventDispatcher,
        view: View,
        columnManager: ColumnManager,
        router: Router,
        translator: Translator,
        flashMessenger: FlashMessenger,
        componentRegistry: ComponentRegistryInterface
    ) {
        _.extend(data, new ListData);
        this.data = data;
        this.eventDispatcher = eventDispatcher;
        this.columnManager = columnManager;
        this.view = view;
        this.router = router;
        this.translator = translator;
        this.flashMessenger = flashMessenger;
        this.componentRegistry = componentRegistry;
    }

    public init()
    {
        this.flashMessenger.init();
        this.view.init();

        this.columnManager.columns = this.data.columns;
        this.columnManager.init();

        this.eventDispatcher.on('updated', (event: UpdatedEvent) => {
            this.view.loadValue('edit-view', (id) => {
                if(event.id == parseInt(id)) {
                    this.load();
                }
            });
        });

        this.eventDispatcher.on('removed', (event: UpdatedEvent) => {
            this.view.loadValue('active-view', (id) => {
                if(event.id == parseInt(id)) {
                    this.clearActiveItem();
                }
            });
        });

        this.eventDispatcher.on('loaded', (event: UpdatedEvent) => {
            this.view.loadValue('edit-view', (id) => {
                if(event.id == parseInt(id)) {
                    this.checkActiveItem();
                }
            });
        });

        this.componentRegistry.registerStore('list', this);
        this.data = this.componentRegistry.registerData(this.data);
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
                this.checkActiveItem();
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
            resource.expand = this.data.expanded;
        }
        return resources;
    }

    public open(item: Item)
    {
        let parameters: any = {};
        if(this.data.openRouteParameters) {
            parameters = this.data.openRouteParameters;
        }
        parameters.id = item.id;
        this.activateItem(item).then(() => {
            let url = this.router.generate(this.data.openRoute, parameters);
            this.view.open(url, 'edit-view').then((view: ViewInterface) => {
                this.view.storeValue('active-view', view.id);
            });
        });
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
                    'error',
                    this.translator.trans('enhavo_app.list.message.error')
                ))
            })
    }

    private getAllItems(): Item[]
    {
        let items = [];
        for(let item of this.data.items) {
            items.push(item);
            for(let descendant of item.getDescendants()) {
                items.push(descendant);
            }
        }
        return items;
    }

    private activateItem(item: Item)
    {
        return new Promise((resolve, reject) => {
            for(let currentItem of this.getAllItems()) {
                currentItem.active = currentItem.id == item.id;
            }

            async.parallel([(callback: (err: any) => void) => {
                this.view.storeValue('active-view', null).then(() => {
                    callback(null);
                }).catch(() => {
                    callback(true);
                });
            },(callback: (err: any) => void) => {
                this.view.storeValue('active-item', item.id).then(() => {
                    callback(null);
                }).catch(() => {
                    callback(true);
                });
            }], (err) => {
                if(err) {
                    reject();
                } else {
                    resolve();
                }
            });
        });
    }

    private checkActiveItem()
    {
        this.view.loadValue('active-item', (id) => {
            if(id) {
                for(let currentItem of this.getAllItems()) {
                    currentItem.active = currentItem.id === parseInt(id);
                }
            }
        });
    }

    public clearActiveItem()
    {
        this.view.storeValue('active-view', null);
        this.view.storeValue('active-item', null);
        for(let currentItem of this.getAllItems()) {
            currentItem.active = false;
        }
    }
}
