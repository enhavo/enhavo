import {Frame} from "@enhavo/app/frame/Frame";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {reactive} from "vue";
export class FrameTestManager
{
    public frames: Frame[] = [];
    public currentFrame: Frame = null;

    constructor(
        private frameManager: FrameManager,
    ) {
    }


    async load()
    {
        this.frameManager.loaded();

        this.frameManager.getFrames().then((frames) => {
            this.frames = frames;
        });

        this.frameManager.getFrame().then((frame) => {
            if (frame) {
                this.currentFrame = frame;
                frame.label = frame.id;
            }
        });
    }
}
