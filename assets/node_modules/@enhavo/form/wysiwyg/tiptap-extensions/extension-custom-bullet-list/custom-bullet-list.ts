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
            toggleBulletList: () => ({ commands, chain }) => {
                if (this.options.keepAttributes) {
                    return chain()
                        .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': 'disc' })
                        .updateAttributes(ListItemName, this.editor.getAttributes(TextStyleName))
                        .run()
                    ;
                }
                return commands
                    .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': 'disc' })
                ;
            },
            toggleStyledBulletList: (listStyle: ListStyle) => ({ commands, chain }) => {
                if (this.options.keepAttributes) {
                    return chain()
                        .toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': listStyle })
                        .updateAttributes(ListItemName, this.editor.getAttributes(TextStyleName))
                        .run()
                    ;
                }
                return commands.toggleList(this.name, this.options.itemTypeName, this.options.keepMarks, { 'listStyle': listStyle });
            },
        };
    },
});
