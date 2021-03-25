import { ComponentAwareInterface } from "@enhavo/core/index";
import DataStorageEntry from "@enhavo/app/view-stack/DataStorageEntry";

export default interface ViewInterface extends ComponentAwareInterface
{
    id: number;
    label: string;
    children: ViewInterface[];
    parent: ViewInterface;
    loaded: boolean;
    width: string;
    minimize: boolean;
    focus: boolean;
    priority: number;
    removed: boolean;
    position: number;
    url: string;
    customMinimized: boolean;
    storage: DataStorageEntry[];
    closeable: boolean;

    finish(): void;
}