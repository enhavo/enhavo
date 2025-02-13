import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuGroup} from "@enhavo/form/wysiwyg/WysiwygMenuGroup";
import {WysiwygMenuButton} from "@enhavo/form/wysiwyg/WysiwygMenuButton";
import {WysiwygMenuSpacer} from "@enhavo/form/wysiwyg/WysiwygMenuSpacer";
import {WysiwygMenuSubmenu} from "@enhavo/form/wysiwyg/WysiwygMenuSubmenu";
import {WysiwygSourceCodeButton} from "@enhavo/form/wysiwyg/WysiwygSourceCodeButton";
import * as _ from "lodash";
import {WysiwygColorButton} from "@enhavo/form/wysiwyg/WysiwygColorButton";

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
                            tooltip: 'enhavo_form.wysiwyg_form.command.undo',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().undo().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().undo().run(); }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'redo',
                            tooltip: 'enhavo_form.wysiwyg_form.command.redo',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().redo().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().redo().run(); }
                        }),
                    ]
                },
                {
                    items: [
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_bold',
                            tooltip: 'enhavo_form.wysiwyg_form.command.bold',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleBold().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleBold().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('bold') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_italic',
                            tooltip: 'enhavo_form.wysiwyg_form.command.italic',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleItalic().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleItalic().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('italic') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_underlined',
                            tooltip: 'enhavo_form.wysiwyg_form.command.underlined',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleUnderline().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleUnderline().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('underline') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'strikethrough_s',
                            tooltip: 'enhavo_form.wysiwyg_form.command.strikethrough',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStrike().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleStrike().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('strike') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_subscript',
                            tooltip: 'enhavo_form.wysiwyg_form.command.subscript',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleSubscript().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleSubscript().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('subscript') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_superscript',
                            tooltip: 'enhavo_form.wysiwyg_form.command.superscript',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleSuperscript().run(); },
                            disabled: (form: WysiwygForm) => { return !form.editor.can().chain().focus().toggleSuperscript().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('superscript') ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_clear',
                            tooltip: 'enhavo_form.wysiwyg_form.command.clear_format',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().unsetAllMarks().run(); },
                        }),
                    ]
                },
                {
                    items: [
                        _.assign(new WysiwygColorButton(), {
                            tooltip: 'enhavo_form.wysiwyg_form.command.color.label',
                            translationDomain: 'javascript',
                        }),
                    ],
                },
                {
                    items: [
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'link',
                            tooltip: 'enhavo_form.wysiwyg_form.command.link.label',
                            translationDomain: 'javascript',
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
                            label: 'enhavo_form.wysiwyg_form.command.menu_format.label',
                            translationDomain: 'javascript',
                            items: [
                                _.assign(new WysiwygMenuSubmenu(), {
                                    label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_headings.label',
                                    translationDomain: 'javascript',
                                    items: [
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_headings.heading1',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleHeading({ level: 1 }).run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('heading', { level: 1 }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_headings.heading2',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleHeading({ level: 2 }).run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('heading', { level: 2 }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_headings.heading3',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleHeading({ level: 3 }).run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('heading', { level: 3 }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_headings.heading4',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleHeading({ level: 4 }).run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('heading', { level: 4 }) ? 'is-active' : ''; }
                                        }),
                                    ]
                                }),
                                _.assign(new WysiwygMenuSubmenu(), {
                                    label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_formats.label',
                                    translationDomain: 'javascript',
                                    items: [
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_formats.bold',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleBold().run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('bold') ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_formats.italic',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleItalic().run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('italic') ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_formats.underlined',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleUnderline().run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('underline') ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_formats.strikethrough',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStrike().run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('strike') ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_formats.subscript',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleSubscript().run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('subscript') ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_formats.superscript',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleSuperscript().run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('superscript') ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_formats.clear_format',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().unsetAllMarks().run(); },
                                        }),
                                    ]
                                }),
                                _.assign(new WysiwygMenuSubmenu(), {
                                    label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_align.label',
                                    translationDomain: 'javascript',
                                    items: [
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_align.left',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('left').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'left' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_align.center',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('center').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'center' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_align.right',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('right').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'right' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_align.justify',
                                            translationDomain: 'javascript',
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
                            tooltip: 'enhavo_form.wysiwyg_form.command.align_left',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('left').run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'left' }) ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_align_center',
                            tooltip: 'enhavo_form.wysiwyg_form.command.align_center',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('center').run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'center' }) ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_align_right',
                            tooltip: 'enhavo_form.wysiwyg_form.command.align_right',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('right').run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'right' }) ? 'is-active' : ''; }
                        }),
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'format_align_justify',
                            tooltip: 'enhavo_form.wysiwyg_form.command.align_justify',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setTextAlign('justify').run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive({ textAlign: 'justify' }) ? 'is-active' : ''; }
                        }),
                    ]
                },
                {
                    items: [
                        _.assign(new WysiwygMenuSubmenu(), {
                            icon: 'format_list_bulleted',
                            tooltip: 'enhavo_form.wysiwyg_form.command.bullet_list',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleBulletList().run(); },
                            class: (form: WysiwygForm) => { return (form.editor.isActive('bulletList', { listStyle: 'disc' }) || form.editor.isActive('bulletList', { listStyle: null })) ? 'is-active' : ''; },
                            items: [
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.bullet_list_disc',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleBulletList().run(); },
                                    class: (form: WysiwygForm) => { return (form.editor.isActive('bulletList', { listStyle: 'disc' }) || form.editor.isActive('bulletList', { listStyle: null })) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.bullet_list_circle',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledBulletList('circle').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('bulletList', { listStyle: 'circle' }) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.bullet_list_square',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledBulletList('square').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('bulletList', { listStyle: 'square' }) ? 'is-active' : ''; },
                                }),
                            ],
                        }),
                        _.assign(new WysiwygMenuSubmenu(), {
                            icon: 'format_list_numbered',
                            tooltip: 'enhavo_form.wysiwyg_form.command.numbered_list',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleOrderedList().run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('orderedList') ? 'is-active' : ''; },
                            items: [
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_decimal',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('decimal').run(); },
                                    class: (form: WysiwygForm) => { return (form.editor.isActive('orderedList', { listStyle: 'decimal' }) || form.editor.isActive('orderedList', { listStyle: null })) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_lower_alpha',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('lower-alpha').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('orderedList', { listStyle: 'lower-alpha' }) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_upper_alpha',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('upper-alpha').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('orderedList', { listStyle: 'upper-alpha' }) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_lower_roman',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('lower-roman').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('orderedList', { listStyle: 'lower-roman' }) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_upper_roman',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('upper-roman').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('orderedList', { listStyle: 'upper-roman' }) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_lower_greek',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('lower-greek').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('orderedList', { listStyle: 'lower-greek' }) ? 'is-active' : ''; },
                                }),
                            ],
                        }),
                    ]
                },
                {
                    items: [
                        _.assign(new WysiwygMenuButton(), {
                            icon: 'search',
                            tooltip: 'enhavo_form.wysiwyg_form.command.search.label',
                            translationDomain: 'javascript',
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
                            icon: 'table',
                            tooltip: 'enhavo_form.wysiwyg_form.command.table.label',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().insertTable({ rows: 2, cols: 3, withHeaderRow: false }).run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('table') ? 'is-active' : ''; }
                        }),
                    ],
                },
                {
                    items: [
                        _.assign(new WysiwygSourceCodeButton(), {
                            tooltip: 'enhavo_form.wysiwyg_form.command.source_code.label',
                            translationDomain: 'javascript',
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
