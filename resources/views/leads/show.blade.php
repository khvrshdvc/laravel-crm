<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-8">

        <div class="mb-6">
            <a href="{{ route('leads.show', $lead) }}"
                class="text-sm text-gray-500 hover:text-gray-900 transition-colors">
                ← Back to lead details
            </a>
        </div>

        {{-- Convert to Deal Form --}}
        @if (($lead->status->value ?? $lead->status) !== 'converted')
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                    <h2 class="font-semibold text-gray-900 text-lg">
                        Convert to Deal
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Convert this lead into an active deal in your pipeline.
                    </p>
                </div>

                <form method="POST" action="{{ route('leads.convert', $lead) }}" class="p-6">
                    @csrf

                    <div class="space-y-6">

                        {{-- Deal title --}}
                        <div>
                            <label for="deal_title" class="block text-sm font-medium text-gray-700">
                                Deal Title <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="title" id="deal_title" value="{{ old('title', $lead->name) }}"
                                required
                                class="mt-2 block w-full px-4 py-2.5 rounded-lg border @error('title') border-red-500 @else border-gray-300 @enderror focus:border-gray-900 focus:ring-gray-900/10 focus:outline-none">

                            @error('title')
                                <p class="mt-1.5 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Amount --}}
                        <div>
                            <label for="deal_amount" class="block text-sm font-medium text-gray-700">
                                Deal Amount ($)
                            </label>

                            <input type="number" name="amount" id="deal_amount" step="0.01" min="0"
                                value="{{ old('amount') }}" placeholder="0.00"
                                class="mt-2 block w-full px-4 py-2.5 rounded-lg border @error('amount') border-red-500 @else border-gray-300 @enderror focus:border-gray-900 focus:ring-gray-900/10 focus:outline-none">

                            @error('amount')
                                <p class="mt-1.5 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Assigned To --}}
                        <div>
                            <label for="deal_assigned_to" class="block text-sm font-medium text-gray-700">
                                Assigned To
                            </label>

                            <select name="assigned_to" id="deal_assigned_to"
                                class="mt-2 block w-full px-4 py-2.5 bg-white rounded-lg border @error('assigned_to') border-red-500 @else border-gray-300 @enderror focus:border-gray-900 focus:ring-gray-900/10 focus:outline-none">
                                <option value="">Select manager</option>

                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('assigned_to', $lead->assigned_to) == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('assigned_to')
                                <p class="mt-1.5 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('leads.show', $lead) }}"
                            class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            Cancel
                        </a>

                        <button type="submit"
                            class="px-5 py-2.5 bg-green-600 text-gray-900 text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                            Convert to Deal
                        </button>
                    </div>

                </form>
            </div>
        @else
            {{-- Already converted --}}
            <div class="bg-green-50 border border-green-200 rounded-xl p-6 shadow-sm">

                <div class="flex items-center justify-between flex-wrap gap-4">

                    <div>
                        <h2 class="font-semibold text-green-900 text-lg">
                            Lead converted
                        </h2>

                        <p class="mt-1 text-sm text-green-700">
                            This lead has already been converted into a deal.
                        </p>
                    </div>

                    @if ($lead->deal)
                        <a href="{{ route('deals.show', $lead->deal) }}"
                            class="px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                            View Deal →
                        </a>
                    @endif

                </div>

            </div>
        @endif

    </div>
</x-app-layout>
