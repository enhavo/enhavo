import {FrameStack} from "@enhavo/app/frame/FrameStack";
import {FrameUtil} from "@enhavo/app/frame/FrameUtil";
import {FrameArrangeManager} from "@enhavo/app/frame/FrameArrangeManager";
import {Frame} from "./Frame";
import {MenuManager} from "@enhavo/app/menu/MenuManager";

export class FrameStateManager
{
    constructor(
        private frameStack: FrameStack,
        private frameArrangeManager: FrameArrangeManager,
        private menuManager: MenuManager,
    )
    {
    }

    public subscribe()
    {
        if (window.name == "") {
            window.addEventListener("popstate",  async (event: any) => {
                let state = this.getStateFromUrl(window.location.href);
                if (state === null) {
                    await this.frameStack.clearFrames();
                    this.menuManager.start();
                } else {
                    await this.applyState(state);
                }
            });
        }
    }

    public async applyState(state: string)
    {
        let frames = FrameUtil.getFramesOption(state);
        await this.frameStack.updateFrames(frames);
        this.frameArrangeManager.arrange();
    }

    public saveState()
    {
        let frames = this.frameStack.getFrames();
        history.pushState(null, "", this.generateUrl(frames));
    }

    public async loadState()
    {
        let state = this.getStateFromUrl(window.location.href);
        if (state) {
            await this.applyState(state);
        } else {
            this.menuManager.start();
        }
    }

    private getStateFromUrl(value: string): string
    {
        let url = new URL(value);
        if (url.searchParams.has('frames')) {
            return url.searchParams.get('frames');
        }
        return null;
    }

    private generateUrl(frames: Frame[]): string
    {
        let state = FrameUtil.getState(frames);
        let url = new URL(window.location.href);
        url.searchParams.set('frames', state);
        return url.toString();
    }
}
