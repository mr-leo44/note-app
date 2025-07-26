<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des promotions</h1>
                        @if (auth()->user()->account->accountable_type === \App\Models\Admin::class)

            <button id="openModalBtn" data-modal-target="createPromotionModal" data-modal-toggle="createPromotionModal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                type="button">
                + Nouvelle promotion
            </button>
            @endif
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
    @if ($promotions->isEmpty())
        <div class="text-center text-gray-500 py-8">Aucune promotion enregistrée.</div>
    @else
        <div class="overflow-x-auto" id="promotionsTableWrapper">
            <table id="promotionsTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Nom</th>
                        <th scope="col" class="px-6 py-3">Nom court</th>
                        <th scope="col" class="px-6 py-3">Département</th>
                        <th scope="col" class="px-6 py-3">Faculté</th>
                        <th scope="col" class="px-6 py-3">Nbre Etudiants</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
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
                            <td class="px-6 py-4 font-semibold">{{ $promotion->name }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $promotion->short_name }}</td>
                            <td class="px-6 py-4">{{ $promotion->department->name ?? '' }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $faculty->name }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $promotion->students()->count() }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('promotions.show', $promotion) }}"
                                    class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir">
                                    <x-icons.eye />
                                </a>
                                <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded"
                                    title="Modifier" data-promotion-id="{{ $promotion->id }}"
                                    data-modal-target="editPromotionModal-{{ $promotion->id }}"
                                    data-modal-toggle="editPromotionModal-{{ $promotion->id }}">
                                    <x-icons.pencil-square />
                                </button>
                                <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded"
                                    title="Supprimer" data-promotion-id="{{ $promotion->id }}"
                                    data-action-url="{{ route('promotions.destroy', $promotion) }}"
                                    data-modal-target="deletePromotionModal"
                                    data-modal-toggle="deletePromotionModal">
                                    <x-icons.trash />
                                </button>
                                <x-promotions.edit-promotion-modal :promotion="$promotion" :departments="$promotions->pluck('department')->unique('id')->filter()->values()" />
                                <x-promotions.delete-promotion-modal />
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
    <x-promotions.create-promotion-modal />
</x-app-layout>
