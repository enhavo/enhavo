import AbstractAction from "@enhavo/app/action/model/AbstractAction";
import axios from 'axios';
import * as $ from 'jquery';

export default class DownloadAction extends AbstractAction
{
    public url: string;
    public ajax: boolean;

    execute(): void
    {
        if(this.ajax) {
            window.open(this.url, '_self');
            return;
        }

        axios.post(this.url, $('form').serialize(), {
            headers: {'Content-Type': 'multipart/form-data' },
            responseType: 'arraybuffer'
        })
        .then((response) => {
            let filename = this.getFilename(response.headers['content-disposition']);
            let contentType = response.headers['content-type'];

            const blob = new Blob([response.data], {
                type: contentType,
            });

            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;
            link.click();
        })
        .catch(function (response) {
            //handle error
            console.log(response);
        });
    }

    private getFilename(contentDisposition: string): string|null
    {
        let filename = null;

        let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
        let matches = filenameRegex.exec(contentDisposition);
        if (matches != null && matches[1]) {
            filename = matches[1].replace(/['"]/g, '');
        }
        return filename;
    }
}