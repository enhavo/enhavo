import {AbstractMenuItem} from "@enhavo/app/menu/model/AbstractMenuItem";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {FrameStack} from "@enhavo/app/frame/FrameStack";

export class BaseMenuItem extends AbstractMenuItem
{
    public url: string;
    public mainUrl: string;
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
}
