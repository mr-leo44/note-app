<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour
                </a>
                <h1 class="text-2xl font-bold">{{ $faculty->name }}</h1>
            </div>
            <button type="button"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2"
                data-modal-target="createDepartmentModal" data-modal-toggle="createDepartmentModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Ajouter
            </button>
            <x-departments.create-department-modal :faculties="App\Models\Faculty::orderBy('name')->get()" :facultyId="$faculty->id" />
        </div>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if (session('warning'))
            <x-alert type="warning">{{ session('warning') }}</x-alert>
        @endif
        @if ($errors->any())
            <x-alert type="error">
                <ul class="list-none pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif
    </div>

    @php
        $facultyDepartments = $faculty->departments()->orderBy('name')->paginate();
    @endphp

    @if ($facultyDepartments->isEmpty())
        <div class="text-center text-gray-500 py-8">Aucun département lié à cette faculté.</div>
    @else
        <div class="overflow-x-auto" id="facultyDepartmentsTableWrapper">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="facultyDepartmentsTable">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Nom</th>
                        <th scope="col" class="px-6 py-3">Abréviation</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($facultyDepartments as $department)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $department->name }}</td>
                            <td class="px-6 py-4">{{ $department->short_name }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('departments.show', $department) }}"
                                    class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir">
                                    <x-icons.eye />
                                </a>
                                <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded"
                                    title="Modifier" data-department-id="{{ $department->id }}"
                                    data-modal-target="editDepartmentModal-{{ $department->id }}"
                                    data-modal-toggle="editDepartmentModal-{{ $department->id }}">
                                    <x-icons.pencil-square />
                                </button>
                                <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded"
                                    title="Supprimer" data-department-id="{{ $department->id }}"
                                    data-action-url="{{ route('departments.destroy', $department) }}"
                                    data-modal-target="deleteDepartmentModal" data-modal-toggle="deleteDepartmentModal">
                                    <x-icons.trash />
                                </button>
                                <x-departments.edit-department-modal :department="$department" :faculties="App\Models\Faculty::orderBy('name')->get()" />
                                <x-departments.delete-department-modal />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @push('scripts')
            @vite(['resources/js/app.js'])
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default ||
                        window.DataTable);
                    if (DataTableClass) {
                        const dt = new DataTableClass('#facultyDepartmentsTable', {
                            searchable: true,
                            sortable: true,
                            labels: {
                                placeholder: "Recherche...",
                                perPage: "par page",
                                perPageSelect: "Afficher",
                                noRows: "Aucun résultat trouvé",
                                info: "Affichage de {start} à {end} sur {rows} entrées",
                                loading: "Chargement...",
                                infoFiltered: "(filtré à partir de {rows} entrées)",
                                first: "Premier",
                                last: "Dernier",
                                prev: "Précédent",
                                next: "Suivant"
                            }
                        });
                    }
                });
            </script>
        @endpush
    @endif
</x-app-layout>
