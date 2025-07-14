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
            <div class="mb-4 p-4 text-green-800 rounded-lg bg-green-100 dark:bg-green-200 dark:text-green-900">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 text-red-800 rounded-lg bg-red-100 dark:bg-red-200 dark:text-red-900">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
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
                                    @php
                                        // Icônes SVG pour les actions
                                        $editIcon = '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.6 2.6 0 1 1 3.677 3.677L7.5 20.203l-4.243.566a.75.75 0 0 1-.848-.848l.566-4.243L16.862 3.487Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75l1.5 1.5"/></svg>';
                                        $deleteIcon = '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
                                    @endphp
                                    <a href="{{ route('faculties.edit', $faculty) }}" class="hover:bg-blue-50 p-1 rounded" title="Modifier">{!! $editIcon !!}</a>
                                    <form action="{{ route('faculties.destroy', $faculty) }}" method="POST" onsubmit="return confirm('Supprimer cette faculté ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="hover:bg-red-50 p-1 rounded" title="Supprimer">{!! $deleteIcon !!}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <nav aria-label="Pagination">
                    <ul class="inline-flex -space-x-px text-sm">
                        @if ($faculties->onFirstPage())
                            <li>
                                <span class="px-3 py-2 ml-0 leading-tight text-gray-400 bg-white border border-gray-300 rounded-l-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-500">&laquo;</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $faculties->previousPageUrl() }}" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">&laquo;</a>
                            </li>
                        @endif
                        @foreach ($faculties->getUrlRange(1, $faculties->lastPage()) as $page => $url)
                            @if ($page == $faculties->currentPage())
                                <li>
                                    <span class="px-3 py-2 leading-tight text-blue-600 bg-blue-50 border border-blue-300 dark:bg-gray-700 dark:border-gray-700 dark:text-white">{{ $page }}</span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                        @if ($faculties->hasMorePages())
                            <li>
                                <a href="{{ $faculties->nextPageUrl() }}" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">&raquo;</a>
                            </li>
                        @else
                            <li>
                                <span class="px-3 py-2 leading-tight text-gray-400 bg-white border border-gray-300 rounded-r-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-500">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif

        <x-faculties._faculty-modal />
        <x-faculties.create-faculty />
    </div>
</x-app-layout>
