import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuGroup} from "@enhavo/form/wysiwyg/WysiwygMenuGroup";
import {WysiwygMenuButton} from "@enhavo/form/wysiwyg/WysiwygMenuButton";
import {WysiwygMenuSpacer} from "@enhavo/form/wysiwyg/WysiwygMenuSpacer";
import {WysiwygMenuSubmenu} from "@enhavo/form/wysiwyg/WysiwygMenuSubmenu";
import {WysiwygSourceCodeButton} from "@enhavo/form/wysiwyg/WysiwygSourceCodeButton";
import {WysiwygColorButton} from "@enhavo/form/wysiwyg/WysiwygColorButton";
import {WysiwygBackgroundColorButton} from "@enhavo/form/wysiwyg/WysiwygBackgroundColorButton";
import {WysiwygSpecialCharactersButton} from "@enhavo/form/wysiwyg/WysiwygSpecialCharactersButton";
import {Extensions} from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import {BackgroundColor} from "@enhavo/form/wysiwyg/tiptap-extensions/extension-background-color/background-color";
import Color from "@tiptap/extension-color";
import {CustomBulletList} from "@enhavo/form/wysiwyg/tiptap-extensions/extension-custom-bullet-list/custom-bullet-list";
import {CustomOrderedList} from "@enhavo/form/wysiwyg/tiptap-extensions/extension-custom-ordered-list/custom-ordered-list";
import Image from '@tiptap/extension-image';
import Link from "@tiptap/extension-link";
import SearchAndReplace from "@sereneinserenade/tiptap-search-and-replace";
import Subscript from "@tiptap/extension-subscript";
import Superscript from "@tiptap/extension-superscript";
import Table from "@tiptap/extension-table";
import {TableView} from "@enhavo/form/wysiwyg/tiptap-extensions/extension-table/TableView";
import TableRow from "@tiptap/extension-table-row";
import TableHeader from "@tiptap/extension-table-header";
import TableCell from "@tiptap/extension-table-cell";
import TextAlign from "@tiptap/extension-text-align";
import TextStyle from "@tiptap/extension-text-style";
import Underline from "@tiptap/extension-underline";
import * as _ from "lodash";

export class WysiwygConfig
{
    private static configurations: WysiwygConfig[] = [
        {
            name: 'default',
            extensions: [
                StarterKit,
                BackgroundColor,
                Color,
                CustomBulletList,
                CustomOrderedList,
                Image.configure({
                    inline: true,
                    allowBase64: true,
                }),
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
                Table.configure({
                    resizable: true,
                    lastColumnResizable: false,
                    View: TableView
                }),
                TableRow,
                TableHeader,
                TableCell,
                TextAlign.configure({
                    types: ['heading', 'paragraph'],
                }),
                TextStyle,
                Underline,
            ],
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
                        _.assign(new WysiwygBackgroundColorButton(), {
                            tooltip: 'enhavo_form.wysiwyg_form.command.background_color.label',
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
                                    label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_blocks.label',
                                    translationDomain: 'javascript',
                                    items: [
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_blocks.paragraph',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().setParagraph().run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('paragraph') ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_blocks.blockquote',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleBlockquote().run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('blockquote') ? 'is-active' : ''; }
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
                                }),
                                _.assign(new WysiwygMenuSubmenu(), {
                                    label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_list.label',
                                    translationDomain: 'javascript',
                                    items: [
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_list.bullet_list_disc',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledBulletList('disc').run(); },
                                            class: (form: WysiwygForm) => { return (form.editor.isActive('customBulletList', { listStyle: 'disc' }) || form.editor.isActive('customBulletList', { listStyle: null })) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_list.bullet_list_circle',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledBulletList('circle').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('customBulletList', { listStyle: 'circle' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_list.bullet_list_square',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledBulletList('square').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('customBulletList', { listStyle: 'square' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_list.numbered_list_decimal',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('decimal').run(); },
                                            class: (form: WysiwygForm) => { return (form.editor.isActive('customOrderedList', { listStyle: 'decimal' }) || form.editor.isActive('customOrderedList', { listStyle: null })) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_list.numbered_list_lower_alpha',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('lower-alpha').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList', { listStyle: 'lower-alpha' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_list.numbered_list_upper_alpha',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('upper-alpha').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList', { listStyle: 'upper-alpha' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_list.numbered_list_lower_roman',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('lower-roman').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList', { listStyle: 'lower-roman' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_list.numbered_list_upper_roman',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('upper-roman').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList', { listStyle: 'upper-roman' }) ? 'is-active' : ''; }
                                        }),
                                        _.assign(new WysiwygMenuButton(), {
                                            label: 'enhavo_form.wysiwyg_form.command.menu_format.menu_list.numbered_list_lower_greek',
                                            translationDomain: 'javascript',
                                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('lower-greek').run(); },
                                            class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList', { listStyle: 'lower-greek' }) ? 'is-active' : ''; }
                                        }),
                                    ],
                                }),
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
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledBulletList('disc').run(); },
                            class: (form: WysiwygForm) => { return (form.editor.isActive('customBulletList', { listStyle: 'disc' }) || form.editor.isActive('customBulletList', { listStyle: null })) ? 'is-active' : ''; },
                            items: [
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.bullet_list_disc',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledBulletList('disc').run(); },
                                    class: (form: WysiwygForm) => { return (form.editor.isActive('customBulletList', { listStyle: 'disc' }) || form.editor.isActive('customBulletList', { listStyle: null })) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.bullet_list_circle',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledBulletList('circle').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('customBulletList', { listStyle: 'circle' }) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.bullet_list_square',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledBulletList('square').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('customBulletList', { listStyle: 'square' }) ? 'is-active' : ''; },
                                }),
                            ],
                        }),
                        _.assign(new WysiwygMenuSubmenu(), {
                            icon: 'format_list_numbered',
                            tooltip: 'enhavo_form.wysiwyg_form.command.numbered_list',
                            translationDomain: 'javascript',
                            click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('decimal').run(); },
                            class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList') ? 'is-active' : ''; },
                            items: [
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_decimal',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('decimal').run(); },
                                    class: (form: WysiwygForm) => { return (form.editor.isActive('customOrderedList', { listStyle: 'decimal' }) || form.editor.isActive('customOrderedList', { listStyle: null })) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_lower_alpha',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('lower-alpha').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList', { listStyle: 'lower-alpha' }) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_upper_alpha',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('upper-alpha').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList', { listStyle: 'upper-alpha' }) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_lower_roman',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('lower-roman').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList', { listStyle: 'lower-roman' }) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_upper_roman',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('upper-roman').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList', { listStyle: 'upper-roman' }) ? 'is-active' : ''; },
                                }),
                                _.assign(new WysiwygMenuButton(), {
                                    label: 'enhavo_form.wysiwyg_form.command.numbered_list_lower_greek',
                                    translationDomain: 'javascript',
                                    click: (event: Event, form: WysiwygForm) => { form.editor.chain().focus().toggleStyledOrderedList('lower-greek').run(); },
                                    class: (form: WysiwygForm) => { return form.editor.isActive('customOrderedList', { listStyle: 'lower-greek' }) ? 'is-active' : ''; },
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
                {
                    items: [
                        _.assign(new WysiwygSpecialCharactersButton(), {
                            icon: 'omega',
                            tooltip: 'enhavo_form.wysiwyg_form.command.special_characters.label',
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
    extensions: Extensions = [];
    menu: WysiwygMenuGroup[] = [];
}
