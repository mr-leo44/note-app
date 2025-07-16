<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('promotions.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    Retour
                </a>
                <h1 class="text-2xl font-bold">{{ $promotion->name }}</h1>
            </div>
            <button id="openModalBtn" data-modal-target="createStudentModal" data-modal-toggle="createStudentModal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                type="button">
                + Nouvel Etudiant
            </button>
        </div>
    </x-slot>
    <div class="container mx-auto py-8 px-4">
        <div class="overflow-x-auto" id="promotionStudentsTableWrapper">
            <table id="promotionStudentsTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Nom</th>
                        <th class="px-6 py-3">Matricule</th>
                        <th class="px-6 py-3">Statut</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($promotion->students as $student)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $student->name }}</td>
                            <td class="px-6 py-4">{{ $student->matricule }}</td>
                            <td class="px-6 py-4">
                                {{ $student->pivot->status === 'en cours' ? 'Admis' : ucfirst($student->pivot->status) }}
                            </td>
                            <td class="px-6 py-4 flex gap-2">
                                <button class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir Resultats">
                                    <x-icons.eye />
                                </button>
                                <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier" data-student-id="{{ $student->id }}" data-modal-target="editStudentModal-{{ $student->id }}" data-modal-toggle="editStudentModal-{{ $student->id }}">
                                    <x-icons.pencil-square />
                                </button>
                                <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Supprimer" data-modal-target="deleteStudentModal-{{ $student->id }}" data-modal-toggle="deleteStudentModal-{{ $student->id }}">
                                    <x-icons.trash />
                                </button>
                                <x-students.edit-student-modal :student="$student" />
                                <x-students.delete-student-modal :student="$student" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="datatable-empty text-center">Aucun étudiant trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-students.create-student />
        @push('scripts')
        @vite(['resources/js/app.js'])
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default || window.DataTable);
                if (DataTableClass) {
                    const dt = new DataTableClass('#promotionStudentsTable', {
                        searchable: true,
                        sortable: true,
                        labels: {
                            placeholder: "Recherche...",
                            perPage: "par page",
                            perPageSelect: "Afficher",
                            noRows: "Aucun étudiant trouvé.",
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
                        const wrapper = document.getElementById('promotionStudentsTableWrapper');
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
                        // Centrage du message vide DataTable
                        const emptyCell = wrapper.querySelector('.datatable-empty');
                        if (emptyCell) {
                            emptyCell.classList.add('text-center');
                            const table = wrapper.querySelector('table');
                            const colCount = table ? (table.tHead ? table.tHead.rows[0].cells.length : 1) : 1;
                            emptyCell.setAttribute('colspan', colCount);
                        }
                        wrapper.classList.add('pb-4');
                    }, 100);
                }
            });
        </script>
        @endpush
    </div>
</x-app-layout>
