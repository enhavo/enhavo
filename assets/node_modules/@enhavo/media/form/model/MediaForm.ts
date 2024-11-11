import $ from "jquery";
import * as async from "async";
import axios, {CancelTokenSource} from "axios";
import {ListForm} from "@enhavo/form/form/model/ListForm";
import {Form} from "@enhavo/vue-form/model/Form";
import {DeleteEvent} from "@enhavo/form/form/event/DeleteEvent";
import {ChangeEvent} from "@enhavo/vue-form/event/ChangeEvent";
import {MediaItemForm} from "@enhavo/media/form/model/MediaItemForm";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {ActionManager} from "@enhavo/app/action/ActionManager";
import {ActionMediaInterface} from "@enhavo/media/action/ActionMediaInterface";
import {File as MediaFile} from "@enhavo/media/model/File";

export class MediaForm extends ListForm
{
    upload: boolean;
    uploadUrl: string;
    uploadLabel: string;
    loading: boolean = false;
    buttons: MediaFormButton[];
    maxUploadSize: number;
    multiple: boolean;
    sortable: boolean;
    editable: boolean;
    progress: number = 10;
    dragOver: boolean;
    highlight: boolean;
    uploads: FileUpload[] = [];
    fileErrors: FileError[] = [];
    actions: ActionInterface[] = [];

    private _actions: ActionInterface[] = null;

    constructor(
        formFactory: FormFactory,
        private readonly actionManager: ActionManager,
    ) {
        super(formFactory);
    }

    startUpload()
    {
        $(this.element).trigger('click');
    }

    change(event: any)
    {
        this.uploadFiles(event.target.files).then(() => {
            $(this.element).val('');
        });
    }

    drop(event: any)
    {
        this.dragOver = false;
        this.highlight = false;

        this.uploadFiles(event.dataTransfer.files).then(() => {
            this.loading = false;
        }).catch(() => {
            this.loading = false;
        });
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

    init() {
        super.init();

        // overwrite arrays, because this object get cloned before, and we want to prevent, that media objects share the same array
        this.uploads = [];
        this.fileErrors = [];
        this.buttons = [];

        $(window).on('dragenter dragover', () => {
            this.highlight = true;
        });

        $(window).on('dragleave', () => {
            this.highlight = false;
        });
    }
    getActions()
    {
        if (this._actions !== null) {
            return this._actions
        }

        this._actions =  this.actionManager.createActions(this.actions);
        for (let action of this._actions) {
            (action as ActionMediaInterface).form = this;
        }
        return this._actions;
    }

    private uploadFiles(files: any): Promise<void>
    {
        return new Promise((resolve, reject) => {
            let callbacks: ((callback: any) => void)[] = [];

            if (!this.multiple) {
                files = [files[files.length - 1]];
            }

            for (let file of files) {
                if (this.checkFile(file)) {
                    callbacks.push(this.uploadFile(file));
                }
            }

            async.parallel(callbacks, (err) => {
                if (err) {
                    reject(err);
                    return;
                }
                resolve();
            });
        });
    }

    private uploadFile(file: File): (callback: any) => void
    {
        return (callback) => {

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
                    for (let file of response.data) {
                        this.addFile(file);
                    }

                    callback();
                })
                .catch((error) => {
                    this.uploads.splice(this.uploads.indexOf(fileUpload), 1);
                    if (axios.isCancel(error)) {
                        callback(); // no error
                    } else {
                        this.addError('An error occurred');
                        console.error(error)
                        callback(null, error)
                    }
                });
        }
    }

    public addFile(file: MediaFile)
    {
        let updated = false;

        if (!this.multiple && this.children.length > 0) {
            for (let child of this.children) {
                this.deleteItem(child);
                updated  = true;
            }
        }

        let item = <MediaItemForm>this.addItem();
        for (const property in file) {
            if (file.hasOwnProperty(property)) {
                if (item.has(property)) {
                    item.get(property).value = file[property];
                }
            }
        }

        item.file = file;

        this.eventDispatcher.dispatchEvent(new ChangeEvent(this, {
            action: updated ? 'update' : 'create',
            item: item,
            origin: this
        }), 'change');

    }

    protected checkFile(file: File): boolean
    {
        if (file.size > this.maxUploadSize) {
            this.addError('File size is to large. Max allowed is ' + this.formatBytes(this.maxUploadSize));
            return false;
        }

        return true;
    }

    public addError(message: string)
    {
        let error = new FileError(message);
        this.fileErrors.push(error);
        window.setTimeout(() => {
            this.removeError(error);
        }, 5000);
    }

    public removeError(error: FileError)
    {
        if (this.fileErrors.indexOf(error) >= 0) {
            this.fileErrors.splice(this.fileErrors.indexOf(error), 1);
        }
    }

    private formatBytes(bytes: number, decimals = 2)
    {
        if (!+bytes) return '0 Bytes'

        const k = 1024
        const dm = decimals < 0 ? 0 : decimals
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']

        const i = Math.floor(Math.log(bytes) / Math.log(k))

        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
    }

    public deleteFile(child: Form)
    {
        let event = new DeleteEvent(child);
        this.eventDispatcher.dispatchEvent(event, 'delete')

        if (!event.isStopped()) {
            this.eventDispatcher.dispatchEvent(new ChangeEvent(this, {
                action: 'delete',
                item: child,
                origin: this
            }), 'change');
            this.deleteItem(child);
        }
    }

    public deleteItem(child: Form)
    {
        this.eventDispatcher.stop();
        super.deleteItem(child);
        this.eventDispatcher.start();
    }

    public addItem(): Form
    {
        this.eventDispatcher.stop();
        let item = super.addItem();
        this.eventDispatcher.start();
        return item;
    }
}

export class MediaFormButton
{
    label: string;
    type: string;
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

export class FileError
{
    constructor(
        public message: string
    ) {
    }
}
