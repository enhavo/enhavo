export default class Data
{
    view: string = 'thumbnail';
    activeTag: Tag = null;
    activeContentType: ContentType = null;
    page: number = 1;
    limit: number = null;
    sortColumn: Column = null;
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
    columns: Column[] = [];
}

export class File
{
    id: number;
    previewImageUrl: string;
    label: string;
    suffix: string;
    type: string;
    icon: string = null;
    date: string;
    selected: boolean = false;
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

export class Column {
    property: string;
    label: string;
    direction: string = 'asc';
}
