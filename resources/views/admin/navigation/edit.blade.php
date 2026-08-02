@extends('layouts.admin')

@section('title', 'Edit Navigation')
@section('heading', 'Edit Navigation')

@section('content')
    <form method="POST" action="{{ route('admin.navigation.update', $navigation) }}" x-data="{ items: @js($navigation->items->map(fn ($i) => ['label' => $i->label, 'url' => $i->url, 'page_id' => $i->page_id, 'sort_order' => $i->sort_order, 'is_visible' => $i->is_visible, 'open_in_new_tab' => $i->open_in_new_tab])->values()) }">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save navigation</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="name" class="block text-sm font-medium text-charcoal-700">Menu name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $navigation->name) }}" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            <div class="mt-6 flex items-center justify-between">
                <h2 class="text-base font-semibold text-charcoal-900">Items</h2>
                <button type="button" @click="items.push({ label: '', url: '', page_id: '', sort_order: items.length, is_visible: true, open_in_new_tab: false })" class="rounded-md border border-charcoal-300 px-3 py-1.5 text-sm hover:bg-charcoal-50">Add item</button>
            </div>

            <template x-for="(item, index) in items" :key="index">
                <div class="mt-4 rounded-md border border-charcoal-200 p-3">
                    <input type="hidden" :name="'items['+index+'][label]'" :value="item.label">
                    <input type="hidden" :name="'items['+index+'][url]'" :value="item.url">
                    <input type="hidden" :name="'items['+index+'][page_id]'" :value="item.page_id">
                    <input type="hidden" :name="'items['+index+'][sort_order]'" :value="item.sort_order">
                    <input type="hidden" :name="'items['+index+'][is_visible]'" :value="item.is_visible ? 1 : 0">
                    <input type="hidden" :name="'items['+index+'][open_in_new_tab]'" :value="item.open_in_new_tab ? 1 : 0">

                    <label class="block text-xs font-medium text-charcoal-600">Label</label>
                    <input type="text" x-model="item.label" class="mt-1 block w-full rounded-md border-charcoal-300 text-sm">

                    <label class="mt-2 block text-xs font-medium text-charcoal-600">URL</label>
                    <input type="text" x-model="item.url" class="mt-1 block w-full rounded-md border-charcoal-300 text-sm">

                    <label class="mt-2 block text-xs font-medium text-charcoal-600">Page</label>
                    <select x-model="item.page_id" class="mt-1 block w-full rounded-md border-charcoal-300 text-sm">
                        <option value="">- None -</option>
                        @foreach ($pages as $page)
                            <option value="{{ $page->id }}">{{ $page->title }}</option>
                        @endforeach
                    </select>

                    <button type="button" @click="items.splice(index, 1)" class="mt-2 text-xs text-red-700 hover:underline">Remove</button>
                </div>
            </template>
        </div>
    </form>
@endsection
