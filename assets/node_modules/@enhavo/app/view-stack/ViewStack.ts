import EventDispatcher from '@enhavo/app/view-stack/EventDispatcher';
import ViewInterface from '@enhavo/app/view-stack/ViewInterface';
import ViewRegistry from '@enhavo/app/view-stack/ViewRegistry';
import CreateEvent from "@enhavo/app/view-stack/event/CreateEvent";
import LoadedEvent from "@enhavo/app/view-stack/event/LoadedEvent";
import CloseEvent from "@enhavo/app/view-stack/event/CloseEvent";
import RemoveEvent from "@enhavo/app/view-stack/event/RemoveEvent";
import ArrangeEvent from "@enhavo/app/view-stack/event/ArrangeEvent";
import ViewStackData from "@enhavo/app/view-stack/ViewStackData";
import RemovedEvent from "@enhavo/app/view-stack/event/RemovedEvent";
import ClearEvent from "@enhavo/app/view-stack/event/ClearEvent";
import ClearedEvent from "@enhavo/app/view-stack/event/ClearedEvent";
import ArrangeManager from "@enhavo/app/view-stack/ArrangeManager";
import LoadingEvent from "@enhavo/app/view-stack/event/LoadingEvent";
import * as async from 'async';
import SaveStateEvent from "@enhavo/app/view-stack/event/SaveStateEvent";
import ChangeUrlEvent from "@enhavo/app/view-stack/event/ChangeUrlEvent";
import FrameStorage from "@enhavo/app/view-stack/FrameStorage";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";
import * as $ from "jquery";

export default class ViewStack
{
    public views: ViewInterface[];
    public data: ViewStackData;

    private nextId: number = 1;

    private readonly dispatcher: EventDispatcher;
    private readonly registry: ViewRegistry;
    private readonly arrangeManager: ArrangeManager;
    private readonly frameStorage: FrameStorage;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(
        data: ViewStackData,
        dispatcher: EventDispatcher,
        viewRegistry: ViewRegistry,
        frameStorage: FrameStorage,
        componentRegistry: ComponentRegistryInterface,
        arrangeManager: ArrangeManager
    ) {
        this.data = data;
        this.dispatcher = dispatcher;
        this.registry = viewRegistry;
        this.frameStorage = frameStorage;
        this.componentRegistry = componentRegistry;
        this.arrangeManager = arrangeManager;

        this.addCloseListener();
        this.addRemoveListener();
        this.addLoadedListener();
        this.addCreateListener();
        this.addClearListener();
        this.addArrangeListener();
        this.addLoadingListener();
        this.addExistsListener();
        this.addChangeUrlListener();
    }

    init() {
        this.data = this.componentRegistry.registerData(this.data);

        $(window).resize(() => {
            this.arrangeManager.resize(this.data.views);
        });

        if(!this.data.views) {
            this.data.views = [];
            this.views = <ViewInterface[]>this.data.views;
        } else {
            for(let i in this.data.views) {
                let view = this.registry.getFactory(this.data.views[i].component).createFromData(this.data.views[i]);
                if(view.id > this.nextId) {
                    this.nextId = view.id + 1;
                }
                this.data.views[i] = view;
            }

            this.views = <ViewInterface[]>this.data.views;
        }

        for (let component of this.registry.getComponents()) {
            this.componentRegistry.registerComponent(component.name, component.component)
        }

        this.componentRegistry.registerStore('viewStack', this);
    }

    getFrameStorage() {
        return this.frameStorage;
    }

    private addChangeUrlListener()
    {
        this.dispatcher.on('change-url', (event: ChangeUrlEvent) => {
            let view = this.get(event.id);
            if(view.url !== event.url) {
                view.url = event.url;
                if(event.clearStorage) {
                    view.storage = [];
                }
                event.resolve({changed: true});
                return;
            }
            event.resolve({changed: false});
        });
    }

    private addExistsListener()
    {
        this.dispatcher.on('exists', (event: RemoveEvent) => {
            let view = this.get(event.id);
            event.resolve({
                exists: !!view
            });
        });
    }

    private addRemoveListener()
    {
        this.dispatcher.on('remove', (event: RemoveEvent) => {
            let view = this.get(event.id);
            if(view) {
                this.remove(view);
            }

            if(event.saveState) {
                this.dispatcher.dispatch(new SaveStateEvent());
            }

            event.resolve();
        });
    }

    private addLoadedListener()
    {
        this.dispatcher.on('loaded', (event: LoadedEvent) => {
            let view = this.get(event.id);
            if(event.label) {
                view.label = event.label;
            }
            view.closeable = event.closeable;
            if(view) {
                view.finish();
            }
        });
    }

    private addLoadingListener()
    {
        this.dispatcher.on('loading', (event: LoadingEvent) => {
            let view = this.get(event.id);
            if(view) {
                view.loaded = false;
            }
        });
    }
    
    private addCloseListener()
    {
        this.dispatcher.on('close', (event: CloseEvent) => {
            let view = this.get(event.id);
            if(view) {
                // if view was not loaded, it can't receive close events, so we trigger remove event as fallback
                if(!view.loaded || view.closeable) {
                    this.dispatcher.dispatch(new RemoveEvent(view.id, event.saveState));
                    event.resolve();
                }
            }
        });
    }
    
    private addCreateListener()
    {
        this.dispatcher.on('create', (event: CreateEvent) => {
            let view = this.registry.getFactory(event.data.component).createFromData(event.data);
            view.id = this.generateId();
            this.push(view);
            event.resolve(view);
        });
    }

    private addArrangeListener()
    {
        this.dispatcher.on('arrange', (event: ArrangeEvent) => {
            this.arrange();
        });
    }

    private addClearListener()
    {
        this.dispatcher.on('clear', (event: ClearEvent) => {

            if(this.getViews().length == 0) {
                event.resolve();
                this.dispatcher.dispatch(new ClearedEvent(event.uuid));
                return;
            }

            let parallels = [];
            for(let view of this.getViews()) {
                parallels.push((callback: (err: any) => void) => {
                    this.dispatcher.dispatch(new CloseEvent(view.id, false))
                        .then(() => {
                            callback(null);
                        })
                        .catch(() => {
                            callback('reject');
                        })
                });
            }

            async.parallel(parallels, (err) => {
                if(err) {
                    event.reject();
                    this.dispatcher.dispatch(new SaveStateEvent());
                } else {
                    event.resolve();
                    this.dispatcher.dispatch(new ClearedEvent(event.uuid));
                }
            });
        });
    }

    private remove(view: ViewInterface)
    {
        /**
         * We can't delete views by splice or using Vue.delete, because the view can contain an iframe.
         * And because of reload iframe issues with vue and general in dom trees, it is best practise to delete only
         * the last element. Other elements will just hide until it is the last of the array.
         */
        view.removed = true;
        this.dispatcher.dispatch(new RemovedEvent(view.id));
        while(this.views.length > 0 && this.views[this.views.length-1].removed) {
            this.views.pop();
        }
    }

    private arrange()
    {
        this.arrangeManager.arrange(this.data.views);
    }

    private generateId(): number
    {
        let id = this.nextId;
        this.nextId++;
        return id;
    }
    
    get(id: number)
    {
        for(let view of this.getViews()) {
            if(view.id == id) {
                return view;
            }
        }
        return null;
    }

    getViews()
    {
        let views = [];
        for(let view of this.views) {
            if(!view.removed) {
                views.push(view);
            }
        }
        return views;
    }

    private push(view: ViewInterface)
    {
        this.views.push(view);
        for(let viewStackView of this.views) {
            viewStackView.focus = false;
        }
        view.focus = true;
    }

    hasViews()
    {
        return this.getViews().length > 0;
    }

    getDispatcher()
    {
        return this.dispatcher;
    }
}