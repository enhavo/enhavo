import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import {Frame} from "@enhavo/app/frame/Frame";
import {FormEventDispatcher} from "@enhavo/vue-form/form/FormEventDispatcher";

export class PreviewAction extends AbstractAction
{
    public url: string;
    public apiUrl: string

    private abortController: AbortController = null;
    private timeoutId: number;

    constructor(
        private readonly frameManager: FrameManager,
        private readonly uiManager: UiManager,
        private readonly resourceInputManager: ResourceInputManager,
        private readonly formEventDispatcher: FormEventDispatcher,
    ) {
        super();
        this.component = 'action-preview';
    }

    async execute(): Promise<void>
    {
        let values = await Promise.all([this.getPreviewData(), this.openPreviewFrame()]);

        let data = values[0] as string;
        let frame = values[1] as Frame;

        await this.frameManager.request(new PreviewData(frame.id, data))

        await this.subscribe();
    }

    async mounted()
    {
        let frame = await this.getPreviewFrame();

        if (frame) {
            let values = await Promise.all([this.getPreviewData(),  (async() => {
                await this.frameManager.wait(frame);
            })()]);

            let data = values[0] as string;
            await this.frameManager.request(new PreviewData(frame.id, data))

            await this.subscribe();
        }
    }

    async getPreviewData(): Promise<string>
    {
        if (this.abortController !== null) {
            this.abortController.abort();
        }

        this.abortController = new AbortController();
        try {
            const response = await this.resourceInputManager.sendForm(this.apiUrl, this.abortController.signal);
            this.abortController = null;
            return await response.text();
        } catch (error) {
            return null;
        }
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
                    let data = await this.getPreviewData();
                    if (data !== null) {
                        await this.frameManager.request(new PreviewData(frame.id, data))
                    }
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
        public data: any,
    ) {
        super('preview-data');
    }
}
