import BulletList from '@tiptap/extension-bullet-list';

const ListItemName = 'listItem';
const TextStyleName = 'textStyle';

export type ListStyle = null | 'disc' | 'circle' | 'square';

declare module '@tiptap/core' {
    interface Commands<ReturnType> {
        customBulletList: {
            /**
             * Toggle a bullet list
             */
            toggleBulletList: () => ReturnType,
            /**
             * Toggle a bullet list with style attribute
             */
            toggleStyledBulletList: (listStyle: ListStyle) => ReturnType,
        }
    }
}

export const CustomBulletList = BulletList.extend({
    name: 'customBulletList',

    addAttributes() {
        return {
            listStyle: {
                default: 'disc',
                parseHTML: (element: HTMLElement) => {
                    return element.style.listStyleType === '' ? 'disc' : element.style.listStyleType;
                },
                renderHTML: (attributes) => {
                    if (attributes.listStyle === 'disc') {
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
            toggleBulletList: () => ({ editor, commands, chain }) => {
                let commandChain = chain();

                if (editor.isActive('customBulletList')
                    && !(
                        editor.isActive('customBulletList', { 'listStyle': 'disc' })
                        || editor.isActive('customBulletList', { 'listStyle': null })
                    )
                ) {
                    // Unset current bullet list with wrong attributes before resetting it with the correct attributes
                    commandChain.toggleList(this.name, this.options.itemTypeName, this.options.keepMarks);
                }

                if (this.options.keepAttributes) {
                    return commandChain
                        .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': 'disc' })
                        .updateAttributes(ListItemName, this.editor.getAttributes(TextStyleName))
                        .run()
                    ;
                }
                return commandChain
                    .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': 'disc' })
                    .run()
                ;
            },
            toggleStyledBulletList: (listStyle: ListStyle) => ({ editor, commands, chain }) => {
                let commandChain = chain();

                if (editor.isActive('customBulletList') && !editor.isActive('customBulletList', { 'listStyle': listStyle })) {
                    // Unset current bullet list with wrong attributes before resetting it with the correct attributes
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
