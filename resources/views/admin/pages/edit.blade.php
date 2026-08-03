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
            blocks: @js($blocks),
            newBlockType: 'hero',
            mediaOptions: @js($mediaOptions ?? []),
            blockTypes: ['hero','who_we_are','rich_text','image_text','statistics','program_grid','ubuntu_values','impact_story','timeline','cta','testimonial','gallery','video','downloads','partners','team','faq','map','form','newsletter','featured_content','featured_events','featured_resources','get_involved'],
            addBlock() {
                const type = this.newBlockType;
                const content = type === 'hero'
                    ? { headline: '', supporting_text: '', image_id: null, image_ids: [], primary_cta: { label: '', url: '' }, secondary_cta: { label: '', url: '' } }
                    : { heading: '', body: '', image_id: null };
                this.blocks.push({ type, is_visible: true, content, settings: {}, anchor_id: type + '-' + (this.blocks.length + 1) });
            },
            removeBlock(index) { this.blocks.splice(index, 1); },
            moveUp(index) {
                if (index === 0) return;
                [this.blocks[index - 1], this.blocks[index]] = [this.blocks[index], this.blocks[index - 1]];
            },
            moveDown(index) {
                if (index >= this.blocks.length - 1) return;
                [this.blocks[index + 1], this.blocks[index]] = [this.blocks[index], this.blocks[index + 1]];
            },
            imageModel: {
                get(block) {
                    return block.content.image_id == null ? '' : String(block.content.image_id);
                },
                set(block, value) {
                    block.content.image_id = value === '' ? null : Number(value);
                }
            },
            ensureHeroSlides(block) {
                if (!Array.isArray(block.content.image_ids)) {
                    block.content.image_ids = block.content.image_id ? [Number(block.content.image_id)] : [];
                }
            },
            toggleHeroSlide(block, id) {
                this.ensureHeroSlides(block);
                const num = Number(id);
                const idx = block.content.image_ids.indexOf(num);
                if (idx === -1) {
                    block.content.image_ids.push(num);
                } else {
                    block.content.image_ids.splice(idx, 1);
                }
                block.content.image_id = block.content.image_ids[0] ?? null;
            },
            isHeroSlideSelected(block, id) {
                this.ensureHeroSlides(block);
                return block.content.image_ids.map(Number).includes(Number(id));
            },
            prepareSubmit() {
                this.blocks.forEach((block) => {
                    if (block.type === 'hero') {
                        this.ensureHeroSlides(block);
                        block.content.image_id = block.content.image_ids[0] ?? null;
                    }
                });
                this.$refs.blocksInput.value = JSON.stringify(this.blocks);
            }
        }"
        @submit="prepareSubmit()"
    >
        @csrf
        @if ($page->exists) @method('PUT') @endif

        <input type="hidden" name="blocks" x-ref="blocksInput" value="">

        <div class="admin-callout mb-4">
            Upload images in the
            <a href="{{ route('admin.media.create', ['folder' => 'hero', 'return' => url()->current()]) }}" class="font-semibold text-brand underline">Media Library</a>,
            then choose them in the Hero / About block below and click <strong>Save page</strong>.
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-4 lg:col-span-1">
                <div class="admin-form admin-form--full">
                    <div class="admin-form__body">
                        <div class="admin-form__section">
                            <div class="admin-field">
                                <label for="title" class="admin-label">Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required class="admin-input">
                            </div>

                            <div class="admin-field">
                                <label for="slug" class="admin-label">Slug</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}" class="admin-input">
                            </div>

                            <div class="admin-field">
                                <label for="excerpt" class="admin-label">Excerpt</label>
                                <textarea name="excerpt" id="excerpt" rows="3" class="admin-textarea">{{ old('excerpt', $page->excerpt) }}</textarea>
                            </div>

                            <div class="admin-field">
                                <label for="template" class="admin-label">Template</label>
                                <input type="text" name="template" id="template" value="{{ old('template', $page->template ?? 'default') }}" class="admin-input">
                            </div>

                            <label class="admin-check">
                                <input type="checkbox" name="requires_safeguarding" value="1" @checked(old('requires_safeguarding', $page->requires_safeguarding))>
                                <span>Requires safeguarding review</span>
                            </label>

                            <div class="admin-field">
                                <label for="editor_notes" class="admin-label">Editor notes</label>
                                <textarea name="editor_notes" id="editor_notes" rows="3" class="admin-textarea">{{ old('editor_notes', $page->editor_notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="admin-form admin-form--full">
                    <div class="admin-form__body">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <p class="admin-form__section-title">Content blocks</p>
                            <div class="flex flex-wrap gap-2">
                                <select x-model="newBlockType" class="admin-select">
                                    <template x-for="type in blockTypes" :key="type">
                                        <option :value="type" x-text="type.replace(/_/g, ' ')"></option>
                                    </template>
                                </select>
                                <button type="button" @click="addBlock()" class="admin-btn-secondary">Add block</button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(block, index) in blocks" :key="index">
                                <div class="space-y-3 rounded-xl border border-charcoal/10 p-4" :class="block.is_visible ? 'bg-white' : 'bg-sand/60 opacity-70'">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <span class="text-sm font-semibold capitalize text-charcoal" x-text="block.type.replace(/_/g, ' ')"></span>
                                        <div class="flex flex-wrap gap-1">
                                            <button type="button" @click="moveUp(index)" class="rounded-lg px-2 py-1 text-xs text-charcoal/60 hover:bg-sand" :disabled="index === 0">↑</button>
                                            <button type="button" @click="moveDown(index)" class="rounded-lg px-2 py-1 text-xs text-charcoal/60 hover:bg-sand" :disabled="index === blocks.length - 1">↓</button>
                                            <button type="button" @click="block.is_visible = !block.is_visible" class="rounded-lg px-2 py-1 text-xs text-charcoal/60 hover:bg-sand" x-text="block.is_visible ? 'Hide' : 'Show'"></button>
                                            <button type="button" @click="removeBlock(index)" class="rounded-lg px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-50">Remove</button>
                                        </div>
                                    </div>

                                    {{-- Hero-specific fields (matches public hero component) --}}
                                    <template x-if="block.type === 'hero'">
                                        <div class="space-y-3">
                                            <div class="admin-field">
                                                <label class="admin-label">Headline</label>
                                                <input type="text" x-model="block.content.headline" class="admin-input" placeholder="Main hero headline">
                                            </div>
                                            <div class="admin-field">
                                                <label class="admin-label">Supporting text</label>
                                                <textarea x-model="block.content.supporting_text" rows="3" class="admin-textarea" placeholder="Short intro under the headline"></textarea>
                                            </div>
                                            <div class="grid gap-3 sm:grid-cols-2">
                                                <div class="admin-field">
                                                    <label class="admin-label">Primary CTA label</label>
                                                    <input type="text" x-model="block.content.primary_cta.label" class="admin-input" placeholder="Explore Our Programs">
                                                </div>
                                                <div class="admin-field">
                                                    <label class="admin-label">Primary CTA URL</label>
                                                    <input type="text" x-model="block.content.primary_cta.url" class="admin-input" placeholder="/what-we-do">
                                                </div>
                                                <div class="admin-field">
                                                    <label class="admin-label">Secondary CTA label</label>
                                                    <input type="text" x-model="block.content.secondary_cta.label" class="admin-input" placeholder="See Our Impact">
                                                </div>
                                                <div class="admin-field">
                                                    <label class="admin-label">Secondary CTA URL</label>
                                                    <input type="text" x-model="block.content.secondary_cta.url" class="admin-input" placeholder="/impact">
                                                </div>
                                            </div>
                                            <div class="admin-field">
                                                <label class="admin-label">Image alt text</label>
                                                <input type="text" x-model="block.content.image_alt" class="admin-input" placeholder="Describe the hero photo">
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Default fields for other blocks --}}
                                    <template x-if="block.type !== 'hero'">
                                        <div class="space-y-3">
                                            <div class="admin-field">
                                                <label class="admin-label">Heading / label</label>
                                                <input type="text" x-model="block.content.heading" class="admin-input">
                                            </div>
                                            <div class="admin-field">
                                                <label class="admin-label">Body</label>
                                                <textarea x-model="block.content.body" rows="3" class="admin-textarea"></textarea>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="['who_we_are','image_text'].includes(block.type)">
                                        <div class="admin-callout">
                                            <label class="admin-label">Section image</label>
                                            <select
                                                class="admin-select mt-1"
                                                :value="imageModel.get(block)"
                                                @change="imageModel.set(block, $event.target.value)"
                                            >
                                                <option value="">No image (show placeholder)</option>
                                                <template x-for="opt in mediaOptions" :key="opt.id">
                                                    <option
                                                        :value="String(opt.id)"
                                                        x-text="opt.label"
                                                        :selected="String(block.content.image_id ?? '') === String(opt.id)"
                                                    ></option>
                                                </template>
                                            </select>
                                            <p class="admin-hint mt-1">
                                                Pick an uploaded image, then save the page. Latest uploads appear at the top of this list.
                                            </p>
                                        </div>
                                    </template>

                                    <template x-if="block.type === 'hero'">
                                        <div class="admin-callout">
                                            <label class="admin-label">Hero carousel images</label>
                                            <p class="admin-hint mt-1 mb-2">
                                                Select two or more images for the rotating background. Order follows selection order in the list below.
                                            </p>
                                            <div class="max-h-56 space-y-2 overflow-y-auto rounded-lg border border-charcoal/10 bg-white p-3">
                                                <template x-for="opt in mediaOptions" :key="'hero-' + opt.id">
                                                    <label class="admin-check">
                                                        <input
                                                            type="checkbox"
                                                            :checked="isHeroSlideSelected(block, opt.id)"
                                                            @change="toggleHeroSlide(block, opt.id)"
                                                        >
                                                        <span x-text="opt.label"></span>
                                                    </label>
                                                </template>
                                            </div>
                                            <p class="admin-hint mt-2" x-text="(block.content.image_ids || []).length + ' slide(s) selected'"></p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <p x-show="blocks.length === 0" class="py-6 text-center text-sm text-charcoal/50">No blocks yet. Add one to build the page.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-form__actions mt-6">
            <button type="submit" class="admin-btn-primary">Save page</button>
        </div>
    </form>
@endsection
