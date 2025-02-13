import OrderedList from '@tiptap/extension-ordered-list';

const ListItemName = 'listItem';
const TextStyleName = 'textStyle';

export type ListStyle = null | 'decimal' | 'lower-alpha' | 'upper-alpha' | 'lower-roman' | 'upper-roman' | 'lower-greek';

declare module '@tiptap/core' {
    interface Commands<ReturnType> {
        customOrderedList: {
            /**
             * Toggle an ordered list
             * @example editor.commands.toggleOrderedList()
             */
            toggleOrderedList: () => ReturnType,
            /**
             * Toggle an ordered list
             * @example editor.commands.toggleOrderedList()
             */
            toggleStyledOrderedList: (listStyle: ListStyle) => ReturnType,
        }
    }
}

export const CustomOrderedList = OrderedList.extend({
    addAttributes() {
        return {
            listStyle: {
                default: 'decimal',
                parseHTML: (element: HTMLElement) => {
                    return element.style.listStyleType === '' ? 'decimal' : element.style.listStyleType;
                },
                renderHTML: (attributes) => {
                    if (attributes.listStyle === 'decimal') {
                        return null;
                    }
                    return {
                        style: `list-style-type: ${attributes.listStyle}`
                    };
                },
            },
        }
    },

    addCommands() {
        return {
            toggleOrderedList: () => ({ commands, chain }) => {
                if (this.options.keepAttributes) {
                    return chain()
                        .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': 'decimal' })
                        .updateAttributes(ListItemName, this.editor.getAttributes(TextStyleName))
                        .run()
                    ;
                }
                return commands
                    .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': 'decimal' })
                ;
            },
            toggleStyledOrderedList: (listStyle: ListStyle) => ({ commands, chain }) => {
                if (this.options.keepAttributes) {
                    return chain()
                        .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': listStyle })
                        .updateAttributes(ListItemName, this.editor.getAttributes(TextStyleName))
                        .run()
                    ;
                }
                return commands
                    .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': listStyle })
                ;
            },
        };
    },
});
