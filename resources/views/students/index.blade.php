<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des étudiants</h1>
            <button id="openModalBtn" data-modal-target="createStudentModal" data-modal-toggle="createStudentModal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                type="button">
                + Nouvel Etudiant
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

        @if ($students->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucun étudiant enregistrée.</div>
        @else
            <div class="overflow-x-auto" id="studentsTableWrapper">
                <table id="studentsTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Nom</th>
                            <th class="px-6 py-3">Matricule</th>
                            <th class="px-6 py-3">Promotion</th>
                            <th class="px-6 py-3">Statut</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $student->name }}</td>
                                <td class="px-6 py-4">{{ $student->matricule }}</td>
                                @php
                                    $currentPromotion = $student->promotions()->wherePivot('status', 'en cours')->first();
                                @endphp
                                <td class="px-6 py-4">
                                    {{ $currentPromotion ? $currentPromotion->short_name : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $currentPromotion ? ($currentPromotion->pivot->status === 'en cours' ? 'Admis' : ucfirst($currentPromotion->pivot->status)) : '-' }}
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$students" />
            </div>
@push('scripts')
@vite(['resources/js/app.js'])
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default || window.DataTable);
        if (DataTableClass) {
            const dt = new DataTableClass('#studentsTable', {
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
            // Style la barre de recherche et le wrapper
            setTimeout(() => {
                const wrapper = document.getElementById('studentsTableWrapper');
                // Flex container pour search + pagination DataTables
                const dtTop = wrapper.querySelector('.datatable-top');
                if (dtTop) {
                    dtTop.classList.remove('block'); // retire block si présent
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
                // Style le select de pagination DataTable
                const dtSelect = wrapper.querySelector('.datatable-selector');
                if (dtSelect) {
                    dtSelect.classList.add(
                        'block',
                        'w-full',
                        'rounded-lg',
                        'border',
                        'border-gray-300',
                        'bg-gray-50',
                        'py-2', // padding vertical plus important
                        'pl-2', // padding horizontal plus important
                        'text-sm',
                        'text-gray-900',
                        'focus:border-blue-600',
                        'focus:ring-2',
                        'focus:ring-blue-600/20',
                        'focus:bg-white',
                        'transition',
                        'duration-200',
                        'appearance-none'
                    );
                }
                // Style le label du select (datatable-dropdown > label)
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

        <x-students.create-student />
    </div>
</x-app-layout>
