<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="AI extraction jobs"
            subtitle="Provider usage, costs and review queue."
            :breadcrumbs="[
                ['label' => 'Reports', 'href' => route('admin.reports.index')],
                ['label' => 'AI extraction'],
            ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            <div class="mt-card">
                <div class="overflow-x-auto">
                    <table class="mt-table">
                        <thead>
                            <tr>
                                <th>Document</th>
                                <th>Provider</th>
                                <th>Status</th>
                                <th>Tokens</th>
                                <th>Cost (₹)</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $job)
                                <tr>
                                    <td>{{ $job->supplierDocument->original_filename }}</td>
                                    <td>{{ $job->provider ?? '—' }}</td>
                                    <td><x-status-pill :status="$job->status" /></td>
                                    <td>{{ ($job->prompt_tokens ?? 0) + ($job->completion_tokens ?? 0) }}</td>
                                    <td>{{ $job->estimated_cost_inr ? '₹'.number_format($job->estimated_cost_inr, 4) : '—' }}</td>
                                    <td class="text-ink-500">{{ $job->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-ink-400 py-8">No extraction jobs yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-ink-200/70">{{ $jobs->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
