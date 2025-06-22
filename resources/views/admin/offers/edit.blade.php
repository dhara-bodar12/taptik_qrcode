@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold text-gray-800">Edit Offer</h2>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Offer Type</label>
                <select name="type" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Select Offer Type --</option>
                    @foreach(['Discount', 'Free Gift', 'Cashback', 'Coupon', 'Feedback'] as $type)
                        <option value="{{ $type }}" @selected($offer->type === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Success Message</label>
                <input type="text" name="success_message" value="{{ old('success_message', $offer->success_message) }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <hr class="my-6">

            <h3 class="text-lg font-bold mb-2">Offer Fields</h3>
            <div id="offer-fields-container">
                @foreach($offer->fields as $index => $field)
                    <div class="field-wrapper border-b pb-3 mb-3">
                        <div class="flex gap-2 flex-wrap items-center">
                            <input type="text" name="fields[{{ $index }}][label]" value="{{ $field->label }}" placeholder="Label"
                                   class="border rounded px-2 py-1 w-1/5" required>

                            <input type="text" name="fields[{{ $index }}][name]" value="{{ $field->name }}" placeholder="Name"
                                   class="border rounded px-2 py-1 w-1/5" required>

                            <select name="fields[{{ $index }}][type]" class="field-type border rounded px-2 py-1 w-1/5"
                                    onchange="toggleOptionsInput(this, {{ $index }})" required>
                                <option value="">Type</option>
                                @foreach(['text','email','number','textarea','date','datetime','tel','url','password','image','file','radio','checkbox'] as $type)
                                    <option value="{{ $type }}" @selected($field->type === $type)>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>

                            <label class="flex items-center gap-1 text-sm">
                                <input type="checkbox" name="fields[{{ $index }}][required]" value="1"
                                       {{ $field->required ? 'checked' : '' }}> Required
                            </label>

                            <button type="button" class="text-red-600 hover:underline text-sm"
                                    onclick="this.closest('.field-wrapper').remove()">
                                Remove
                            </button>
                        </div>

                        <!-- Options (radio/checkbox only) -->
                        <div class="field-options {{ in_array($field->type, ['radio', 'checkbox']) ? '' : 'hidden' }} mt-2" id="field-options-{{ $index }}">
                            <label class="block text-sm text-gray-700 mb-1">Options (Builder)</label>
                            <div class="options-list space-y-2 mb-2" data-index="{{ $index }}">
                                @foreach($field->options ?? [] as $optIndex => $option)
                                    <div class="option-row flex gap-2 items-center">
                                        <input type="text" name="fields[{{ $index }}][options][{{ $optIndex }}][label]"
                                               value="{{ $option['label'] ?? '' }}" placeholder="Option Label"
                                               class="border rounded px-2 py-1 w-1/3" required>
                                        <input type="text" name="fields[{{ $index }}][options][{{ $optIndex }}][value]"
                                               value="{{ $option['value'] ?? '' }}" placeholder="Option Value"
                                               class="border rounded px-2 py-1 w-1/3" required>
                                        <button type="button" class="text-red-600 hover:underline text-sm"
                                                onclick="this.closest('.option-row').remove()">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="text-sm text-green-700 hover:underline"
                                    onclick="addOption({{ $index }})">+ Add Option</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-field"
                    class="mt-2 px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                + Add Field
            </button>

            <div class="mt-6 text-right">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" type="submit">
                    Update Offer
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const addFieldBtn = document.getElementById('add-field');
    const container = document.getElementById('offer-fields-container');

    // Re-run toggleOptionsInput for existing fields
    document.querySelectorAll('.field-type').forEach((select, index) => {
        toggleOptionsInput(select, index);
    });

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
                    </select>

                    <label class="flex items-center gap-1 text-sm">
                        <input type="checkbox" name="fields[${index}][required]" value="1"> Required
                    </label>

                    <button type="button" class="text-red-600 hover:underline text-sm"
                            onclick="this.closest('.field-wrapper').remove()">Remove</button>
                </div>

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

function toggleOptionsInput(select, index) {
    const wrapper = document.getElementById(`field-options-${index}`);
    if (['radio', 'checkbox'].includes(select.value)) {
        wrapper?.classList.remove('hidden');
    } else {
        wrapper?.classList.add('hidden');
    }
}

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
@endsection
