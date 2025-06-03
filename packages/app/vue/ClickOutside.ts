import { VNode, DirectiveBinding } from "vue"
import {FrameManager} from "../frame/FrameManager";

export class ClickOutside
{
    private static clickHandlers: ClickHandler[] = [];
    private static frameManager: FrameManager

    constructor(
        frameManager: FrameManager
    )
    {
        ClickOutside.frameManager = frameManager;
    }

    created(el: HTMLElement, binding: DirectiveBinding, vnode: VNode, oldVnode: VNode)
    {
        let clickHandler = new ClickHandler();
        let executed = false;
        clickHandler.subscriber = ClickOutside.frameManager.on('click', (event) => {
            const currentId = ClickOutside.frameManager.getId() === '' ? null : ClickOutside.frameManager.getId();
            if (currentId != event.origin) {
                if (!executed) {
                    binding.value();
                    executed = true;
                    setTimeout(() => {
                        executed = false;
                    },100);
                }
            }
        });

        clickHandler.element = el;
        clickHandler.handler = (event: Event) => {
            if (event.target == el) {
                return;
            }

            let parentElement = (<HTMLElement>event.target).parentElement;
            while (parentElement != el && parentElement != null) {
                parentElement = parentElement.parentElement;
                if (parentElement == null) {
                    if (!executed) {
                        binding.value();
                        executed = true;
                        setTimeout(() => {
                            executed = false;
                        },100);
                    }
                    return;
                }
            }
        }
        ClickOutside.clickHandlers.push(clickHandler);
        document.addEventListener('click', clickHandler.handler);
    }

    unmounted(el: HTMLElement, binding: DirectiveBinding, vnode: VNode, oldVnode: VNode)
    {
        for (let clickHandler of ClickOutside.clickHandlers) {
            if (clickHandler.element == el) {
                document.removeEventListener('click', clickHandler.handler);
                ClickOutside.frameManager.removeSubscriber(clickHandler.subscriber);
            }
        }
    }
}

class ClickHandler {
    public element: any;
    public handler: any;
    public subscriber: any;
}
