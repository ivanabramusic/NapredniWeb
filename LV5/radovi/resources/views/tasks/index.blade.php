<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Moji radovi') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Popis radova</h3>
                <a href="{{ route('tasks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                    Dodaj novi rad
                </a>
            </div>

            @if($tasks->isEmpty())
            <p class="text-gray-500">Nema dodanih radova.</p>
            @else
            <table class="min-w-full table-auto border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left text-sm text-gray-700 uppercase">
                        <th class="px-6 py-3 border-b">Naziv (HR)</th>
                        <th class="px-6 py-3 border-b">Naziv (EN)</th>
                        <th class="px-6 py-3 border-b">Tip studija</th>
                        <th class="px-6 py-3 border-b">Prijave</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 border-b">{{ $task->naziv }}</td>
                        <td class="px-6 py-4 border-b">{{ $task->naziv_en }}</td>
                        <td class="px-6 py-4 border-b capitalize">{{ $task->tip_studija }}</td>
                        <td class="px-6 py-4 border-b">
                            <!-- Klikabilan link koji vodi do stranice sa prijavama za taj rad -->
                            <a href="{{ route('tasks.applications', $task->id) }}" class="text-blue-600 hover:underline">
                                Pregledaj prijave
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</x-app-layout>