import DataLoader from '@enhavo/app/DataLoader';
import AbstractViewApp from "@enhavo/app/AbstractViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import * as $ from "jquery";

export default class LoginApp extends AbstractViewApp
{
    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View)
    {
        super(loader, eventDispatcher, view);

        this.view.addDefaultCloseListener();

        $(() => {
            this.view.ready();
        });

        $('form').on('submit', () => {
            this.view.exit();
        });
    }
}