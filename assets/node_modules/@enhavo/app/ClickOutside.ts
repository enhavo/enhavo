import { DirectiveOptions, VNode } from "vue"
import { DirectiveBinding } from "vue/types/options";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import ClickEvent from "@enhavo/app/ViewStack/Event/ClickEvent";
import View from "@enhavo/app/View/View";

export default class ClickOutside implements DirectiveOptions
{
    private static view: View;
    private static eventDispatcher: EventDispatcher;

    constructor(eventDispatcher: EventDispatcher, view: View)
    {
        ClickOutside.eventDispatcher = eventDispatcher;
        ClickOutside.view = view;
    }

    bind(el: HTMLElement, binding: DirectiveBinding, vnode: VNode, oldVnode: VNode)
    {
        ClickOutside.eventDispatcher.on('click', (event: ClickEvent) => {
           if(ClickOutside.view.getId() != event.id) {
               binding.value();
           }
        });

        document.addEventListener('click', (event: Event) => {
            event.stopPropagation();
            if(event.target == el) {
                return;
            }

            let parentElement = (<HTMLElement>event.target).parentElement;
            while(parentElement != el) {
                parentElement = parentElement.parentElement;
                if(parentElement == null) {
                    binding.value();
                    return;
                }
            }
        });
    }
}