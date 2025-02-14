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
    name: 'customOrderedList',

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
            toggleOrderedList: () => ({ editor, commands, chain }) => {
                let commandChain = chain();

                if (editor.isActive('customOrderedList')
                    && !(
                        editor.isActive('customOrderedList', { 'listStyle': 'decimal' })
                        || editor.isActive('customOrderedList', { 'listStyle': null })
                    )
                ) {
                    // Unset current ordered list with wrong attributes before resetting it with the correct attributes
                    commandChain.toggleList(this.name, this.options.itemTypeName, this.options.keepMarks);
                }

                if (this.options.keepAttributes) {
                    return commandChain
                        .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': 'decimal' })
                        .updateAttributes(ListItemName, this.editor.getAttributes(TextStyleName))
                        .run()
                    ;
                }
                return commandChain
                    .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': 'decimal' })
                    .run()
                ;
            },
            toggleStyledOrderedList: (listStyle: ListStyle) => ({ editor, commands, chain }) => {
                let commandChain = chain();

                if (editor.isActive('customOrderedList') && !editor.isActive('customOrderedList', { 'listStyle': listStyle })) {
                    // Unset current ordered list with wrong attributes before resetting it with the correct attributes
                    commandChain.toggleList(this.name, this.options.itemTypeName, this.options.keepMarks);
                }

                if (this.options.keepAttributes) {
                    return commandChain
                        .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': listStyle })
                        .updateAttributes(ListItemName, this.editor.getAttributes(TextStyleName))
                        .run()
                    ;
                }
                return commandChain
                    .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': listStyle })
                    .run()
                ;
            },
        };
    },
});
