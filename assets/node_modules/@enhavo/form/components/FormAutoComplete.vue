<template>
    <div v-show="form.isVisible()">
        <select style="width: 100%" :name="form.fullName" :ref="(el) => form.setElement(el as HTMLElement)" :multiple="form.multiple" v-once>
            <template v-if="form.multiple">
                <option selected v-for="item in form.value" :value="item.id">{{ item.text }}</option>
            </template>
            <template v-else>
                <option selected :value="form.value.id" v-if="form.value">{{ form.value.text }}</option>
            </template>
        </select>
        <div class="related-buttons-row" v-if="form.createRoute">
            <a href="#" class="btn-secondary has-symbol" @click.prevent="form.openCreate()">
                {{ form.editLabel }} <i aria-hidden="true" class="icon icon-add_circle_outline"></i>
            </a>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, watch} from "vue";
import {AutoCompleteForm} from "@enhavo/form/form/model/AutoCompleteForm";
import $ from "jquery";
import select2 from "select2";
import Sortable from 'sortablejs';

const props = defineProps<{
    form: AutoCompleteForm
}>()

onMounted(() => {
    select2($);
    refresh();
    props.form.registerSubscribers();
});

watch(props.form.value, async(value) => {
    const $select = $(props.form.element);
    for (let item of value) {
        if ($select.find("option[value='" + item.id + "']").length) {
            $select.find("option[value='" + item.id + "']").text(item.text);
        } else {
            let newOption = new Option(item.text, item.id, true, true);
            $select.append(newOption);
        }
    }

    refresh();
});

function refresh()
{
    $(props.form.element).select2(buildConfig());

    if (props.form.editable) {
        applyEditable();
    }

    if (props.form.sortable) {
        applySortable();
    }
}

function applySortable()
{
    let $list = $(props.form.element).parent().find('.select2 ul');
    let listElement = <HTMLElement>$list.get(0);
    Sortable.create(listElement, {
        draggable: ".select2-selection__choice",
        animation: 150,
        onUpdate: () => {
            const ids = [];
            for (let item of $list.find('li span[data-auto-complete-id]')) {
                ids.push($(item).data('auto-complete-id'));
            }
            updateOrder(ids);
        },
        onEnd: () => {
            $list.find('.select2-search-field').appendTo($list.get(0));
        },
    });
}

function updateOrder(ids: string[])
{
    props.form.value.sort((a, b) => {
        return ids.indexOf(a.id) - ids.indexOf(b.id);
    })

    $(props.form.element).children().remove();

    for (let item of props.form.value) {
        $(props.form.element).append($('<option selected value="'+item.id+'">'+item.text+'</option>'));
    }

    refresh();
}

function applyEditable()
{
    let $list = $(props.form.element).parent().find('.select2 ul');

    $list.on('click', 'li span[data-auto-complete-id]', (event) => {
        event.preventDefault();
        event.stopPropagation();
        let id = $(event.target).data('auto-complete-id');
        props.form.openEdit(id);
    });
}

function buildConfig()
{
    return {
        sorter: (data) => {
            return data;
        },
        debug: true,
        templateSelection: function (state: any) {
            if (props.form.multiple) {
                return $("<span data-auto-complete-id=\"" + state.id + "\">"+state.text+"</span>");
            } else {
                return state.text;
            }
        },
        minimumInputLength: props.form.minimumInputLength,
        ajax: {
            url: props.form.url,
            delay: 500,
            data: function (searchTerm: Select2QueryOptions, page: number) {
                return {
                    q: searchTerm.term,
                    page: page || 1
                };
            },
            results: function (data: any, page: any) {
                return data;
            },
            cache: true,
        },
        tags: props.form.multiple,
        placeholder: props.form.placeholder,
        allowClear: props.form.placeholder != null,
    };
}

</script>
