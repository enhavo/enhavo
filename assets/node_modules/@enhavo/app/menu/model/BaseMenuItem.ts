import {AbstractMenuItem} from "@enhavo/app/menu/model/AbstractMenuItem";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {FrameStack} from "@enhavo/app/frame/FrameStack";
import {FrameUtil} from "@enhavo/app/frame/FrameUtil";
import {Frame} from "@enhavo/app/frame/Frame";

export class BaseMenuItem extends AbstractMenuItem
{
    public url: string;
    public clickable: boolean = true;
    public frame: object;
    public clear: boolean;

    constructor(
        private frameStack: FrameStack,
        private frameManager: FrameManager,
    ) {
        super();
    }

    async open(): Promise<void>
    {
        let success = true;
        if (this.clear) {
            success = await this.frameManager.clearFrames();
        }

        if (success) {
            let options = this.frame !== null && typeof this.frame == 'object' ? this.frame : {};
            Object.assign(options, {
                url: this.url,
            })
            await this.frameManager.addFrame(options);
        }

        this.frameManager.save();
        this.frameManager.arrange();
    }

    isActive(): boolean
    {
        for (let frame of this.frameStack.getFrames()) {
            if (frame.url === this.url) {
                return true;
            }
        }
        return false;
    }

    getMainUrl(): string
    {
        let options = this.frame !== null && typeof this.frame == 'object' ? this.frame : {};
        Object.assign(options, {
            url: this.url,
        })
        let frame = new Frame(options);


        let url = new URL(window.location.href);
        if (url.searchParams.has('frames')) {
            url.searchParams.delete('frames');
        }
        url.searchParams.set('frames', FrameUtil.getState([frame]));
        return url.toString();
    }
}
