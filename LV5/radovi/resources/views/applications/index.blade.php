<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Radovi za prijavu
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
        @endif

        @foreach($tasks as $task)
        <div class="bg-white p-4 mb-4 shadow rounded">
            <h3 class="text-lg font-semibold">{{ $task->naziv_hr }} / {{ $task->naziv_en }}</h3>
            <p class="text-gray-600">{{ $task->zadatak }}</p>
            <p class="text-sm text-gray-500 mt-1">Tip studija: {{ $task->tip_studija }}</p>

            @if(in_array($task->id, $appliedTaskIds))
            <p class="mt-2 text-green-700 font-medium">Prijavljeno</p>
            @else
            <form method="POST" action="{{ route('applications.apply', $task->id) }}" class="mt-3">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Prijavi se</button>
            </form>
            @endif
        </div>
        @endforeach
    </div>
</x-app-layout>