<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Dodaj članove na projekt') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto">
        @if (session('error'))
        <div class="bg-red-200 text-red-800 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        @if (session('success'))
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('projects.add-members', ['project' => 0]) }}" id="add-members-form">
            @csrf


            <div class="mb-4">
                <label for="project_id" class="block font-medium text-sm text-gray-700">Odaberite projekt:</label>
                <select name="project_id" id="project_id" class="mt-1 block w-full" required>
                    <option value="">-- Odaberite projekt --</option>
                    @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Odaberite korisnike:</label>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    @foreach ($users as $user)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="users[]" value="{{ $user->id }}">
                        <span>{{ $user->name }} ({{ $user->email }})</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Dodaj članove
            </button>
        </form>
    </div>


    <script>
        const form = document.getElementById('add-members-form');
        const projectSelect = document.getElementById('project_id');

        form.addEventListener('submit', function(e) {
            const selectedProject = projectSelect.value;
            if (!selectedProject) {
                e.preventDefault();
                alert('Molimo odaberite projekt.');
                return;
            }

            const action = form.getAttribute('action').replace('/0', '/' + selectedProject);
            form.setAttribute('action', action);
        });
    </script>
</x-app-layout>