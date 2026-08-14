<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ConsentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaUploadRequest;
use App\Models\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MediaAssetController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', MediaAsset::class);

        $assets = MediaAsset::query()->latest()->paginate(24);

        return view('admin.media.index', compact('assets'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', MediaAsset::class);

        return view('admin.media.create', [
            'returnUrl' => $request->query('return'),
            'defaultFolder' => $request->query('folder', 'uploads'),
        ]);
    }

    public function store(MediaUploadRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $folder = trim((string) $request->input('folder', 'uploads')) ?: 'uploads';
        $files = $this->collectUploadedFiles($request);

        if ($files === []) {
            throw ValidationException::withMessages([
                'files' => 'Please choose one or more valid files to upload.',
            ]);
        }

        $assets = [];
        $duplicates = [];
        $errors = [];

        foreach ($files as $file) {
            $original = $file->getClientOriginalName() ?: 'file';

            if (! $file->isValid()) {
                $errors[] = "{$original}: ".$file->getErrorMessage();
                continue;
            }

            $realPath = $file->getRealPath();
            if (! $realPath || ! is_readable($realPath)) {
                $errors[] = "{$original}: could not read the uploaded file.";
                continue;
            }

            $hash = hash_file('sha256', $realPath);
            if (! $hash) {
                $errors[] = "{$original}: could not fingerprint the file.";
                continue;
            }

            $existing = MediaAsset::query()->where('content_hash', $hash)->first();
            if ($existing) {
                $duplicates[] = [
                    'filename' => $original,
                    'existing_id' => $existing->id,
                    'existing_filename' => $existing->filename,
                    'url' => $existing->publicUrl(),
                ];
                continue;
            }

            $path = $file->store($folder, 'public');
            if (! is_string($path) || $path === '' || $path === '0') {
                $errors[] = "{$original}: could not save to storage. Check folder permissions.";
                continue;
            }

            $assets[] = MediaAsset::create([
                'disk' => 'public',
                'path' => $path,
                'filename' => $original,
                'mime' => $file->getMimeType() ?? 'application/octet-stream',
                'size' => (int) $file->getSize(),
                'content_hash' => $hash,
                'alt' => $request->input('alt'),
                'caption' => $request->input('caption'),
                'folder' => $folder,
                'consent_status' => $request->input('consent_status', ConsentStatus::Pending->value),
            ]);
        }

        if ($assets === [] && $duplicates === [] && $errors !== []) {
            throw ValidationException::withMessages([
                'files' => implode(' ', $errors),
            ]);
        }

        if ($assets === [] && $duplicates !== [] && $errors === []) {
            $names = collect($duplicates)->pluck('filename')->implode(', ');
            throw ValidationException::withMessages([
                'files' => "No new files uploaded. Already in the library: {$names}.",
            ]);
        }

        $count = count($assets);
        $dupCount = count($duplicates);
        $first = $assets[0] ?? null;
        $message = $this->uploadSummaryMessage($count, $dupCount, $errors);

        if ($request->expectsJson() || $request->wantsJson()) {
            $payload = [
                'message' => $message,
                'assets' => collect($assets)->map(fn (MediaAsset $asset) => [
                    'id' => $asset->id,
                    'label' => $asset->pickerLabel(),
                    'url' => $asset->publicUrl(),
                    'mime' => $asset->mime,
                    'folder' => $asset->folder,
                    'filename' => $asset->filename,
                ])->values()->all(),
                'duplicates' => $duplicates,
                'errors' => $errors,
            ];

            if ($count === 1 && $first) {
                $payload['asset'] = $payload['assets'][0];
            }

            return response()->json($payload, $count > 0 ? 201 : 200);
        }

        $return = $request->input('return');
        if (is_string($return) && str_starts_with($return, url('/'))) {
            return redirect($return)->with('success', $message);
        }

        if ($count === 1 && $first && $dupCount === 0 && $errors === []) {
            return redirect()
                ->route('admin.media.edit', $first)
                ->with('success', $message);
        }

        return redirect()
            ->route('admin.media.index')
            ->with('success', $message);
    }

    /**
     * @return list<UploadedFile>
     */
    private function collectUploadedFiles(MediaUploadRequest $request): array
    {
        $files = [];

        if ($request->hasFile('files')) {
            $uploaded = $request->file('files');
            $uploaded = is_array($uploaded) ? $uploaded : [$uploaded];
            foreach ($uploaded as $file) {
                if ($file instanceof UploadedFile) {
                    $files[] = $file;
                }
            }
        } elseif ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file instanceof UploadedFile) {
                $files[] = $file;
            }
        }

        return $files;
    }

    /**
     * @param  list<string>  $errors
     */
    private function uploadSummaryMessage(int $uploaded, int $duplicates, array $errors): string
    {
        $parts = [];

        if ($uploaded === 1) {
            $parts[] = '1 file uploaded.';
        } elseif ($uploaded > 1) {
            $parts[] = "{$uploaded} files uploaded.";
        }

        if ($duplicates === 1) {
            $parts[] = '1 duplicate skipped (already in the library).';
        } elseif ($duplicates > 1) {
            $parts[] = "{$duplicates} duplicates skipped (already in the library).";
        }

        if ($errors !== []) {
            $parts[] = 'Some files failed: '.implode(' ', $errors);
        }

        return trim(implode(' ', $parts)) ?: 'No files uploaded.';
    }

    public function edit(MediaAsset $medium): View
    {
        $this->authorize('update', $medium);

        return view('admin.media.edit', ['asset' => $medium]);
    }

    public function update(Request $request, MediaAsset $medium): RedirectResponse
    {
        $this->authorize('update', $medium);

        $validated = $request->validate([
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
            'credit' => ['nullable', 'string', 'max:255'],
            'copyright' => ['nullable', 'string', 'max:255'],
            'folder' => ['nullable', 'string', 'max:100'],
            'consent_status' => ['nullable', 'string'],
            'consent_notes' => ['nullable', 'string'],
            'is_private' => ['nullable', 'boolean'],
        ]);

        $medium->update($validated);

        return back()->with('success', 'Media metadata updated.');
    }

    public function destroy(MediaAsset $medium): RedirectResponse
    {
        $this->authorize('delete', $medium);

        if ($medium->path && $medium->path !== '0' && Storage::disk($medium->disk)->exists($medium->path)) {
            Storage::disk($medium->disk)->delete($medium->path);
        }

        $medium->delete();

        return redirect()->route('admin.media.index')->with('success', 'Media deleted.');
    }
}
