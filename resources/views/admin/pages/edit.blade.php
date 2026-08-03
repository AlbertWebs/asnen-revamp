@extends('layouts.admin')

@section('title', $page->exists ? 'Edit Page' : 'New Page')
@section('heading', $page->exists ? 'Edit Page' : 'New Page')

@section('content')
    @if ($page->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $page, 'routePrefix' => 'pages'])
        </div>
    @endif

    <form
        method="POST"
        action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
        x-data="{
            blocks: @js($blocks).length ? @js($blocks) : [],
            newBlockType: 'hero',
            mediaOptions: @js($mediaOptions ?? []),
            blockTypes: ['hero','who_we_are','rich_text','image_text','statistics','program_grid','ubuntu_values','impact_story','timeline','cta','testimonial','gallery','video','downloads','partners','team','faq','map','form','newsletter','featured_content','featured_events','featured_resources','get_involved'],
            addBlock() {
                this.blocks.push({ type: this.newBlockType, is_visible: true, content: { heading: '', body: '', image_id: null }, settings: {}, anchor_id: this.newBlockType + '-' + (this.blocks.length + 1) });
            },
            removeBlock(index) { this.blocks.splice(index, 1); },
            moveUp(index) {
                if (index === 0) return;
                [this.blocks[index - 1], this.blocks[index]] = [this.blocks[index], this.blocks[index - 1]];
            },
            moveDown(index) {
                if (index >= this.blocks.length - 1) return;
                [this.blocks[index + 1], this.blocks[index]] = [this.blocks[index], this.blocks[index + 1]];
            }
        }"
    >
        @csrf
        @if ($page->exists) @method('PUT') @endif

        <input type="hidden" name="blocks" :value="JSON.stringify(blocks)">

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save page</button>
        </div>

        <div class="mb-4 rounded-md border border-brand/20 bg-brand-50 px-4 py-3 text-sm text-charcoal-700">
            For block images (hero / about): upload in
            <a href="{{ route('admin.media.create') }}" class="font-semibold text-forest-700 underline">Media Library</a>,
            note the image ID, then enter it in the block’s Image ID field below.
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-4 lg:col-span-1">
                <div class="rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
                    <label for="title" class="block text-sm font-medium text-charcoal-700">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

                    <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

                    <label for="excerpt" class="mt-4 block text-sm font-medium text-charcoal-700">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" rows="3" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('excerpt', $page->excerpt) }}</textarea>

                    <label for="template" class="mt-4 block text-sm font-medium text-charcoal-700">Template</label>
                    <input type="text" name="template" id="template" value="{{ old('template', $page->template ?? 'default') }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

                    <label class="mt-4 flex items-center gap-2 text-sm text-charcoal-700">
                        <input type="checkbox" name="requires_safeguarding" value="1" @checked(old('requires_safeguarding', $page->requires_safeguarding)) class="rounded border-charcoal-300 text-forest-600 focus:ring-forest-500">
                        Requires safeguarding review
                    </label>

                    <label for="editor_notes" class="mt-4 block text-sm font-medium text-charcoal-700">Editor notes</label>
                    <textarea name="editor_notes" id="editor_notes" rows="3" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('editor_notes', $page->editor_notes) }}</textarea>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-base font-semibold text-charcoal-900">Content blocks</h2>
                        <div class="flex flex-wrap gap-2">
                            <select x-model="newBlockType" class="rounded-md border-charcoal-300 text-sm focus:border-forest-500 focus:ring-forest-500">
                                <template x-for="type in blockTypes" :key="type">
                                    <option :value="type" x-text="type.replace(/_/g, ' ')"></option>
                                </template>
                            </select>
                            <button type="button" @click="addBlock()" class="rounded-md border border-charcoal-300 bg-white px-3 py-1.5 text-sm hover:bg-charcoal-50">Add block</button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(block, index) in blocks" :key="index">
                            <div class="rounded-md border border-charcoal-200 p-3" :class="block.is_visible ? 'bg-white' : 'bg-charcoal-50 opacity-70'">
                                <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                                    <span class="text-sm font-semibold capitalize text-charcoal-800" x-text="block.type.replace(/_/g, ' ')"></span>
                                    <div class="flex flex-wrap gap-1">
                                        <button type="button" @click="moveUp(index)" class="rounded px-2 py-1 text-xs text-charcoal-600 hover:bg-charcoal-100" :disabled="index === 0">↑</button>
                                        <button type="button" @click="moveDown(index)" class="rounded px-2 py-1 text-xs text-charcoal-600 hover:bg-charcoal-100" :disabled="index === blocks.length - 1">↓</button>
                                        <button type="button" @click="block.is_visible = !block.is_visible" class="rounded px-2 py-1 text-xs text-charcoal-600 hover:bg-charcoal-100" x-text="block.is_visible ? 'Hide' : 'Show'"></button>
                                        <button type="button" @click="removeBlock(index)" class="rounded px-2 py-1 text-xs text-red-700 hover:bg-red-50">Remove</button>
                                    </div>
                                </div>
                                <label class="block text-xs font-medium text-charcoal-600">Heading / label</label>
                                <input type="text" x-model="block.content.heading" class="mt-1 block w-full rounded-md border-charcoal-300 text-sm focus:border-forest-500 focus:ring-forest-500">
                                <label class="mt-2 block text-xs font-medium text-charcoal-600">Body</label>
                                <textarea x-model="block.content.body" rows="3" class="mt-1 block w-full rounded-md border-charcoal-300 text-sm focus:border-forest-500 focus:ring-forest-500"></textarea>

                                <template x-if="['hero','who_we_are','image_text'].includes(block.type)">
                                    <div class="mt-3 rounded-md bg-sand/80 p-3">
                                        <label class="block text-xs font-medium text-charcoal-600">Section image</label>
                                        <select
                                            class="mt-1 block w-full rounded-md border-charcoal-300 text-sm focus:border-forest-500 focus:ring-forest-500"
                                            :value="block.content.image_id || ''"
                                            @change="block.content.image_id = ($event.target.value === '' ? null : Number($event.target.value))"
                                        >
                                            <option value="">No image (show placeholder)</option>
                                            <template x-for="opt in mediaOptions" :key="opt.id">
                                                <option :value="opt.id" x-text="opt.label" :selected="Number(block.content.image_id) === Number(opt.id)"></option>
                                            </template>
                                        </select>
                                        <p class="mt-1 text-[11px] text-charcoal-500">Upload via Media Library first. Hero and About sections use this image.</p>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <p x-show="blocks.length === 0" class="py-6 text-center text-sm text-charcoal-500">No blocks yet. Add one to build the page.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
