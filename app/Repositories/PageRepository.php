<?php

namespace App\Repositories;

use App\Models\Page;

class PageRepository
{
    public function findBySlug(string $slug): ?Page
    {
        return Page::query()
            ->published()
            ->where('slug', $slug)
            ->with(['blocks' => fn ($q) => $q->where('is_visible', true)])
            ->first();
    }

    public function findByPath(string $path): ?Page
    {
        $path = trim($path, '/');

        if ($path === '' || $path === 'home') {
            return $this->findBySlug('home');
        }

        $slugFromPath = str_replace('/', '-', $path);

        $page = $this->findBySlug($slugFromPath);

        if ($page) {
            return $page;
        }

        $lastSegment = basename($path);

        return $this->findBySlug($lastSegment);
    }
}
