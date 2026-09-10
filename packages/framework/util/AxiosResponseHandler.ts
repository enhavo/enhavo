import {AxiosResponse} from 'axios';

export class AxiosResponseHandler
{
    public static download(response: AxiosResponse<any>)
    {
        if (typeof response.data !== 'object' || response.request.responseType !== 'arraybuffer') {
            throw new Error('Response type has to be an arraybuffer');
        }

        let filename = AxiosResponseHandler.getFilename(response.headers['content-disposition']);
        let contentType = response.headers['content-type'];

        const blob = new Blob([response.data], {
            type: contentType,
        });

        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }

    public static getBody(response: AxiosResponse<any>): string
    {
        if (typeof response.data === 'string') {
            return response.data;
        } else if (typeof response.data === 'object' && response.request.responseType === 'arraybuffer') {
            return (new TextDecoder("utf-8")).decode(response.data);
        } else if (typeof response.data === 'object' && response.request.responseType === 'json') {
            return JSON.parse(response.data);
        }

        throw new Error('Unknown response type');
    }

    private static getFilename(contentDisposition: string): string|null
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
