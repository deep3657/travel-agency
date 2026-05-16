<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

final class PackageService
{
    /** @param array<string, mixed> $data */
    public function create(array $data, User $actor): Package
    {
        $data['ulid'] ??= (string) Str::ulid();
        $data['slug'] ??= $this->uniqueSlug($data['title'] ?? 'package');

        return Package::query()->create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(Package $p, array $data, User $actor): Package
    {
        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = $this->uniqueSlug($data['title'], $p->id);
        }

        $p->update($data);

        return $p->refresh();
    }

    public function delete(Package $p, User $actor): void
    {
        $p->delete();
    }

    public function restore(Package $p, User $actor): void
    {
        $p->restore();
    }

    public function publish(Package $p, User $actor): Package
    {
        $p->update([
            'status' => 'active',
            'published_at' => Carbon::now(),
        ]);

        return $p->refresh();
    }

    public function unpublish(Package $p, User $actor): Package
    {
        $p->update(['status' => 'draft']);

        return $p->refresh();
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            Package::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
