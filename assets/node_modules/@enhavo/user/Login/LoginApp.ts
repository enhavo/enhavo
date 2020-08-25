import View from "@enhavo/app/View/View";
import * as $ from "jquery";

export default class LoginApp
{
    private readonly view: View;

    constructor(view: View)
    {
        this.view = view;
    }

    init() {
        this.view.init();
        this.view.addDefaultCloseListener();

        $(() => {
            this.view.ready();
        });

        $('form').on('submit', () => {
            this.view.exit();
        });
    }
}
