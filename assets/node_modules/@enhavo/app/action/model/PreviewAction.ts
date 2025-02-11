import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import {Frame} from "@enhavo/app/frame/Frame";
import {FormEventDispatcher} from "@enhavo/vue-form/form/FormEventDispatcher";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";

export class PreviewAction extends AbstractAction
{
    public url: string;
    public apiUrl: string
    public selectors: string[]
    public forceReload: boolean

    private timeoutId: number;

    constructor(
        private readonly frameManager: FrameManager,
        private readonly resourceInputManager: ResourceInputManager,
        private readonly formEventDispatcher: FormEventDispatcher,
    ) {
        super();
        this.component = 'action-preview';
    }

    async execute(): Promise<void>
    {
        let frame = await this.openPreviewFrame();

        await this.frameManager.request(new PreviewData(frame.id, this.getFormData(), this.apiUrl, this.selectors, true))

        await this.subscribe();
    }

    async mounted()
    {
        let frame = await this.getPreviewFrame();

        if (frame) {
            await this.frameManager.request(new PreviewData(frame.id, this.getFormData(), this.apiUrl, this.selectors, this.forceReload))
            await this.subscribe();
        }
    }

    getFormData(): string
    {
        // @ts-ignore URLSearchParams also accepts FormData
        return (new URLSearchParams(FormUtil.serializeForm(this.resourceInputManager.form))).toString();
    }

    async openPreviewFrame(): Promise<Frame>
    {
        let frame = await this.getPreviewFrame();
        if (frame) {
            await this.frameManager.wait(frame);
        } else {
            frame = await this.frameManager.openFrame({
                url: this.url,
                key: 'preview',
            });
        }
        return frame;
    }

    async getPreviewFrame(): Promise<Frame>
    {
        let frames = await this.frameManager.getFrames()
        for (let frame of frames) {
            if (frame.parent == this.frameManager.getId() && frame.key === 'preview') {
                return frame;
            }
        }
        return null;
    }

    private async subscribe()
    {
        let currentFrame = await this.frameManager.getFrame();
        await this.frameManager.wait(currentFrame);

        let frame = await this.getPreviewFrame();
        if (this.resourceInputManager.form && frame) {
            this.formEventDispatcher.on('change', async () => {

                if (this.timeoutId) {
                    window.clearTimeout(this.timeoutId);
                }

                this.timeoutId = window.setTimeout(async () => {
                    await this.frameManager.request(new PreviewData(frame.id, this.getFormData(), this.apiUrl, this.selectors, this.forceReload))
                    this.timeoutId = null;
                }, 500);
            });
        }
    }
}

export class PreviewData extends Event
{
    constructor(
        public target: string,
        public formData: any,
        public url: string,
        public selectors: string[],
        public forceReload: boolean = false,
    ) {
        super('preview-data');
    }
}
