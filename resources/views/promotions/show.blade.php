<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('promotions.index') }}"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-sky-700 bg-sky-100 hover:bg-sky-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="hidden md:inline">Retour</span>
                </a>
                <h1 class="text-base md:text-2xl font-bold">{{ $promotion->name }}</h1>
            </div>
            @if (auth()->user()->account->accountable_type === \App\Models\Admin::class)
                <button id="openModalBtn" data-modal-target="createStudentModal" data-modal-toggle="createStudentModal"
                    class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                    title="Ajouter un nouvel étudiant">
                    + <span class="hidden md:inline">Nouvel étudiant</span>
                </button>
            @endif
        </div>
    </x-slot>

    <div class="container mx-auto py-12 px-4">
        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @elseif (session('warning'))
            <x-alert type="warning">{{ session('warning') }}</x-alert>
        @elseif (session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
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
        $isAdmin = auth()->user()->account->accountable_type === 'App\Models\Admin' ? true : false;
        $currentPeriod = \App\Models\Period::where('current', true)->first();
        $currentSemester = $currentPeriod->semesters()->where('current', true)->first();
        $currentSession = $currentSemester->result_sessions()->where('current', true)->first();
        // $students = $promotion->students()->get();
        $periodSemesters = \App\Models\Semester::where('period_id', $currentPeriod->id)->get() ?? null;
    @endphp
    @if ($students->isEmpty())
        <div class="text-center text-gray-500 py-8">Aucun étudiant enregistré pour cette promotion.</div>
    @else
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
                    @foreach ($students as $student)
                        @php
                            $currentResult = $currentSession
                                ? \App\Models\Result::where(
                                    'result_session_id',
                                    $currentSession->id,
                                )
                                    ->where('student_id', $student->id)
                                    ->first()
                                : null;
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
                                <button id="dropdown-results-button" type="button"
                                    class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded"
                                    title="Voir les résultats de l'année en cours" aria-expanded="false"
                                    data-dropdown-toggle="dropdown-{{ $student->id }}">
                                    <span class="sr-only">Open user menu</span>
                                    <x-icons.eye />
                                </button>
                                <div class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700"
                                    id="dropdown-{{ $student->id }}">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdown-results-button" role="none">
                                        @foreach ($periodSemesters as $semester)
                                            <li>
                                                <button
                                                    class="block px-4 py-2 w-full text-start hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                    data-semester-id="{{ $semester->id }}"
                                                    data-modal-target="showCurrentResultsModal-{{ $student->id }}-{{ $semester->id }}"
                                                    data-modal-toggle="showCurrentResultsModal-{{ $student->id }}-{{ $semester->id }}">{{ $semester->name }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                </button>
                                @if ($isAdmin)
                                    <button type="button" class="bg-sky-100 hover:bg-sky-200 p-1.5 rounded"
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
                                    <button type="button" class="bg-sky-100 hover:bg-sky-200 p-1.5 rounded"
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
                                @foreach ($periodSemesters as $semester)
                                    <x-promotions.show-current-results :student="$student" :currentPromotion="$promotion"
                                        :semester="$semester" />
                                @endforeach
                                <x-students.edit-student-modal :student="$student" />
                                <x-students.delete-student-modal :student="$student" />
                                <x-students.assign-result-to-student :student="$student" :currentPromotion="$promotion" />
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
                    }
                });
    
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
                                'fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-sky-100 border border-sky-300 text-sky-800 px-6 py-3 rounded-lg shadow-lg flex items-center gap-2';
                            alert.innerHTML = `
                            <svg class='w-5 h-5 text-sky-600' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z' /></svg>
                            <span>Les résultats pour la session en cours de l'étudiant <b>${studentName}</b> ont été publié avec succès</span>
                            <button type="button" class="ml-4 text-sky-800 hover:text-sky-900 focus:outline-none" aria-label="Fermer" onclick="this.closest('div').remove()">
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
                            }, 3000);
                        });
                }
            </script>
        @endpush
    @endif
    <x-students.create-student :promotion="$promotion" />
</x-app-layout>
