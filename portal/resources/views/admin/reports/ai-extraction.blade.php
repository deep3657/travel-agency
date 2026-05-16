<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}" class="text-gray-400 hover:text-gray-600">← Reports</a>
            <h2 class="font-semibold text-xl text-gray-800">AI Extraction Jobs</h2>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Document</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provider</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tokens</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost (₹)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($jobs as $job)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $job->supplierDocument->original_filename }}</td>
                                <td class="px-4 py-3 text-sm">{{ $job->provider ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @php $colors = ['pending'=>'bg-gray-100 text-gray-600','processing'=>'bg-blue-100 text-blue-700','completed'=>'bg-green-100 text-green-700','failed'=>'bg-red-100 text-red-700'] @endphp
                                    <span class="px-2 py-0.5 rounded text-xs {{ $colors[$job->status] ?? '' }}">{{ $job->status }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ ($job->prompt_tokens ?? 0) + ($job->completion_tokens ?? 0) }}</td>
                                <td class="px-4 py-3 text-sm">{{ $job->estimated_cost_inr ? '₹'.number_format($job->estimated_cost_inr, 4) : '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $job->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No extraction jobs yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">{{ $jobs->links() }}</div>
        </div>
    </div>
</x-app-layout>
