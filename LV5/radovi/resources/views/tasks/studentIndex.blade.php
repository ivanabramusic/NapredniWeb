<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Radovi za prijavu') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Naziv rada</th>
                        <th class="py-2 px-4 border-b">Akcija</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $task->naziv }}</td>
                        <td class="py-2 px-4 border-b">
                            @if($task->applications->isNotEmpty())
                            <span class="text-green-600">Prijavljeno</span>
                            @else
                            <form action="{{ route('tasks.apply', $task->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Prijavi</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>