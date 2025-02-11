import axios, {CancelTokenSource} from "axios";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";
import {Router} from "@enhavo/app/routing/Router";
import {Translator} from "@enhavo/app/translation/Translator";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {RouteContainer} from "@enhavo/app/routing/RouteContainer";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {FilterInterface} from "@enhavo/app/filter/FilterInterface";
import {ColumnInterface} from "@enhavo/app/column/ColumnInterface";
import {BatchInterface} from "@enhavo/app/batch/BatchInterface";
import {CollectionInterface} from "@enhavo/app/collection/CollectionInterface";
import {ActionManager} from "@enhavo/app/action/ActionManager";
import {FilterManager} from "@enhavo/app/filter/FilterManager";
import {ColumnManager} from "@enhavo/app/column/ColumnManager";
import {BatchManager} from "@enhavo/app/batch/BatchManager";
import {CollectionFactory} from "@enhavo/app/collection/CollectionFactory";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {MediaFileSelectEvent} from "@enhavo/media-library/event/MediaFileSelectEvent";
import {MediaLibraryCollection} from "@enhavo/media-library/collection/MediaLibraryCollection";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import $ from "jquery";

export class MediaLibraryManager
{
    public mode: string;
    public loading: boolean = true;

    public token: string;
    public uploadUrl: string;
    public selectUrl: string;
    public maxUploadSize: number;

    public dragOver: boolean = false;
    public uploadElement: HTMLElement
    public uploads: FileUpload[] = [];
    public highlight: boolean = false;

    public actions: ActionInterface[];
    public actionsSecondary: ActionInterface[];
    public filters: FilterInterface[];
    public columns: ColumnInterface[];
    public batches: BatchInterface[];
    public collection: CollectionInterface;
    public routes: RouteContainer;
    
    public constructor(
        private readonly eventDispatcher: FrameEventDispatcher,
        private readonly frameManager: FrameManager,
        private readonly flashMessenger: FlashMessenger,
        private readonly router: Router,
        private readonly translator: Translator,
        private readonly actionManager: ActionManager,
        private readonly filterManager: FilterManager,
        private readonly columnManager: ColumnManager,
        private readonly batchManager: BatchManager,
        private readonly collectionFactory: CollectionFactory,
        private readonly uiManager: UiManager,
    ) {
    }

    public async load(route: string, parameters: object = {})
    {
        let url = this.router.generate(route, parameters);

        const response = await fetch(url);

        if (!response.ok) {
            this.frameManager.loaded();
            this.uiManager.alert({ message: 'Error occurred' }).then(() => {
                this.frameManager.close(true);
            });
            return;
        }

        const data = await response.json();

        this.uploadUrl = data.uploadUrl;
        this.selectUrl = data.selectUrl;
        this.token = data.token;
        this.maxUploadSize = data.maxUploadSize;
        this.actions = this.actionManager.createActions(data.actions);
        this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);
        this.filters = this.filterManager.createFilters(data.filters);
        this.columns = this.columnManager.createColumns(data.columns);
        this.batches = this.batchManager.createBatches(data.batches);
        this.routes = new RouteContainer(data.routes);
        this.collection = this.collectionFactory.create(data.collection.model, data.collection, this.filters, this.columns, this.batches, this.routes);
        this.collection.init();

        this.loading = false;
        this.frameManager.loaded();
    }

    addListener()
    {
        $(window).on('dragenter dragover', () => {
            this.highlight = true;
        });

        $(window).on('dragleave', () => {
            this.highlight = false;
        });
    }

    change(event: any)
    {
        this.uploadFiles(event.target.files).then(() => {
            $(this.uploadElement).val('');
        });
    }

    drop(event: any)
    {
        this.dragOver = false;
        this.highlight = false;

        this.uploadFiles(event.dataTransfer.files);
    }

    dragover()
    {
        this.dragOver = true
    }

    dragleave()
    {
        this.dragOver = false
    }

    dragdrop()
    {
        this.dragOver = false;
        this.highlight = false;
    }

    private uploadFiles(files: []): Promise<void>
    {
        this.flashMessenger.notice('Upload '+files.length+' files');

        let uploads = [];

        for (let file of files) {
            uploads.push(this.uploadFile(file));
        }

        return new Promise((resolve) => {
            Promise.all(uploads).then((values) => {
                let uploadedFiles = 0;
                for (let value of values) {
                    if (value)  {
                        uploadedFiles++;
                    }
                }
                if (uploadedFiles > 0) {
                    this.flashMessenger.success(uploadedFiles +' Files uploaded');
                    this.collection.load();
                    this.dispatchCollectionUpdate();
                }
                resolve();
            });
        })
    }

    private uploadFile(file: File): Promise<boolean>
    {
        return new Promise((resolve) => {
            if (!this.checkFile(file)) {
                resolve(false);
                return;
            }

            const CancelToken = axios.CancelToken;
            const source = CancelToken.source();

            let fileUpload = new FileUpload();
            fileUpload.name = file.name;
            fileUpload.total = file.size;
            fileUpload.source = source;
            this.uploads.push(fileUpload);
            fileUpload = this.uploads[this.uploads.indexOf(fileUpload)]; // make reactive

            let data = new FormData();
            data.append('files', file);

            axios.post(this.uploadUrl, data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: (progressEvent) => {
                    fileUpload.progress = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                    fileUpload.loaded = progressEvent.loaded;
                },
                cancelToken: source.token
            })
            .then((response) => {
                this.uploads.splice(this.uploads.indexOf(fileUpload), 1);
                resolve(true);
            })
            .catch((error) => {
                this.uploads.splice(this.uploads.indexOf(fileUpload), 1);
                if (axios.isCancel(error)) {
                    resolve(false); // no error
                } else {
                    if (error.response.status === 400 && error.response.data.success === false) {
                        for (let message of error.response.data.errors) {
                            this.flashMessenger.error(message)
                        }
                    } else {
                        this.flashMessenger.error('An error occurred')
                        console.error(error);
                        resolve(false);
                    }
                }
            });
        });
    }

    private checkFile(file: File): boolean
    {
        if (file.size > this.maxUploadSize) {
            this.flashMessenger.error('File size is to large. Max allowed is ' + this.formatBytes(this.maxUploadSize))
            return false;
        }

        return true;
    }

    private formatBytes(bytes: number, decimals = 2): string
    {
        if (!+bytes)  {
            return '0 Bytes'
        }

        const k = 1024
        const dm = decimals < 0 ? 0 : decimals
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']

        const i = Math.floor(Math.log(bytes) / Math.log(k))

        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
    }

    public async select()
    {
        this.uiManager.loading(true);

        const ids = this.collection.getIds()

        const response = await fetch(this.selectUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                ids: ids,
                token: this.token
            }),
        });

        this.uiManager.loading(false);

        if (!response.ok) {
            this.uiManager.alert({ message: 'Error occurred' });
            return;
        }

        const data = await response.json();

        let frame = await this.frameManager.getFrame()
        this.frameManager.dispatch(new MediaFileSelectEvent(data.files, frame.parameters['fullName'], frame.parent));
        (this.collection as MediaLibraryCollection).resetIds();

        this.frameManager.close(true);
    }

    public dispatchCollectionUpdate()
    {
        this.frameManager.dispatch(new UpdateMediaCollectionEvent());
    }
}

export class FileUpload
{
    name: string;
    progress: number = 0;
    loaded: number;
    total: number;
    source: CancelTokenSource

    cancel()
    {
        this.source.cancel();
    }
}

export class UpdateMediaCollectionEvent extends Event
{
    constructor() {
        super('update_media_collection');
    }
}
