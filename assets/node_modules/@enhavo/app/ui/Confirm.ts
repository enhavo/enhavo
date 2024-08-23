import View from "@enhavo/app/view/View";

export default class Confirm
{
    public message: string;
    public denyText: string;
    public acceptText: string;
    public onDeny: () => void;
    public onAccept: () => void;
    public view: View;

    constructor(message: string, onAccept?: () => void, onDeny?: () => void, denyText: string = 'deny', acceptText: string = 'accept')
    {
        this.message = message;
        this.onAccept = onAccept;
        this.onDeny = onDeny;
        this.denyText = denyText;
        this.acceptText = acceptText;
    }

    public setView(view: View)
    {
        this.view = view;
    }

    public deny()
    {
        this.view.confirm(null);
        if(this.onDeny) {
            this.onDeny();
        }
    }

    public accept()
    {
        this.view.confirm(null);
        if(this.onAccept) {
            this.onAccept();
        }
    }
}