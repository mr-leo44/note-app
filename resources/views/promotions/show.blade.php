<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('promotions.index') }}"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour
                </a>
                <h1 class="text-2xl font-bold">{{ $promotion->name }}</h1>
            </div>
            @if (auth()->user()->account->accountable_type === \App\Models\Admin::class)
                <button id="openModalBtn" data-modal-target="createStudentModal" data-modal-toggle="createStudentModal"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                    type="button">
                    + Nouvel Etudiant
                </button>
            @endif
        </div>
    </x-slot>
    <div class="container mx-auto py-8 px-4">
        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @elseif (session('warning'))
            <x-alert type="warning">{{ session('warning') }}</x-alert>
        @elseif (session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
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
                    @foreach ($promotion->students as $student)
                        @php
                            $isAdmin = auth()->user()->account->accountable_type === 'App\Models\Admin' ? true : false;
                            $currentPromotion = $student->promotions()->wherePivot('status', 'en cours')->first();
                            $currentSession = \App\Models\ResultSession::where('current', true)->first();
                            $currentResult = \App\Models\Result::where('result_session_id', $currentSession->id)
                                ->where('student_id', $student->id)
                                ->first();
                        @endphp
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
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
                                @if ($isAdmin)
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded"
                                        title="Modifier" data-student-id="{{ $student->id }}"
                                        data-modal-target="editStudentModal-{{ $student->id }}"
                                        data-modal-toggle="editStudentModal-{{ $student->id }}">
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded"
                                        title="Supprimer" data-modal-target="deleteStudentModal-{{ $student->id }}"
                                        data-modal-toggle="deleteStudentModal-{{ $student->id }}">
                                        <x-icons.trash />
                                    </button>
                                @elseif (!$currentResult || $currentResult->status !== \App\Enums\StudentPromotionStatus::PUBLISHED->value)
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded"
                                        title="Modifier les assignations" data-student-id="{{ $student->id }}"
                                        data-modal-target="assignResultToStudentModal-{{ $student->id }}"
                                        data-modal-toggle="assignResultToStudentModal-{{ $student->id }}">
                                        <x-icons.file-pen />
                                    </button>
                                @endif
                                @if ($currentResult && $currentResult->status === \App\Enums\StudentPromotionStatus::COMPLETE->value)
                                    <button type="button" title="Publier Résultats"
                                        class="bg-green-100 hover:bg-green-200 p-1.5 rounded"
                                        onclick="publishResult({{ $student->id }}, {{ $currentResult->id }}, '{{ $student->name }}')">
                                        <x-icons.check-circle />
                                    </button>
                                @endif
                                <x-students.edit-student-modal :student="$student" />
                                <x-students.delete-student-modal :student="$student" />
                                <x-students.assign-result-to-student :student="$student" :currentPromotion="$currentPromotion" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-students.create-student />
        @push('scripts')
            @vite(['resources/js/app.js'])
            <script>
                function publishResult(studentId, currentResultId, studentName) {
                    fetch(`/students/${studentId}/results/${currentResultId}/publish`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        })
                        .then(res => res.json())
                        .then(data => {
                            location.reload();
                            const alert = document.createElement('div');
                            alert.className =
                                'fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-blue-100 border border-blue-300 text-blue-800 px-6 py-3 rounded-lg shadow-lg flex items-center gap-2';
                            alert.innerHTML = `
                            <svg class='w-5 h-5 text-blue-600' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z' /></svg>
                            <span>Les résultats pour la session en cours de l'étudiant <b>${studentName}</b> ont été publié avec succès</span>
                            <button type="button" class="ml-4 text-blue-800 hover:text-blue-900 focus:outline-none" aria-label="Fermer" onclick="this.closest('div').remove()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                                `;
                            document.body.appendChild(alert);
                            setTimeout(() => {
                                if (document.body.contains(alert)) alert.remove();
                            }, 3000);
                        })
                        .catch(() => {
                            const errorAlert = document.createElement('div');
                            errorAlert.className =
                                'fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-red-400 border border-red-300 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2';
                            errorAlert.innerHTML = `
                                <svg class='w-5 h-5 text-white' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z' /></svg>
                                <span>Erreur lors de la publication des résultats pour la session en cours</span>
                                <button type="button" class="ml-4 text-white focus:outline-none" aria-label="Fermer" onclick="this.closest('div').remove()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            `;
                            document.body.appendChild(errorAlert);
                            setTimeout(() => {
                                if (document.body.contains(errorAlert)) errorAlert.remove();
                            }, 9000);
                        });
                }
                document.addEventListener('DOMContentLoaded', function() {
                    let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default ||
                        window.DataTable);
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
                                    'block', 'w-full', 'p-2', 'text-sm', 'text-gray-900', 'border',
                                    'border-gray-300', 'rounded-lg', 'bg-gray-50', 'focus:ring-blue-500',
                                    'focus:border-blue-500'
                                );
                            }
                            const dtSelect = wrapper.querySelector('.datatable-selector');
                            if (dtSelect) {
                                dtSelect.classList.add(
                                    'block', 'w-full', 'rounded-lg', 'border', 'border-gray-300', 'bg-gray-50',
                                    'py-2', 'pl-2', 'text-sm', 'text-gray-900', 'focus:border-blue-600',
                                    'focus:ring-2', 'focus:ring-blue-600/20', 'focus:bg-white', 'transition',
                                    'duration-200', 'appearance-none'
                                );
                            }
                            const dtDropdownLabel = wrapper.querySelector('.datatable-dropdown label');
                            if (dtDropdownLabel) {
                                dtDropdownLabel.classList.add('flex', 'gap-2', 'items-center', 'text-sm',
                                    'text-gray-700', 'dark:text-gray-400', 'flex-shrink-0', 'min-w-fit',
                                    'whitespace-nowrap');
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
