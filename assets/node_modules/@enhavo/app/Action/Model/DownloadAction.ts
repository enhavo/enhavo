import AbstractAction from "@enhavo/app/Action/Model/AbstractAction";
import * as $ from "jquery";

export default class DuplicateAction extends AbstractAction
{
    public url: string;

    execute(): void
    {
        $.ajax({
            type : 'post',
            url: this.url,
            data: $('form').serialize(),
            success: function(data) {
                let element = document.createElement('a');
                element.setAttribute('href', 'data:application/octet-stream;base64,' + data.data);
                element.setAttribute('download', data.filename);
                element.style.display = 'none';
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
            }
        });
    }
}