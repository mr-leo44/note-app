<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des sessions</h1>
            @if ($sessions->count() < count(App\Enums\ResultSession::cases()))
                <button id="openModalBtn" data-modal-target="createSessionModal" data-modal-toggle="createSessionModal"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                    type="button">
                    + Nouvelle session
                </button>
            @endif
        </div>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
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
    @if ($sessions->isEmpty())
        <div class="text-center text-gray-500 py-8">Aucune session enregistrée.</div>
    @else
        <div class="overflow-x-auto" id="sessionsTableWrapper">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="sessionsTable">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Nom</th>
                        <th scope="col" class="px-6 py-3">Période</th>
                        <th scope="col" class="px-6 py-3">Statut</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sessions as $session)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                            <td class="px-6 py-4">
                                {{ ($sessions->currentPage() - 1) * $sessions->perPage() + $loop->iteration }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $session->name }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $session->period->name }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $session->current ? 'En cours' : 'Ecoulé' }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded"
                                    title="Modifier" data-session-id="{{ $session->id }}"
                                    data-modal-target="editSessionModal-{{ $session->id }}"
                                    data-modal-toggle="editSessionModal-{{ $session->id }}">
                                    <x-icons.pencil-square />
                                </button>
                                <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded"
                                    title="Supprimer" data-session-id="{{ $session->id }}"
                                    data-modal-target="deleteSessionModal-{{ $session->id }}"
                                    data-modal-toggle="deleteSessionModal-{{ $session->id }}">
                                    <x-icons.trash />
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-center">
            <x-pagination :paginator="$sessions" />
        </div>
        @push('scripts')
            @vite(['resources/js/app.js'])
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default ||
                        window.DataTable);
                    if (DataTableClass) {
                        const dt = new DataTableClass('#sessionsTable', {
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
    <x-sessions.create-session-modal />
    @foreach ($sessions as $session)
        <x-sessions.edit-session-modal :session="$session" />
        <x-sessions.delete-session-modal :session="$session" />
    @endforeach
</x-app-layout>
