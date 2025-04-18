<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.add_task') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            <div class="mb-4">
                <label for="naziv_hr" class="block text-gray-700 font-medium">{{ __('messages.title_hr') }}</label>
                <input type="text" name="naziv_hr" id="naziv_hr" class="mt-1 block w-full border-gray-300 rounded" required>
            </div>

            <div class="mb-4">
                <label for="naziv_en" class="block text-gray-700 font-medium">{{ __('messages.title_en') }}</label>
                <input type="text" name="naziv_en" id="naziv_en" class="mt-1 block w-full border-gray-300 rounded" required>
            </div>

            <div class="mb-4">
                <label for="zadatak" class="block text-gray-700 font-medium">{{ __('messages.task_description') }}</label>
                <textarea name="zadatak" id="zadatak" rows="4" class="mt-1 block w-full border-gray-300 rounded" required></textarea>
            </div>

            <div class="mb-4">
                <label for="tip_studija" class="block text-gray-700 font-medium">{{ __('messages.type') }}</label>
                <select name="tip_studija" id="tip_studija" class="mt-1 block w-full border-gray-300 rounded" required>
                    <option value="strucni">{{ __('messages.strucni') }}</option>
                    <option value="preddiplomski">{{ __('messages.preddiplomski') }}</option>
                    <option value="diplomski">{{ __('messages.diplomski') }}</option>
                </select>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('messages.save') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>