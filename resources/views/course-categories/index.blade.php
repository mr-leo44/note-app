<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <h1 class="text-base md:text-2xl font-bold">Catégories de cours</h1>
            <button id="openModalBtn" data-modal-target="createCourseCategoryModal" data-modal-toggle="createCourseCategoryModal"
                class="block text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                type="button" title="Ajouter un cours">
                + <span class="hidden md:inline">Ajouter catégorie de cours</span>
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
    @if ($course_categories->isEmpty())
        <div class="text-center text-gray-500 py-8">Aucune categorie enregistrée.</div>
    @else
        <div class="overflow-x-auto" id="courseCategoriesTableWrapper">
            <table id="courseCategoriesTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Nom (UE)</th>
                        <th scope="col" class="px-6 py-3">Code UE</th>
                        <th scope="col" class="px-6 py-3">Credit UE</th>
                        <th scope="col" class="px-6 py-3">Catégorie UE</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($course_categories as $course_category)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                            <td class="px-6 py-4">
                                {{ ($course_categories->currentPage() - 1) * $course_categories->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 font-semibold">{{ $course_category->name }}</td>
                            <td class="px-6 py-4">{{ $course_category->short_name }}</td>
                            <td class="px-6 py-4">{{ $course_category->ue }}</td>
                            <td class="px-6 py-4">{{ $course_category->category_alias }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('course-categories.show', $course_category) }}"
                                    class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir">
                                    <x-icons.eye />
                                </a>
                                <button type="button" class="bg-sky-100 hover:bg-sky-200 p-1.5 rounded"
                                    title="Modifier" data-course_category-id="{{ $course_category->id }}"
                                    data-modal-target="editCourseCategoryModal-{{ $course_category->id }}"
                                    data-modal-toggle="editCourseCategoryModal-{{ $course_category->id }}">
                                    <x-icons.pencil-square />
                                </button>
                                <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded"
                                    title="Supprimer" data-course_category-id="{{ $course_category->id }}"
                                    data-action-url="{{ route('course-categories.destroy', $course_category) }}"
                                    data-modal-target="deleteCourseCategoryModal" data-modal-toggle="deleteCourseCategoryModal">
                                    <x-icons.trash />
                                </button>
                                <x-course-categories.edit-course-category-modal :course_category="$course_category" />
                                <x-course-categories.delete-course-category-modal />
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
                        const dt = new DataTableClass('#courseCategoriesTable', {
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

    <x-course-categories.create-course-category />
</x-app-layout>
