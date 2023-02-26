import UploadExtension from "@enhavo/media/UploadExtension";

export default interface MediaConfig
{
    multiple: boolean;
    sortable: boolean;
    extensions: Array<UploadExtension>;
    upload: boolean;
    edit: boolean;
    prototypeName: string;
}