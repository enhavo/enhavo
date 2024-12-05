import {Form} from "@enhavo/vue-form/model/Form";
import {WysiwygConfig} from "@enhavo/form/wysiwyg/WysiwygConfig";
import {WysiwygModalConfiguration} from "@enhavo/form/wysiwyg/WysiwygModalConfiguration";
import {Editor} from '@tiptap/vue-3';
import {Editor as CoreEditor} from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Underline from '@tiptap/extension-underline';
import Subscript from '@tiptap/extension-subscript';
import Superscript from '@tiptap/extension-superscript';
import TextAlign from '@tiptap/extension-text-align';
import SearchAndReplace from '@sereneinserenade/tiptap-search-and-replace';

export class WysiwygForm extends Form
{
    public editor: Editor = null;

    public editorBreadcrumbs: string = '';

    public configName: string = 'default';
    public config: WysiwygConfig = null;

    public modal: WysiwygModalConfiguration = null;

    public searchAndReplaceOpen: boolean = false;
    public searchAndReplaceSearchTerm: string = '';
    public searchAndReplaceReplaceTerm: string = '';

    initEditor()
    {
        if (this.editor !== null) {
            return;
        }

        this.editor = new Editor({
            content: this.value,
            extensions: [
                StarterKit,
                Link.configure({
                    openOnClick: false,
                    autolink: true,
                    defaultProtocol: 'https',
                    protocols: ['http', 'https'],
                    HTMLAttributes: {
                        rel: null,
                    },
                    isAllowedUri: (url, ctx) => {
                        try {
                            // construct URL
                            const parsedUrl = url.includes(':') ? new URL(url) : new URL(`${ctx.defaultProtocol}://${url}`);

                            // use default validation
                            if (!ctx.defaultValidate(parsedUrl.href)) {
                                return false;
                            }

                            // disallowed protocols
                            const disallowedProtocols = ['ftp', 'file', 'mailto'];
                            const protocol = parsedUrl.protocol.replace(':', '');

                            if (disallowedProtocols.includes(protocol)) {
                                return false;
                            }

                            // only allow protocols specified in ctx.protocols
                            const allowedProtocols = ctx.protocols.map(p => (typeof p === 'string' ? p : p.scheme));

                            if (!allowedProtocols.includes(protocol)) {
                                return false;
                            }

                            // all checks have passed
                            return true;
                        } catch (error) {
                            return false;
                        }
                    },
                    shouldAutoLink: url => {
                        try {
                            // construct URL
                            const parsedUrl = url.includes(':') ? new URL(url) : new URL(`https://${url}`);

                            // only auto-link if the domain is not in the disallowed list
                            const disallowedDomains = ['example-no-autolink.com', 'another-no-autolink.com'];
                            const domain = parsedUrl.hostname;

                            return !disallowedDomains.includes(domain);
                        } catch (error) {
                            return false;
                        }
                    },
                }),
                SearchAndReplace.configure({
                    searchResultClass: "search-result",
                    disableRegex: true,
                }),
                Subscript,
                Superscript,
                TextAlign.configure({
                    types: ['heading', 'paragraph'],
                }),
                Underline,
            ],
            onUpdate: ({ editor }) => {
                this.value = editor.getHTML();
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

    getConfig(): WysiwygConfig
    {
        if (this.config === null) {
            this.config = WysiwygConfig.getConfig(this.configName);
        }
        return this.config;
    }

    openModal(component: string, options: object = {}): Promise<object>
    {
        this.modal = new WysiwygModalConfiguration(component, options);
        return new Promise<object>((resolve, reject) => {
            this.modal.getPromise()
                .then((result) => {
                    resolve(result);
                    this.modal = null;
                })
                .catch(() => {
                    reject();
                    this.modal = null;
                })
            ;
        });
    }
}
