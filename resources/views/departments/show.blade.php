<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('departments.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    Retour
                </a>
                <h1 class="text-2xl font-bold">{{ $department->name }}</h1>
            </div>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2" data-modal-target="createPromotionModal" data-modal-toggle="createPromotionModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Ajouter
            </button>
            <x-promotions.create-promotion-modal :departments="App\Models\Department::orderBy('name')->get()" :departmentId="$department->id" />
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
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif
        @php
            $promotions = $department->promotions()?->paginate();
        @endphp
        @if ($promotions->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucune promotion liée à ce département.</div>
        @else
            <div class="overflow-x-auto" id="departmentPromotionsTableWrapper">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="departmentPromotionsTable">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Abbreviations</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($promotions as $promotion)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $promotion->name }}</td>
                                <td class="px-6 py-4">{{ $promotion->short_name }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="{{ route('promotions.show', $promotion) }}" class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir">
                                        <x-icons.eye />
                                    </a>
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier" data-promotion-id="{{ $promotion->id }}" data-modal-target="editPromotionModal-{{ $promotion->id }}" data-modal-toggle="editPromotionModal-{{ $promotion->id }}">
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Supprimer" data-promotion-id="{{ $promotion->id }}" data-action-url="{{ route('promotions.destroy', $promotion) }}" data-modal-target="deletePromotionModal" data-modal-toggle="deletePromotionModal">
                                        <x-icons.trash />
                                    </button>
                                    <x-promotions.edit-promotion-modal :promotion="$promotion" :departments="App\Models\Department::orderBy('name')->get()" />
                                    <x-promotions.delete-promotion-modal />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$promotions" />
            </div>
            @push('scripts')
                @vite(['resources/js/app.js'])
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        let DataTableClass = window.DataTable && (window.DataTable.DataTable || window.DataTable.default ||
                            window.DataTable);
                        if (DataTableClass) {
                            const dt = new DataTableClass('#departmentPromotionsTable', {
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
                            setTimeout(() => {
                                const wrapper = document.getElementById('departmentPromotionsTableWrapper');
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
                                wrapper.classList.add('pb-4');
                            }, 100);
                        }
                    });
                </script>
            @endpush
        @endif
    </div>
</x-app-layout>
