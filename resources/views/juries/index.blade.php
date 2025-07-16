<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des jurys</h1>
            <button id="openModalBtn" data-modal-target="createJuryModal" data-modal-toggle="createJuryModal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                type="button">
                + Nouveau jury
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
        @if ($juries->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucun jury enregistré.</div>
        @else
            <div class="overflow-x-auto" id="juriesTableWrapper">
                <table id="juriesTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Pseudo</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Promotions assignées</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($juries as $jury)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">
                                    {{ ($juries->currentPage() - 1) * $juries->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $jury->name }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $jury->username }}</td>
                                <td class="px-6 py-4">{{ $jury->email }}</td>
                                <td class="px-6 py-4">
                                    @if ($jury->promotions->isNotEmpty())
                                        {{ $jury->promotions->pluck('short_name')->implode(', ') }}
                                    @else
                                        <span class="text-gray-400">Aucune</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex gap-2">
                                    <div class="mt-4 flex justify-center">
                                        <x-pagination :paginator="$juries" />
                                    </div>
                                    @push('scripts')
                                        @vite(['resources/js/app.js'])
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default ||
                                                    window.DataTable);
                                                if (DataTableClass) {
                                                    const dt = new DataTableClass('#juriesTable', {
                                                        searchable: true,
                                                        sortable: true,
                                                        labels: {
                                                            placeholder: "Recherche...",
                                                            perPage: "par page",
                                                            perPageSelect: "Afficher",
                                                            noRows: "Aucun jury trouvé.",
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
                                                        const wrapper = document.getElementById('juriesTableWrapper');
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
                                                                'block', 'w-full', 'p-2', 'text-sm', 'text-gray-900', 'border',
                                                                'border-gray-300', 'rounded-lg', 'bg-gray-50', 'focus:ring-blue-500',
                                                                'focus:border-blue-500'
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
                                                            );
                                                        }
                                                        // Style le label du select (datatable-dropdown > label)
                                                        const dtDropdownLabel = wrapper.querySelector('.datatable-dropdown label');
                                                        if (dtDropdownLabel) {
                                                            dtDropdownLabel.classList.add('flex', 'gap-2', 'items-center', 'text-sm',
                                                                'text-gray-700', 'dark:text-gray-400', 'flex-shrink-0', 'min-w-fit',
                                                                'whitespace-nowrap');
                                                        }
                                                        wrapper.classList.add('pb-4');
                                                    }, 100);
                                                }
                                            });
                                        </script>
                                    @endpush
                                    <button type="button" class="bg-yellow-100 hover:bg-yellow-200 p-1.5 rounded" title="Réinitialiser le mot de passe" onclick="resetJuryPassword({{ $jury->id }}, '{{ $jury->name }}')">
                                        <svg xmlns='http://www.w3.org/2000/svg' class='w-5 h-5 text-yellow-600' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3-1.343 3-3 3-3-1.343-3-3zm0 0V7m0 4v4m0 0c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z' /></svg>
                                    </button>
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier" data-jury-id="{{ $jury->id }}" data-modal-target="editJuryModal-{{ $jury->id }}" data-modal-toggle="editJuryModal-{{ $jury->id }}">
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Supprimer" data-jury-id="{{ $jury->id }}" data-modal-target="deleteJuryModal-{{ $jury->id }}" data-modal-toggle="deleteJuryModal-{{ $jury->id }}">
                                        <x-icons.trash />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$juries" />
            </div>
            @push('scripts')
                @vite(['resources/js/app.js'])
                <script>
                    function resetJuryPassword(juryId, juryName) {
                        fetch(`/juries/${juryId}/reset-password`, {
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
                            console.log(data);
                            
                            const pwd = data.password;
                            const alert = document.createElement('div');
                            alert.className = 'fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-blue-100 border border-blue-300 text-blue-800 px-6 py-3 rounded-lg shadow-lg flex items-center gap-2';
                            alert.innerHTML = `
                                <svg class='w-5 h-5 text-blue-600' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z' /></svg>
                                <span>Mot de passe généré pour <b>${juryName}</b> : <b>${pwd}</b></span>
                                <button type="button" class="ml-4 text-blue-800 hover:text-blue-900 focus:outline-none" aria-label="Fermer" onclick="this.closest('div').remove()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            `;
                            document.body.appendChild(alert);
                            setTimeout(() => { if(document.body.contains(alert)) alert.remove(); }, 9000);
                        })
                        .catch(() => {
                            alert('Erreur lors de la réinitialisation du mot de passe.');
                        });
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default || window.DataTable);
                        if (DataTableClass) {
                            const dt = new DataTableClass('#juriesTable', {
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
                                const wrapper = document.getElementById('juriesTableWrapper');
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
                                        'block', 'w-full', 'p-2', 'text-sm', 'text-gray-900', 'border',
                                        'border-gray-300', 'rounded-lg', 'bg-gray-50', 'focus:ring-blue-500',
                                        'focus:border-blue-500'
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
                                    dtDropdownLabel.classList.add('flex', 'gap-2', 'items-center', 'text-sm',
                                        'text-gray-700', 'dark:text-gray-400', 'flex-shrink-0', 'min-w-fit',
                                        'whitespace-nowrap');
                                }
                                wrapper.classList.add('pb-4');
                            }, 100);
                        }
                    });
                </script>
            @endpush

        @endif
    </div>
    <x-juries.create-jury-modal />
    @foreach ($juries as $jury)
        <x-juries.edit-jury-modal :jury="$jury" />
        <x-juries.delete-jury-modal :jury="$jury" />
    @endforeach
</x-app-layout>
