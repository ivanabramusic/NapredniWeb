<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-10">
        <h2 class="text-2xl font-bold mb-5">Dodaj novi projekt</h2>

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block font-medium text-gray-700">Naziv projekta</label>
                <input type="text" id="name" name="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 p-2" value="{{ old('name') }}">
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>


            <div class="mb-4">
                <label for="description" class="block font-medium text-gray-700">Opis projekta</label>
                <textarea id="description" name="description" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 p-2">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="price" class="block font-medium text-gray-700">Cijena projekta (€)</label>
                <input type="number" id="price" name="price" min="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 p-2" value="{{ old('price') }}">
                @error('price') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>


            <div class="mb-4">
                <label for="start_date" class="block font-medium text-gray-700">Datum početka</label>
                <input type="date" id="start_date" name="start_date" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 p-2" value="{{ old('start_date') }}">
                @error('start_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="end_date" class="block font-medium text-gray-700">Datum završetka</label>
                <input type="date" id="end_date" name="end_date" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 p-2" value="{{ old('end_date') }}">
                @error('end_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>


            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Stvori projekt</button>
                <a href="{{ route('dashboard') }}" class="ml-4 text-gray-600 hover:text-gray-900">Odustani</a>
            </div>
        </form>
    </div>
</x-app-layout>