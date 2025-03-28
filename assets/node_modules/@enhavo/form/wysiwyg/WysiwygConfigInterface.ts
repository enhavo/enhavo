import {Extensions} from "@tiptap/core";
import {WysiwygMenuGroup} from "@enhavo/form/wysiwyg/menu/WysiwygMenuGroup";

export interface WysiwygConfigInterface
{
    name: string;
    extensions: Extensions;
    menu: WysiwygMenuGroup[];
}
