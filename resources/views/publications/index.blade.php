<x-app-layout>
    @php
        $isAdmin = auth()->user()->account->accountable_type === \App\Models\Admin::class;
        $completeResultsExists = \App\Models\ResultStatus::where('status', 'complete')->exists();
    @endphp
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <h1 class="text-base md:text-2xl font-bold">Liste des publications</h1>
            @if ($isAdmin && $completeResultsExists)
                <button type="button" title="Publier Résultats en ligne"
                    class="flex gap-2 items-center text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" title="Publier les résultats en ligne"
                    onclick="onlinePublishResults()">
                    <x-icons.check-circle />
                    <span class="hidden md:inline text-sm md:text-base">Publier les résultats</span>
                </button>
            @endif
        </div>
    </x-slot>
    <div class="container mx-auto px-4 py-20">
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
    @if ($publications->isEmpty())
        <div class="text-center text-gray-500 py-8">Aucune publication enregistrée.</div>
    @else
        <div class="overflow-x-auto" id="publicationsTableWrapper">
            <table id="publicationsTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Promotion</th>
                        <th scope="col" class="px-6 py-3">Nombre Etudiants</th>
                        <th scope="col" class="px-6 py-3">Resultats publiés</th>
                        <th scope="col" class="px-6 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($publications as $publication)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                            @php
                                $currentSession = \App\Models\ResultSession::where('current', true)->first();
                                $studentByPromotion = $publication->promotion
                                    ->students()
                                    ->wherePivot('status', 'en cours')
                                    ->get();
                                $promotionResultsCount = 0;
                                foreach ($studentByPromotion as $student) {
                                    $currentStudentResultExists = \App\Models\Result::where(
                                        'student_id',
                                        $student->id,
                                    )
                                        ->where('result_session_id', $currentSession->id)
                                        ->where('status', 'publié')
                                        ->exists();
                                    if ($currentStudentResultExists) {
                                        $promotionResultsCount++;
                                    }
                                }
                            @endphp
                            <td class="px-6 py-4">
                                {{ ($publications->currentPage() - 1) * $publications->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 font-semibold">{{ $publication->promotion->name }}</td>
                            <td class="px-6 py-4">{{ $studentByPromotion->count() }}</td>
                            <td class="px-6 py-4">{{ $promotionResultsCount }}</td>
                            <td class="px-6 py-4">
                                {{ $publication->status->label() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @push('scripts')
            @vite(['resources/js/app.js'])
            <script>
                function onlinePublishResults() {
                    fetch(`publications/publish-results`, {
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
                            <span>Les résultats pour la session en cours ont été publié avec succès</span>
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
                document.addEventListener('DOMContentLoaded', function() {
                    let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default ||
                        window.DataTable);
                    if (DataTableClass) {
                        const dt = new DataTableClass('#publicationsTable', {
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
