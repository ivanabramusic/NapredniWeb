<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                    @if (auth()->user()->role && auth()->user()->role->name === 'admin')
                    <div class="mt-4">
                        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">Korisnici</a>
                    </div>
                    @endif

                    @if(Auth::user()->role_id == 2)
                    <div class="mt-4">
                        <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">Radovi</a>
                    </div>
                    @endif

                    @if(Auth::user()->role_id == 3)
                    <div class="mt-4">
                        <a href="{{ route('tasks.studentIndex') }}" class="px-4 py-2 bg-green-600 text-white rounded-md">Radovi za prijavu</a>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>