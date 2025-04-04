<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Moji projekti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">Projekti u kojima ste lider:</h3>
                    @if($leaderProjects->isEmpty())
                    <p>Nemate projekte u kojima ste lider.</p>
                    @else
                    <ul>
                        @foreach($leaderProjects as $project)
                        <li>
                            {{ $project->name }} - {{ $project->description }}
                            <a href="{{ route('projects.edit', $project) }}" class="ml-2 text-blue-500 hover:underline">Uredi</a>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    <h3 class="text-lg font-semibold mt-6">Projekti u kojima ste član:</h3>
                    @if($memberProjects->isEmpty())
                    <p>Nemate projekte u kojima ste član.</p>
                    @else
                    <ul>
                        @foreach($memberProjects as $project)
                        <li>{{ $project->name }} - {{ $project->description }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>