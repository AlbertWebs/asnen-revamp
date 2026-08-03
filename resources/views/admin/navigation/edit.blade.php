@extends('layouts.admin')

@section('title', 'Edit Navigation')
@section('heading', 'Edit Navigation')

@section('content')
    <form method="POST" action="{{ route('admin.navigation.update', $navigation) }}" class="admin-form admin-form--wide" x-data="{ items: @js($navigation->items->map(fn ($i) => ['label' => $i->label, 'url' => $i->url, 'page_id' => $i->page_id, 'sort_order' => $i->sort_order, 'is_visible' => $i->is_visible, 'open_in_new_tab' => $i->open_in_new_tab])->values()) }">
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

                <div class="flex items-center justify-between">
                    <p class="admin-form__section-title">Items</p>
                    <button type="button" @click="items.push({ label: '', url: '', page_id: '', sort_order: items.length, is_visible: true, open_in_new_tab: false })" class="admin-btn-secondary">Add item</button>
                </div>

                <template x-for="(item, index) in items" :key="index">
                    <div class="space-y-3 rounded-xl border border-charcoal/10 p-4">
                        <input type="hidden" :name="'items['+index+'][label]'" :value="item.label">
                        <input type="hidden" :name="'items['+index+'][url]'" :value="item.url">
                        <input type="hidden" :name="'items['+index+'][page_id]'" :value="item.page_id">
                        <input type="hidden" :name="'items['+index+'][sort_order]'" :value="item.sort_order">
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

                        <button type="button" @click="items.splice(index, 1)" class="text-xs font-medium text-red-700 hover:underline">Remove</button>
                    </div>
                </template>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save navigation</button>
            </div>
        </div>
    </form>
@endsection
