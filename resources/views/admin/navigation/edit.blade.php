@extends('layouts.admin')

@section('title', 'Edit Navigation')
@section('heading', 'Edit Navigation')

@section('content')
    <form method="POST" action="{{ route('admin.navigation.update', $navigation) }}" class="admin-form admin-form--wide" x-data="{
        items: @js($items),
        emptyItem() {
            return { label: '', url: '', page_id: '', sort_order: 0, is_visible: true, open_in_new_tab: false, children: [] };
        },
        addItem() {
            this.items.push(this.emptyItem());
        },
        addChild(index) {
            if (! Array.isArray(this.items[index].children)) {
                this.items[index].children = [];
            }
            this.items[index].children.push(this.emptyItem());
        }
    }">
        @csrf
        @method('PUT')

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Navigation</p>
                <h2 class="admin-form__title">Edit navigation</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="name" class="admin-label">Menu name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $navigation->name) }}" required class="admin-input">
                </div>

                <p class="admin-hint">Top-level items match the public header. Nested items are the dropdown links, including merged pages such as Who We Are, Leadership &amp; Governance, and Success Stories.</p>

                <div class="flex items-center justify-between">
                    <p class="admin-form__section-title">Items</p>
                    <button type="button" @click="addItem()" class="admin-btn-secondary">Add item</button>
                </div>

                <template x-for="(item, index) in items" :key="'parent-'+index">
                    <div class="space-y-3 rounded-xl border border-charcoal/10 p-4">
                        <input type="hidden" :name="'items['+index+'][label]'" :value="item.label">
                        <input type="hidden" :name="'items['+index+'][url]'" :value="item.url">
                        <input type="hidden" :name="'items['+index+'][page_id]'" :value="item.page_id">
                        <input type="hidden" :name="'items['+index+'][sort_order]'" :value="index">
                        <input type="hidden" :name="'items['+index+'][is_visible]'" :value="item.is_visible ? 1 : 0">
                        <input type="hidden" :name="'items['+index+'][open_in_new_tab]'" :value="item.open_in_new_tab ? 1 : 0">

                        <div class="admin-field">
                            <label class="admin-label">Label</label>
                            <input type="text" x-model="item.label" class="admin-input">
                        </div>

                        <div class="admin-field">
                            <label class="admin-label">URL</label>
                            <input type="text" x-model="item.url" class="admin-input">
                        </div>

                        <div class="admin-field">
                            <label class="admin-label">Page</label>
                            <select x-model="item.page_id" class="admin-select">
                                <option value="">- None -</option>
                                @foreach ($pages as $page)
                                    <option value="{{ $page->id }}">{{ $page->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" @click="addChild(index)" class="admin-btn-secondary">Add child link</button>
                            <button type="button" @click="items.splice(index, 1)" class="text-xs font-medium text-red-700 hover:underline">Remove</button>
                        </div>

                        <template x-for="(child, childIndex) in (item.children || [])" :key="'child-'+index+'-'+childIndex">
                            <div class="space-y-3 rounded-xl border border-dashed border-charcoal/15 bg-sand/40 p-4 ml-4">
                                <input type="hidden" :name="'items['+index+'][children]['+childIndex+'][label]'" :value="child.label">
                                <input type="hidden" :name="'items['+index+'][children]['+childIndex+'][url]'" :value="child.url">
                                <input type="hidden" :name="'items['+index+'][children]['+childIndex+'][page_id]'" :value="child.page_id">
                                <input type="hidden" :name="'items['+index+'][children]['+childIndex+'][sort_order]'" :value="childIndex">
                                <input type="hidden" :name="'items['+index+'][children]['+childIndex+'][is_visible]'" :value="child.is_visible ? 1 : 0">
                                <input type="hidden" :name="'items['+index+'][children]['+childIndex+'][open_in_new_tab]'" :value="child.open_in_new_tab ? 1 : 0">

                                <p class="text-xs font-semibold uppercase tracking-wide text-charcoal/45">Child of <span x-text="item.label || 'this item'"></span></p>

                                <div class="admin-field">
                                    <label class="admin-label">Label</label>
                                    <input type="text" x-model="child.label" class="admin-input">
                                </div>

                                <div class="admin-field">
                                    <label class="admin-label">URL</label>
                                    <input type="text" x-model="child.url" class="admin-input">
                                </div>

                                <div class="admin-field">
                                    <label class="admin-label">Page</label>
                                    <select x-model="child.page_id" class="admin-select">
                                        <option value="">- None -</option>
                                        @foreach ($pages as $page)
                                            <option value="{{ $page->id }}">{{ $page->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="button" @click="item.children.splice(childIndex, 1)" class="text-xs font-medium text-red-700 hover:underline">Remove child</button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save navigation</button>
            </div>
        </div>
    </form>
@endsection
