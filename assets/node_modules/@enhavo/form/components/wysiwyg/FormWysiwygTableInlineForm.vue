<template>
    <div class="wysiwyg-inline-menu-form wysiwyg-table-inline-modal">
        <div class="form-row wysiwyg-inline-menu-form-row">
            <label>{{ translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.label', [], 'javascript') }}</label>
        </div>
        <div class="wysiwyg-inline-menu-form-actions">
            <div class="wysiwyg-inline-menu-form-spacer">&nbsp;</div>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().addColumnBefore().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.column_before', [], 'javascript')"
            >
                <i class="icon icon-table_column_add_before"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().addColumnAfter().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.column_after', [], 'javascript')"
            >
                <i class="icon icon-table_column_add_after"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().deleteColumn().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.column_delete', [], 'javascript')"
            >
                <i class="icon icon-table_column_delete"></i>
            </a>
            <div class="wysiwyg-inline-menu-form-spacer">&nbsp;</div>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().addRowBefore().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.row_before', [], 'javascript')"
            >
                <i class="icon icon-table_row_add_before"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().addRowAfter().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.row_after', [], 'javascript')"
            >
                <i class="icon icon-table_row_add_after"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().deleteRow().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.row_delete', [], 'javascript')"
            >
                <i class="icon icon-table_row_delete"></i>
            </a>
            <div class="wysiwyg-inline-menu-form-spacer">&nbsp;</div>
            <div class="wysiwyg-submenu"
                 :class="{ 'open': submenuOpen }"
                 :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.sub_menu.label', {}, 'javascript')"
                 :aria-label="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.sub_menu.label', {}, 'javascript')"
                 v-click-outside="submenuClose"
            >
                <div class="wysiwyg-submenu-dropdown"
                     :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.sub_menu.label', {}, 'javascript')"
                     :aria-label="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.sub_menu.label', {}, 'javascript')"
                     @click="submenuToggleOpen"><i class="icon icon-more_vert"></i>
                </div>
                <div class="wysiwyg-submenu-items">
                    <div class="wysiwyg-button"
                         @click="form.editor.chain().focus().mergeCells().run(); submenuClose()"
                    >
                        {{ translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.sub_menu.merge', {}, 'javascript') }}
                    </div>
                    <div class="wysiwyg-button"
                         @click="form.editor.chain().focus().splitCell().run(); submenuClose()"
                    >
                        {{ translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.sub_menu.split', {}, 'javascript') }}
                    </div>
                    <div class="wysiwyg-button"
                         @click="form.editor.chain().focus().toggleHeaderRow().run(); submenuClose()"
                    >
                        {{ translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.sub_menu.toggle_header_row', {}, 'javascript') }}
                    </div>
                    <div class="wysiwyg-button"
                         @click="form.editor.chain().focus().toggleHeaderColumn().run(); submenuClose()"
                    >
                        {{ translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.sub_menu.toggle_header_col', {}, 'javascript') }}
                    </div>
                    <div class="wysiwyg-button"
                         @click="form.editor.chain().focus().toggleHeaderCell().run(); submenuClose()"
                    >
                        {{ translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.sub_menu.toggle_header_cell', {}, 'javascript') }}
                    </div>
                    <div class="wysiwyg-button"
                         @click="form.editor.chain().focus().deleteTable().run(); submenuClose()"
                    >
                        {{ translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.sub_menu.delete_table', {}, 'javascript') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import {inject, onMounted, onUnmounted, ref, watch} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {Translator} from "@enhavo/app/translation/Translator";

const translator = inject<Translator>('translator');

const props = defineProps<{
    form: WysiwygForm
}>();

const submenuOpen = ref(false);

function submenuToggleOpen()
{
    submenuOpen.value = !submenuOpen.value;
}

function submenuClose()
{
    submenuOpen.value = false;
}

</script>