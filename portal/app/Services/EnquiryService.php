<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Enquiry;
use App\Models\EnquiryNote;
use App\Models\User;
use Illuminate\Support\Str;

final class EnquiryService
{
    /** @param array<string, mixed> $data */
    public function create(array $data, User $actor): Enquiry
    {
        $data['ulid'] ??= (string) Str::ulid();

        return Enquiry::query()->create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(Enquiry $e, array $data, User $actor): Enquiry
    {
        $e->update($data);

        return $e->refresh();
    }

    public function addNote(Enquiry $e, string $body, User $actor): EnquiryNote
    {
        return $e->notes()->create([
            'author_user_id' => $actor->id,
            'body' => $body,
        ]);
    }

    public function assign(Enquiry $e, int $userId, User $actor): void
    {
        $e->update(['assigned_user_id' => $userId]);
    }

    public function changeStatus(Enquiry $e, string $status, User $actor): void
    {
        $e->update(['status' => $status]);
    }
}
