<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des départements</h1>
            <button id="openModalBtn" data-modal-target="createDepartmentModal" data-modal-toggle="createDepartmentModal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                type="button">
                + Nouveau département
            </button>
        </div>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if ($errors->any())
            <x-alert type="error">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        @if ($departments->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucun département enregistré.</div>
        @else
            <div class="overflow-x-auto" id="departmentsTableWrapper">
                <table id="departmentsTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Abréviation</th>
                            <th scope="col" class="px-6 py-3">Faculté</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">{{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $department->name }}</td>
                                <td class="px-6 py-4">{{ $department->short_name }}</td>
                                <td class="px-6 py-4">{{ $department->faculty->name ?? '' }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="{{ route('departments.show', $department) }}" class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir">
                                        <x-icons.eye />
                                    </a>
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier" data-department-id="{{ $department->id }}" data-modal-target="editDepartmentModal-{{ $department->id }}" data-modal-toggle="editDepartmentModal-{{ $department->id }}">
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Supprimer" data-department-id="{{ $department->id }}" data-action-url="{{ route('departments.destroy', $department) }}" data-modal-target="deleteDepartmentModal" data-modal-toggle="deleteDepartmentModal">
                                        <x-icons.trash />
                                    </button>
                                    <x-departments.edit-department-modal :department="$department" :faculties="$departments->pluck('faculty')->unique('id')->filter()->values()" />
                                    <x-departments.delete-department-modal />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$departments" />
            </div>
            @push('scripts')
            @vite(['resources/js/app.js'])
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default || window.DataTable);
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
                        setTimeout(() => {
                            const wrapper = document.getElementById('departmentsTableWrapper');
                            const dtTop = wrapper.querySelector('.datatable-top');
                            if (dtTop) {
                                dtTop.classList.remove('block');
                                dtTop.classList.add('flex', 'justify-between', 'items-center', 'mb-4', 'gap-4');
                            }
                            const dtSearch = wrapper.querySelector('.datatable-search');
                            if (dtSearch) {
                                dtSearch.classList.add('max-w-md', 'flex-1');
                            }
                            const searchInput = wrapper.querySelector('input[type="search"]');
                            if (searchInput) {
                                searchInput.classList.add(
                                    'block', 'w-full', 'p-2', 'text-sm', 'text-gray-900', 'border', 'border-gray-300', 'rounded-lg', 'bg-gray-50', 'focus:ring-blue-500', 'focus:border-blue-500'
                                );
                            }
                            const dtSelect = wrapper.querySelector('.datatable-selector');
                            if (dtSelect) {
                                dtSelect.classList.add(
                                    'block', 'w-full', 'rounded-lg', 'border', 'border-gray-300', 'bg-gray-50', 'py-2', 'pl-2', 'text-sm', 'text-gray-900', 'focus:border-blue-600', 'focus:ring-2', 'focus:ring-blue-600/20', 'focus:bg-white', 'transition', 'duration-200', 'appearance-none'
                                );
                            }
                            const dtDropdownLabel = wrapper.querySelector('.datatable-dropdown label');
                            if (dtDropdownLabel) {
                                dtDropdownLabel.classList.add('flex', 'gap-2', 'items-center', 'text-sm', 'text-gray-700', 'dark:text-gray-400', 'flex-shrink-0', 'min-w-fit', 'whitespace-nowrap');
                            }
                            wrapper.classList.add('pb-4');
                        }, 100);
                    }
                });
            </script>
            @endpush
        @endif
        <x-departments.create-department-modal />
    </div>
</x-app-layout>
