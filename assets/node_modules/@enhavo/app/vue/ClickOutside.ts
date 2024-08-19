import { VNode } from "vue"
import { DirectiveBinding } from "vue/types/options";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import ClickEvent from "@enhavo/app/view-stack/event/ClickEvent";
import View from "@enhavo/app/view/View";

export class ClickOutside
{
    private static view: View;
    private static eventDispatcher: EventDispatcher;
    private static clickHandlers: ClickHandler[] = [];

    constructor(eventDispatcher: EventDispatcher, view: View)
    {
        ClickOutside.eventDispatcher = eventDispatcher;
        ClickOutside.view = view;
    }

    created(el: HTMLElement, binding: DirectiveBinding, vnode: VNode, oldVnode: VNode)
    {
        let clickHandler = new ClickHandler();
        let executed = false;
        clickHandler.subscriber = ClickOutside.eventDispatcher.on('click', (event: ClickEvent) => {
            if(ClickOutside.view.getId() != event.id) {
                if(!executed) {
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
            event.stopPropagation();
            if (event.target == el) {
                return;
            }

            let parentElement = (<HTMLElement>event.target).parentElement;
            while (parentElement != el) {
                parentElement = parentElement.parentElement;
                if (parentElement == null) {
                    if(!executed) {
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
        for(let clickHandler of ClickOutside.clickHandlers) {
            if(clickHandler.element == el) {
                document.removeEventListener('click', clickHandler.handler);
                ClickOutside.eventDispatcher.remove(clickHandler.subscriber);
            }
        }
    }
}

class ClickHandler {
    public element: any;
    public handler: any;
    public subscriber: any;
}
