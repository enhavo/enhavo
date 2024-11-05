import {FrameStack} from "@enhavo/app/frame/FrameStack";
import {Frame} from "@enhavo/app/frame/Frame";
import {MenuManager} from "@enhavo/app/menu/MenuManager";

export class FrameArrangeManager
{
    constructor(
        private frameStack: FrameStack,
        private menuManager: MenuManager,
    ) {
    }
    
    arrange()
    {
        let frames = this.getFrames()

        if (!this.menuManager.isCustomChange()) {
            if (frames.length >= 2) {
                this.menuManager.close();
            } else if(frames.length <= 2) {
                this.menuManager.open();
            }
        }

        let hasFocus = false;
        for (let frame of frames) {
            if (frame.focus == true) {
                hasFocus = true;
            }
        }

        if (!hasFocus) {
            if (frames.length) {
                frames[frames.length - 1].focus = true;
            }
        }

        this.setSize(frames);
        this.setPosition(frames);
    }

    private setSize(frames: Frame[])
    {
        this.setMinimized(frames);

        if (frames.length === 0) {
            return;
        }

        if (!frames[0].minimize) {
            if (frames.length === 1) {
                frames[0].width = 100;
            } else {
                let otherFrameMaximized = false;
                for (let index = 1; index < frames.length; index++) {
                    if (!frames[index].minimize) {
                        otherFrameMaximized = true;
                        break;
                    }
                }
                if (otherFrameMaximized) {
                    frames[0].width = 30;
                } else {
                    frames[0].width = 100;
                }
            }
        }
    }

    private setMinimized(frames: Frame[])
    {
        if (frames.length === 0) {
            return;
        }

        // If any frame is keepMinimized, no automatic minimizing
        for (let frame of frames) {
            if (frame.keepMinimized) {
                return;
            }
        }

        // Last two maximized, all others minimized
        let numMaximized = 2;
        for (let index = frames.length - 1; index >= 0; index--) {
            if (numMaximized > 0) {
                frames[index].minimize = false;
                numMaximized--;
            } else {
                frames[index].minimize = true;
            }
        }
    }

    private setPosition(frames: Frame[])
    {
        for (let frame of frames) {
            frame.position = null;
        }

        let position = 0;
        for (let frame of frames) {
            if (frame.minimize) {
                frame.position = position++;
            }
        }

        for (let frame of frames) {
            if (!frame.minimize) {
                if (frame.position == null) {
                    frame.position = position++;
                }

                let children = this.frameStack.getFrames(frame.id);
                for (let child of children) {
                    if (!child.minimize) {
                        child.position = position++;
                    }
                }
            }
        }
    }

    private getFrames(): Frame[]
    {
        let frames = [];
        for (let frame of this.frameStack.getFrames()) {
            if (frame.parent == null) {
                frames.push(frame);
                for (let descendant of this.getFrameDescendants(frame)) {
                    frames.push(descendant);
                }
            }
        }
        return frames;
    }

    private getFrameDescendants(parent: Frame): Frame[]
    {
        let frames = [];
        for (let frame of this.frameStack.getFrames()) {
            if (parent.id === frame.parent) {
                frames.push(frame)
                for (let descendant of this.getFrameDescendants(frame)) {
                    frames.push(descendant);
                }
            }
        }
        return frames;
    }
}
