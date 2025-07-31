<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des périodes</h1>
            <button id="openModalBtn" data-modal-target="createPeriodModal" data-modal-toggle="createPeriodModal"
                class="block text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                type="button">
                + Nouvelle période
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

    @if ($periods->isEmpty())
        <div class="text-center text-gray-500 py-8">Aucune période enregistrée.</div>
    @else
        <div class="overflow-x-auto" id="periodsTableWrapper">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="periodsTable">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Nom</th>
                        <th scope="col" class="px-6 py-3">Statut</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($periods as $period)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                            <td class="px-6 py-4">
                                {{ ($periods->currentPage() - 1) * $periods->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $period->name }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $period->current ? 'En cours' : 'Ecoulé' }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('periods.show', $period->id) }}" class="bg-gray-100 p-1.5 rounded" title="Voir">
                                    <x-icons.eye />
                                </a>
                                <button type="button" class="bg-sky-100 hover:bg-sky-200 p-1.5 rounded"
                                    title="Modifier" data-period-id="{{ $period->id }}"
                                    data-modal-target="editPeriodModal-{{ $period->id }}"
                                    data-modal-toggle="editPeriodModal-{{ $period->id }}">
                                    <x-icons.pencil-square />
                                </button>
                                <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded"
                                    title="Supprimer" data-period-id="{{ $period->id }}"
                                    data-modal-target="deletePeriodModal-{{ $period->id }}"
                                    data-modal-toggle="deletePeriodModal-{{ $period->id }}">
                                    <x-icons.trash />
                                </button>
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
                        const dt = new DataTableClass('#periodsTable', {
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
    <x-periods.create-period-modal />
    @foreach ($periods as $period)
        <x-periods.edit-period-modal :period="$period" />
        <x-periods.delete-period-modal :period="$period" />
    @endforeach
</x-app-layout>
