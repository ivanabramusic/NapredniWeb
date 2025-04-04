<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Uredi projekt: {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('projects.update', $project) }}">
                @csrf
                @method('PUT')

                @if (auth()->user()->id === $project->leader_id)

                <div class="mb-4">
                    <label class="block">Naziv</label>
                    <input type="text" name="name" value="{{ old('name', $project->name) }}" class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block">Opis</label>
                    <textarea name="description" class="w-full border rounded p-2">{{ old('description', $project->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block">Cijena</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $project->price) }}" class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block">Obavljeni poslovi</label>
                    <textarea name="completed_tasks" class="w-full border rounded p-2">{{ old('completed_tasks', $project->completed_tasks) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block">Početak</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}" class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block">Kraj</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $project->end_date->format('Y-m-d')) }}" class="w-full border rounded p-2">
                </div>
                @else

                <div class="mb-4">
                    <label class="block">Obavljeni poslovi</label>
                    <textarea name="completed_tasks" class="w-full border rounded p-2">{{ old('completed_tasks', $project->completed_tasks) }}</textarea>
                </div>
                @endif

                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Spremi</button>
            </form>
        </div>
    </div>
</x-app-layout>