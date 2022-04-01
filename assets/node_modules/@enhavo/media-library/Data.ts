export default class Data
{
    activeTag: Tag = null;
    activeContentType: ContentType = null;
    activePage: number = 1;
    searchString: string = '';
    files: File[] = [];
    loading: boolean = false;
    selectedFiles: File[] = [];
    multiple: boolean;
    mode: string;
    contentTypes: ContentType[];
    tags: Tag[];
    pages: number[] = [];
    progress: number = 0;
    dropZone: boolean = false;
    dropZoneActive: boolean = false;
}

export class File
{
    id: number;
    previewImageUrl: string;
    label: string;
    selected: boolean = false;
    icon: string = null;
}

export class ContentType
{
    key: string;
    label: string;
    selected: boolean;
}

export class Tag {
    id: string;
    label: string;
    selected: boolean;
}
