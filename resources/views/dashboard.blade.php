<x-app-layout>
    @php

    @endphp
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Vue d'ensemble</h1>
            @if ($currentPeriod)
                <button
                    class="block text-white dark:text-gray-900 bg-sky-700 dark:bg-gray-200 hover:bg-sky-800 dark:hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-base px-5 py-2.5 text-center"
                    type="button">
                    {{ $currentSession->name - $currentPeriod->name }}
                </button>
            @endif
        </div>
    </x-slot>
    <div class="py-16 dark:text-white">

        <div class="my-4">
            <h3 class="text-2xl font-semibold mb-4">Statistiques</h3>
            <div class="grid grid-cols-4 gap-4">
                <div
                    class="border border-gray-400 dark:border-gray-600 dark:text-white bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex flex-col gap-2">
                        <span class="text-4xl font-bold">{{ $promotions->count() }}</span>
                        <span class="text-base text-gray-600 dark:text-gray-400">Promotions</span>
                    </div>
                </div>
                <div
                    class="border border-gray-400 dark:border-gray-600 dark:text-white bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex flex-col gap-2">
                        <span class="text-4xl font-bold">{{ $students ? $students->count() : 0 }}</span>
                        <span class="text-base text-gray-600 dark:text-gray-400">Etudiants</span>
                    </div>
                </div>
                <div
                    class="border border-gray-400 dark:border-gray-600 dark:text-white bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex flex-col gap-2">
                        <span class="text-4xl font-bold">{{ $juries->count() }}</span>
                        <span class="text-base text-gray-600 dark:text-gray-400">Jurys</span>
                    </div>
                </div>
                <div
                    class="border border-gray-400 dark:border-gray-600 dark:text-white bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex flex-col gap-2">
                        <span class="text-4xl font-bold">{{ $courses->count() }}</span>
                        <span class="text-base text-gray-600 dark:text-gray-400">Cours</span>
                    </div>
                </div>

            </div>
        </div>

        <div class="my-4">
            <h3 class="text-2xl font-semibold mb-4">Suivi des Publications</h3>
            @if ($promotions->isEmpty())
                <div class="text-center text-gray-500 py-8">Aucune promotion enregistrée.</div>
            @else
                <div class="overflow-x-auto" id="promotionsTableWrapper">
                    <table id="promotionsTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">Nom</th>
                                <th scope="col" class="px-6 py-3">Département</th>
                                <th scope="col" class="px-6 py-3">Section</th>
                                <th scope="col" class="px-6 py-3">Nbre Etudiants</th>
                                <th scope="col" class="px-6 py-3">Resultats Publiés</th>
                                <th scope="col" class="px-6 py-3">Statut</th>
                                <th scope="col" class="px-6 py-3">Date Publication</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($promotions as $promotion)
                                @php
                                    $department = $promotion->department;
                                    $faculty = \App\Models\Faculty::find($department->faculty_id);
                                @endphp
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                    <td class="px-6 py-4">
                                        {{ ($promotions->currentPage() - 1) * $promotions->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold" title="{{ $promotion->name }}">
                                        {{ $promotion->short_name }}</td>
                                    <td class="px-6 py-4 font-semibold" title="{{ $department->name }}">
                                        {{ $department->short_name ?? '' }}</td>
                                    <td class="px-6 py-4 font-semibold" title="{{ $faculty->name }}">
                                        {{ $faculty->short_name }}</td>
                                    <td class="px-6 py-4 font-semibold">{{ $promotion->students()->count() }}</td>
                                    <td class="px-6 py-4 font-semibold">{{ $publishedResultByPromotion }}</td>
                                    <td class="px-6 py-4 font-semibold">
                                        {{ $promotion->resultStatus ? $promotion->resultStatus->status->label() : 'Non publié' }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold">
                                        {{ $promotion->resultStatus ? \Carbon\Carbon::parse($promotion->resultStatus->updated_at)->translatedFormat('d F Y') : '-' }}
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
                                const dt = new DataTableClass('#promotionsTable', {
                                    searchable: true,
                                    sortable: true,
                                    perPage: 5,
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

        </div>
    </div>
</x-app-layout>
