import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuGroup} from "@enhavo/form/wysiwyg/WysiwygMenuGroup";
import {WysiwygMenuButton} from "@enhavo/form/wysiwyg/WysiwygMenuButton";
import {WysiwygMenuSpacer} from "@enhavo/form/wysiwyg/WysiwygMenuSpacer";
import {WysiwygMenuSubmenu} from "@enhavo/form/wysiwyg/WysiwygMenuSubmenu";
import * as _ from "lodash";

export class WysiwygConfig
{
    private static configurations: WysiwygConfig[] = [
        {
            name: 'default',
            menu: [
                {
                    items: [
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'undo',
                            tooltip: 'Undo',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().undo().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().undo().run(); }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'redo',
                            tooltip: 'Redo',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().redo().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().redo().run(); }
                        }),
                    ]
                },
                {
                    items: [
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_bold',
                            tooltip: 'Bold',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleBold().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleBold().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('bold') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_italic',
                            tooltip: 'Italic',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleItalic().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleItalic().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('italic') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_underlined',
                            tooltip: 'Underlined',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleUnderline().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleUnderline().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('underline') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'strikethrough_s',
                            tooltip: 'Strikethrough',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStrike().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleStrike().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('strike') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_subscript',
                            tooltip: 'Subscript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleSubscript().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleSubscript().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('subscript') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_superscript',
                            tooltip: 'Subscript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleSuperscript().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleSuperscript().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('superscript') ? 'is-active' : ''; }
                        }),
                    ]
                },
                {
                    items: [
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'link',
                            tooltip: 'Link',
                            click: (event: Event, form: WysiwygForm) => {

                                form.openModal('form-wysiwyg-modal-link', {
                                    url: form.editor.getAttributes('link').href,
                                    target: form.editor.getAttributes('link').target,
                                })
                                    .then((result: object) => {
                                        if (result['unlink']) {
                                            if (form.editor.isActive('link')) {
                                                form.editor.chain().focus().unsetLink().run();
                                            }
                                        } else {
                                            //TODO: Validate url
                                            form.editor.chain().focus().extendMarkRange('link').setLink({
                                                href: result['url'],
                                                target: result['target'],
                                            }).run();
                                        }
                                    })
                                    .catch(() => {
                                        // Cancelled
                                    })
                                ;

                            },
                            class: (form: WysiwygForm) => { return form.editor.isActive('link') ? 'is-active' : ''; }
                        }),
                        new WysiwygMenuSpacer(),
                        _.assign(new WysiwygMenuSubmenu(), {
                            label: 'Format',
                            items: [
                                _.assign(new WysiwygMenuSubmenu(), {
                                    label: 'Headings',
                                    items: [
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'Heading 1',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleHeading({ level: 1 }).run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('heading', { level: 1 }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'Heading 2',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleHeading({ level: 2 }).run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('heading', { level: 2 }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'Heading 3',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleHeading({ level: 3 }).run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('heading', { level: 3 }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'Heading 4',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleHeading({ level: 4 }).run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('heading', { level: 4 }) ? 'is-active' : ''; }
                                        }),
                                    ]
                                }),
                                _.assign(new WysiwygMenuSubmenu(), {
                                    label: 'Align',
                                    items: [
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'Left',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('left').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'left' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'Center',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('center').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'center' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'Right',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('right').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'right' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'Justify',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('justify').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'justify' }) ? 'is-active' : ''; }
                                        }),
                                    ]
                                })
                            ]
                        })
                    ]
                },
                {
                    items: [
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_align_left',
                            tooltip: 'Align left',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('left').run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'left' }) ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_align_center',
                            tooltip: 'Align center',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('center').run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'center' }) ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_align_right',
                            tooltip: 'Align right',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('right').run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'right' }) ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_align_justify',
                            tooltip: 'Justify',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('justify').run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'justify' }) ? 'is-active' : ''; }
                        }),
                    ]
                },
                {
                    items: [
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_list_bulleted',
                            tooltip: 'Bullet list',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleBulletList().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('bulletList') ? 'is-active' : ''; },
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_list_numbered',
                            tooltip: 'Numbered list',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleOrderedList().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('orderedList') ? 'is-active' : ''; },
                        }),
                    ]
                },
                {
                    items: [
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'search',
                            tooltip: 'Search and replace',
                            click: (event: Event, form: WysiwygForm) => {
                                form.searchAndReplaceOpen = !form.searchAndReplaceOpen;
                            },
                            class: (form: WysiwygForm) => { return form.searchAndReplaceOpen ? 'is-active' : ''; }
                        }),
                    ]
                },
                {
                    items: [
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'code',
                            tooltip: 'Source Code',
                            click: (event: Event, form: WysiwygForm) => {
                                form.openModal('form-wysiwyg-modal-source-code', {
                                    code: form.editor.getHTML(),
                                })
                                    .then((result: object) => {
                                        // console.log(form.element);
                                        form.destroyEditor();
                                        form.value = result['code'];
                                        form.initEditor();
                                    })
                                    .catch(() => {
                                        // Cancelled
                                    })
                                ;
                            },
                        }),
                    ]
                },
            ],
        }
    ];

    public static registerConfig(configuration: WysiwygConfig): void {
        let existingIndex = null;
        for(let index in WysiwygConfig.configurations) {
            if (WysiwygConfig.configurations[index].name === configuration.name) {
                existingIndex = index;
                break;
            }
        }
        if (existingIndex !== null) {
            WysiwygConfig.configurations.splice(existingIndex, 1);
        }
        WysiwygConfig.configurations.push(configuration);
    }

    public static getConfig(name: string): WysiwygConfig {
        for(let configuration of WysiwygConfig.configurations) {
            if (configuration.name === name) {
                return configuration;
            }
        }

        return null;
    }

    name: string;
    menu: WysiwygMenuGroup[] = [];
}
