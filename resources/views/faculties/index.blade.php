<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des facultés</h1>
            <button id="openModalBtn" data-modal-target="createFacultyModal" data-modal-toggle="createFacultyModal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                type="button">
                + Nouvelle faculté
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

        @if ($faculties->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucune faculté enregistrée.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Abréviation</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faculties as $faculty)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">{{ ($faculties->currentPage() - 1) * $faculties->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $faculty->name }}</td>
                                <td class="px-6 py-4">{{ $faculty->short_name }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="{{ route('faculties.show', $faculty) }}" class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir">
                                        <x-icons.eye />
                                    </a>
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier" data-faculty-id="{{ $faculty->id }}" data-modal-target="editFacultyModal-{{ $faculty->id }}" data-modal-toggle="editFacultyModal-{{ $faculty->id }}">
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Supprimer" data-faculty-id="{{ $faculty->id }}" data-action-url="{{ route('faculties.destroy', $faculty) }}" data-modal-target="deleteFacultyModal" data-modal-toggle="deleteFacultyModal">
                                        <x-icons.trash />
                                    </button>
                                    <x-faculties.edit-faculty-modal :faculty="$faculty" />
                                    <x-faculties.delete-faculty-modal />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$faculties" />
            </div>
        @endif

        <x-faculties.create-faculty />
    </div>
</x-app-layout>
