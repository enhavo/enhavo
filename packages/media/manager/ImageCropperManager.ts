import {ActionManager} from "@enhavo/app/action/ActionManager";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {Translator} from "@enhavo/app/translation/Translator";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {Router} from "@enhavo/app/routing/Router";
import Cropper from "cropperjs";

export class ImageCropperManager
{
    public actions: ActionInterface[];
    public actionsSecondary: ActionInterface[];
    public loading = true;

    public format: FormatData;

    private token: string;
    private cropper: Cropper;
    private initialDataSetEvent: boolean = null;
    private saveUrl;

    constructor(
        private readonly frameManager: FrameManager,
        private readonly actionManager: ActionManager,
        private readonly flashMessenger: FlashMessenger,
        private readonly translator: Translator,
        private readonly router: Router,
    )
    {
    }

    public async load(route: string, token: string, format: string)
    {
        let url = this.router.generate(route, {
            token: token,
            format: format,
        });

        this.saveUrl = url;

        const response = await fetch(url);
        const data = await response.json();

        this.format = Object.assign(new FormatData(), data.format);
        this.token =  data.token;
        this.actions = this.actionManager.createActions(data.actions);
        this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);

        this.frameManager.loaded();
    }

    public initCropper(element: HTMLImageElement)
    {
        this.cropper = new Cropper(element, {
            ready: () => {
                this.initialDataSetEvent = true;
                if (this.format.ratio) {
                    this.cropper.setAspectRatio(this.format.ratio);
                }
                if (this.format.getData()) {
                    this.cropper.setData(this.format.getData());
                }
                this.loading = false;
            }
        });
    }

    public async crop()
    {
        this.loading = true;

        let cropperData = this.cropper.getData();

        let changed = (value1: number, value2: number) => {
            return Math.abs(value1 - value2) > 0.00001;
        };

        if (changed(this.format.height, cropperData.height)) {
            this.format.changed = true;
            this.format.height = cropperData.height;
        }

        if (changed(this.format.width, cropperData.width)) {
            this.format.changed = true;
            this.format.width = cropperData.width;
        }

        if (changed(this.format.x, cropperData.x)) {
            this.format.changed = true;
            this.format.x = cropperData.x;
        }

        if (changed(this.format.y, cropperData.y)) {
            this.format.changed = true;
            this.format.y = cropperData.y;
        }

        if (this.initialDataSetEvent) {
            this.initialDataSetEvent = false;
            this.format.changed = false;
        }

        const response = await fetch(this.saveUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                height: this.format.height,
                width: this.format.width,
                x: this.format.x,
                y: this.format.y,
                token: this.token,
            }),
        });

        this.loading = false;

        if (response.ok) {
            this.flashMessenger.success(this.translator.trans('enhavo_app.save.message.save', [], 'javascript'));
        } else {
            let json = await response.json()
            if (json.message) {
                this.flashMessenger.error(json.message);
            } else {
                this.flashMessenger.error(this.translator.trans('enhavo_app.save.message.error', [], 'javascript'));
            }
        }
    }

    public async zoomIn()
    {
        this.cropper.zoom(0.1);
    }

    public async zoomOut()
    {
        this.cropper.zoom(-0.1);
    }

    public async reset()
    {
        this.cropper.reset();
    }
}

export class FormatData
{
    url: string;
    x: number;
    y: number;
    width: number;
    height: number;
    ratio: number;
    rotate: number = 0;
    scaleX: number = 1;
    scaleY: number = 1;
    changed: boolean = false;

    getData() {
        if (this.x !== null && this.y !== null && this.width !== null && this.height !== null) {
            return {
                x: this.x,
                y: this.y,
                width: this.width,
                height: this.height,
                scaleX: this.scaleX,
                scaleY: this.scaleY,
                rotate: this.rotate,
            }
        }
        return null;
    }
}
