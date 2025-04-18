<!-- resources/views/tasks/applications.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Prijave za: ') }} {{ $task->naziv }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">{{ __('Prijavljeni studenti') }}</h3>

        @foreach($task->applications as $application)
        <div class="mb-4">
            <p><strong>{{ $application->user->name }}</strong></p>
            <p>{{ $application->user->email }}</p>

            <form action="{{ route('applications.accept', $application->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md">
                    {{ $application->prihvaceno ? 'Prihvaćeno' : 'Prihvati' }}
                </button>
            </form>
        </div>
        @endforeach
    </div>
</x-app-layout>