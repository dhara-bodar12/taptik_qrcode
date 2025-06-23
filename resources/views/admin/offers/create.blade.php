@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold text-gray-800">Create Offer</h2>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <form action="{{ route('admin.offers.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Offer Type</label>
                <select name="type" id="offer-type" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Select Offer Type --</option>
                    <option value="Discount">Discount</option>
                    <option value="Free Gift">Free Gift</option>
                    <option value="Cashback">Cashback</option>
                    <option value="Coupon">Coupon</option>
                    <option value="Feedback">Feedback</option>
                </select>
                @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Offer Value -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Offer Value</label>
                <input type="text" name="value"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                       required>
            </div>

            <!-- Percentage (Optional) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Percentage (if applicable)</label>
                <input type="number" name="percentage" min="1" max="100"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Valid From -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Valid From</label>
                <input type="date" name="valid_from"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Valid Until -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Valid Until</label>
                <input type="date" name="valid_until"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Terms & Conditions -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                <textarea name="terms" rows="4"
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Success Message</label>
                <input type="text" name="success_message"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                       required>
            </div>

            <hr class="my-6">

            <h3 class="text-lg font-bold mb-2">Offer Fields</h3>
            <div id="offer-fields-container"></div>

            <button type="button" id="add-field"
                    class="mt-2 px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                + Add Field
            </button>

            <div class="mt-6 text-right">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" type="submit">
                    Save Offer
                </button>
            </div>
        </form>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addFieldBtn = document.getElementById('add-field');
    const container = document.getElementById('offer-fields-container');

    addFieldBtn.addEventListener('click', () => {
        const index = container.children.length;

        const html = `
            <div class="field-wrapper border-b pb-3 mb-3">
                <div class="flex gap-2 flex-wrap items-center">
                    <input type="text" name="fields[${index}][label]" placeholder="Label"
                           class="border rounded px-2 py-1 w-1/5" required>

                    <input type="text" name="fields[${index}][name]" placeholder="Name"
                           class="border rounded px-2 py-1 w-1/5" required>

                    <select name="fields[${index}][type]" class="field-type border rounded px-2 py-1 w-1/5"
                            onchange="toggleOptionsInput(this, ${index})" required>
                        <option value="">Type</option>
                        <option value="text">Text</option>
                        <option value="email">Email</option>
                        <option value="number">Number</option>
                        <option value="textarea">Textarea</option>
                        <option value="date">Date</option>
                        <option value="datetime">Date & Time</option>
                        <option value="tel">Phone</option>
                        <option value="url">URL</option>
                        <option value="password">Password</option>
                        <option value="image">Image</option>
                        <option value="file">File</option>
                        <option value="radio">Radio</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="select">Select</option>
                    </select>

                    <label class="flex items-center gap-1 text-sm">
                        <input type="checkbox" name="fields[${index}][required]" value="1"> Required
                    </label>

                    <button type="button"
                            class="text-red-600 hover:underline text-sm"
                            onclick="this.closest('.field-wrapper').remove()">
                        Remove
                    </button>
                </div>

                <!-- Options for radio/checkbox -->
                <div class="field-options hidden mt-2" id="field-options-${index}">
                    <label class="block text-sm text-gray-700 mb-1">Options (Builder)</label>
                    <div class="options-list space-y-2 mb-2" data-index="${index}"></div>
                    <button type="button" class="text-sm text-green-700 hover:underline"
                            onclick="addOption(${index})">+ Add Option</button>

                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', html);
    });
});

// Show/hide options container for radio/checkbox
function toggleOptionsInput(select, index) {
    const wrapper = document.getElementById(`field-options-${index}`);
    if (['radio', 'checkbox', 'select'].includes(select.value)) {
        wrapper.classList.remove('hidden');
    } else {
        wrapper.classList.add('hidden');
    }
}

// Add an individual option input group
function addOption(fieldIndex) {
    const optionsList = document.querySelector(`#field-options-${fieldIndex} .options-list`);
    const optionIndex = optionsList.children.length;

    const optionHtml = `
        <div class="option-row flex gap-2 items-center">
            <input type="text" name="fields[${fieldIndex}][options][${optionIndex}][label]" placeholder="Option Label"
                   class="border rounded px-2 py-1 w-1/3" required>
            <input type="text" name="fields[${fieldIndex}][options][${optionIndex}][value]" placeholder="Option Value"
                   class="border rounded px-2 py-1 w-1/3" required>
            <button type="button" class="text-red-600 hover:underline text-sm"
                    onclick="this.closest('.option-row').remove()">Remove</button>
        </div>
    `;

    optionsList.insertAdjacentHTML('beforeend', optionHtml);
}
</script>
