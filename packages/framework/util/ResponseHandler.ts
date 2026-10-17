export class ResponseHandler
{
    public static async download(response: Response)
    {
        let filename = ResponseHandler.getFilename(response.headers.get('content-disposition'));
        let contentType = response.headers.get('content-type');

        const blob = new Blob([await response.arrayBuffer()], {
            type: contentType,
        });

        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }

    public static getBody(response: Response): Promise<string>
    {
        return response.text();
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
