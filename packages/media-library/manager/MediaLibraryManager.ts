import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {Router} from "@enhavo/framework/routing/Router";
import {Translator} from "@enhavo/framework/translation/Translator";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {RouteContainer} from "@enhavo/framework/routing/RouteContainer";
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
    public uploadElement: HTMLInputElement;
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
        this.frameManager.setLabel(this.translator.trans('enhavo_media_library.library.label.title', {}, 'javascript'));
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

    changeUpload(event: any)
    {
        this.upload(event.target.files, this.uploadUrl).then(() => {
            $(this.uploadElement).val('');
        });
    }

    upload(files: [], url: string): Promise
    {
        return this.uploadFiles(files, url);
    }

    replace(file: any, url: string): Promise
    {
        return this.uploadFiles([file], url);
    }

    drop(event: any)
    {
        this.dragOver = false;
        this.highlight = false;

        this.uploadFiles(event.dataTransfer.files, this.uploadUrl);
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

    private uploadFiles(files: [], url: string): Promise<void>
    {
        this.flashMessenger.notice('Uploading ' + files.length + ' files');

        let uploads = [];

        for (let file of files) {
            uploads.push(this.uploadFile(file, url));
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
                    this.flashMessenger.success(uploadedFiles + ' files uploaded');
                    this.dispatchCollectionUpdate();
                }
                resolve();
            });
        })
    }

    private uploadFile(file: File, url: string): Promise<boolean>
    {
        return new Promise((resolve) => {
            if (!this.checkFile(file)) {
                resolve(false);
                return;
            }

            // XMLHttpRequest instead of fetch, because fetch can't report upload progress
            const request = new XMLHttpRequest();

            let fileUpload = new FileUpload();
            fileUpload.name = file.name;
            fileUpload.total = file.size;
            fileUpload.request = request;
            this.uploads.push(fileUpload);
            fileUpload = this.uploads[this.uploads.indexOf(fileUpload)]; // make reactive

            let data = new FormData();
            data.append('files', file);

            request.upload.onprogress = (progressEvent) => {
                fileUpload.progress = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                fileUpload.loaded = progressEvent.loaded;
            };
            request.onload = () => {
                this.uploads.splice(this.uploads.indexOf(fileUpload), 1);
                if (request.status >= 200 && request.status < 300) {
                    resolve(true);
                } else if (request.status === 400 && request.response?.success === false) {
                    for (let message of request.response.errors) {
                        this.flashMessenger.error(message)
                    }
                    resolve(false);
                } else {
                    this.flashMessenger.error('An error occurred')
                    console.error(request.statusText);
                    resolve(false);
                }
            };
            request.onerror = () => {
                this.uploads.splice(this.uploads.indexOf(fileUpload), 1);
                this.flashMessenger.error('An error occurred')
                resolve(false);
            };
            request.onabort = () => {
                this.uploads.splice(this.uploads.indexOf(fileUpload), 1);
                resolve(false); // no error
            };
            request.open('POST', url);
            request.responseType = 'json';
            request.send(data);
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
    request: XMLHttpRequest;

    cancel()
    {
        this.request.abort();
    }
}

export class UpdateMediaCollectionEvent extends Event
{
    constructor()
    {
        super('update_media_collection');
    }
}
