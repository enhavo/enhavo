import {Form} from "@enhavo/vue-form/model/Form";
import { Editor } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'

export class WysiwygForm extends Form
{
    public editor: Editor = null;

    initEditor()
    {
        if (this.editor !== null) {
            return;
        }

        this.editor = new Editor({
            content: this.value,
            extensions: [StarterKit],
            onUpdate: ({ editor }) => {
                this.value = editor.getHTML()
            },
        });
    }

    destroyEditor()
    {
        if (this.editor) {
            this.editor.destroy();
            this.editor = null;
        }
    }

    update(recursive: boolean = true)
    {
        super.update(recursive);
        this.destroyEditor();
        this.initEditor();
    }
}
