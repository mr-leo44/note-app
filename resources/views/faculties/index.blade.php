<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des facultés</h1>
            <button id="openModalBtn" data-modal-target="createFacultyModal" data-modal-toggle="createFacultyModal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                type="button">
                + Nouvelle faculté
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

        @if ($faculties->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucune faculté enregistrée.</div>
        @else
            <div class="overflow-x-auto" id="facultiesTableWrapper">
                <table id="facultiesTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Abréviation</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faculties as $faculty)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">{{ ($faculties->currentPage() - 1) * $faculties->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $faculty->name }}</td>
                                <td class="px-6 py-4">{{ $faculty->short_name }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="{{ route('faculties.show', $faculty) }}" class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir">
                                        <x-icons.eye />
                                    </a>
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier" data-faculty-id="{{ $faculty->id }}" data-modal-target="editFacultyModal-{{ $faculty->id }}" data-modal-toggle="editFacultyModal-{{ $faculty->id }}">
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Supprimer" data-faculty-id="{{ $faculty->id }}" data-action-url="{{ route('faculties.destroy', $faculty) }}" data-modal-target="deleteFacultyModal" data-modal-toggle="deleteFacultyModal">
                                        <x-icons.trash />
                                    </button>
                                    <x-faculties.edit-faculty-modal :faculty="$faculty" />
                                    <x-faculties.delete-faculty-modal />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$faculties" />
            </div>
            @push('scripts')
            @vite(['resources/js/app.js'])
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default || window.DataTable);
                    if (DataTableClass) {
                        const dt = new DataTableClass('#facultiesTable', {
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
                            const wrapper = document.getElementById('facultiesTableWrapper');
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

        <x-faculties.create-faculty />
    </div>
</x-app-layout>
