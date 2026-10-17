import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import {ResponseHandler} from "@enhavo/framework/util/ResponseHandler";
import $ from 'jquery';

export class DownloadAction extends AbstractAction
{
    public url: string;
    public ajax: boolean;

    execute(): void
    {
        if (!this.ajax) {
            var anchor = document.createElement('a');
            anchor.href = this.url;
            anchor.download = '';
            anchor.click();
            return;
        }

        fetch(this.url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: $('form').serialize(),
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return ResponseHandler.download(response);
        })
        .catch(function (response) {
            //handle error
            console.error(response);
        });
    }
}