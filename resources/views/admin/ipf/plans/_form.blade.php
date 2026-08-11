@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Plan Name</label>
        <input type="text" name="name" value="{{ old('name', $plan->name) }}"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="2"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description', $plan->description) }}</textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (days)</label>
        <input type="number" min="1" name="duration_days" value="{{ old('duration_days', $plan->duration_days) }}"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        @error('duration_days') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Down Payment (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="down_payment_percent"
               value="{{ old('down_payment_percent', $plan->down_payment_percent) }}"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        @error('down_payment_percent') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Calculation Method</label>
        <select name="calculation_method" id="calculation_method"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            @php $method = old('calculation_method', $plan->calculation_method ?? 'fixed'); @endphp
            <option value="fixed" @selected($method === 'fixed')>Fixed daily split (financed amount ÷ days)</option>
            <option value="remaining_balance_percentage" @selected($method === 'remaining_balance_percentage')>
                Percentage of remaining balance, daily
            </option>
        </select>
        @error('calculation_method') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div id="daily_rate_wrapper">
        <label class="block text-sm font-medium text-gray-700 mb-1">Daily Rate (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="daily_rate_percent"
               value="{{ old('daily_rate_percent', $plan->daily_rate_percent) }}"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        <p class="mt-1 text-xs text-gray-500">Only used for the "percentage of remaining balance" method.</p>
        @error('daily_rate_percent') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2 flex items-center gap-2">
        <input type="checkbox" name="is_active" id="is_active" value="1"
               @checked(old('is_active', $plan->is_active ?? true))
               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        <label for="is_active" class="text-sm text-gray-700">Active (visible to customers when choosing a plan)</label>
    </div>

</div>

<script>
    // Only show the daily-rate field when it's actually relevant to the chosen method.
    (function () {
        var methodSelect = document.getElementById('calculation_method');
        var wrapper = document.getElementById('daily_rate_wrapper');

        function toggle() {
            wrapper.style.display = methodSelect.value === 'remaining_balance_percentage' ? '' : 'none';
        }

        methodSelect.addEventListener('change', toggle);
        toggle();
    })();
</script>