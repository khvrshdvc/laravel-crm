<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Companies</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your companies and organizations.
                </p>
            </div>

            <a href="{{ route('companies.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                + Add company
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-medium text-gray-500">Company</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Email</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Phone</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Website</th>
                            <th class="px-6 py-4 font-medium text-gray-500 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($companies as $company)
                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4">
                                    <a href="{{ route('companies.show', $company) }}"
                                        class="font-medium text-gray-900 hover:underline">
                                        {{ $company->name }}
                                    </a>
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $company->email ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $company->phone ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $company->website ?? '—' }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-3">
                                        <a href="{{ route('companies.edit', $company) }}"
                                            class="text-gray-600 hover:text-gray-900">
                                            Edit
                                        </a>

                                        <form action="{{ route('companies.destroy', $company) }}" method="POST"
                                            onsubmit="return confirm('Delete this company?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-gray-500">No companies found.</p>

                                    <a href="{{ route('companies.create') }}"
                                        class="inline-block mt-3 text-sm font-medium text-gray-900 hover:underline">
                                        Add your first company
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">
            {{ $companies->links() }}
        </div>
    </div>
</x-app-layout>
