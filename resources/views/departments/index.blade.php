<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <div class="flex items-center gap-2">
                <h1 class="text-base md:text-2xl font-bold">Liste des départements</h1>
            </div>

            @php
                $sectionsCount = \App\Models\Faculty::count();
            @endphp
            <button type="button"
                class="@if($sectionsCount === 0) hidden @endif text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                data-modal-target="createDepartmentModal" data-modal-toggle="createDepartmentModal"
                title="Ajouter un département">
                + <span class="hidden md:inline">Ajouter un département</span>
            </button>
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
    @if ($departments->isEmpty())
        <div class="text-center text-gray-500 py-8">Aucun département lié à cette section.</div>
    @else
        <div class="overflow-x-auto" id="departmentsTableWrapper">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="departmentsTable">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Nom</th>
                        <th scope="col" class="px-6 py-3">Code</th>
                        <th scope="col" class="px-6 py-3">Section</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $department->name }}</td>
                            <td class="px-6 py-4">{{ $department->short_name }}</td>
                            <td class="px-6 py-4">{{ $department->faculty->name }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('departments.show', $department) }}"
                                    class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir">
                                    <x-icons.eye />
                                </a>
                                <button type="button" class="bg-sky-100 hover:bg-sky-200 p-1.5 rounded"
                                    title="Modifier" data-department-id="{{ $department->id }}"
                                    data-modal-target="editDepartmentModal-{{ $department->id }}"
                                    data-modal-toggle="editDepartmentModal-{{ $department->id }}">
                                    <x-icons.pencil-square />
                                </button>
                                <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded"
                                    title="Supprimer" data-department-id="{{ $department->id }}"
                                    data-modal-target="deleteDepartmentModal-{{ $department->id }}" data-modal-toggle="deleteDepartmentModal-{{ $department->id }}">
                                    <x-icons.trash />
                                </button>
                                <x-departments.edit-department-modal :department="$department" :faculties="App\Models\Faculty::orderBy('name')->get()" />
                                <x-departments.delete-department-modal :department="$department" />
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
                        const dt = new DataTableClass('#departmentsTable', {
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
    <x-departments.create-department-modal />
</x-app-layout>
