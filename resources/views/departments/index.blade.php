<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des départements</h1>
            <button id="openModalBtn" data-modal-target="createDepartmentModal" data-modal-toggle="createDepartmentModal"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                type="button">
                + Nouveau département
            </button>
        </div>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        @if (session('success'))
            <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-100 dark:bg-green-200 dark:text-green-900" role="alert">
                <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 0 0-1.414 0L9 11.586 6.707 9.293a1 1 0 0 0-1.414 1.414l3 3a1 1 0 0 0 1.414 0l7-7a1 1 0 0 0 0-1.414Z"/><path d="M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16Zm0-2a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z"/></svg>
                <span class="sr-only">Succès</span>
                <div class="ms-2 text-sm font-medium">{{ session('success') }}</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-green-200 dark:text-green-600 dark:hover:bg-green-300" data-dismiss-target="#alert-success" aria-label="Fermer">
                    <span class="sr-only">Fermer</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l12 12M13 1L1 13"/></svg>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div id="alert-error" class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-100 dark:bg-red-200 dark:text-red-900" role="alert">
                <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0ZM9 9V5a1 1 0 1 1 2 0v4a1 1 0 0 1-2 0Zm1 8a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></svg>
                <span class="sr-only">Erreur</span>
                <div class="ms-2 text-sm font-medium">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300" data-dismiss-target="#alert-error" aria-label="Fermer">
                    <span class="sr-only">Fermer</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l12 12M13 1L1 13"/></svg>
                </button>
            </div>
        @endif

        @if ($departments->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucun département enregistré.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Abréviation</th>
                            <th scope="col" class="px-6 py-3">Faculté</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">{{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $department->name }}</td>
                                <td class="px-6 py-4">{{ $department->short_name }}</td>
                                <td class="px-6 py-4">{{ $department->faculty->name ?? '' }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="{{ route('departments.show', $department) }}" class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir">
                                        <x-icons.eye />
                                    </a>
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier" data-department-id="{{ $department->id }}" data-modal-target="editDepartmentModal-{{ $department->id }}" data-modal-toggle="editDepartmentModal-{{ $department->id }}">
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Supprimer" data-department-id="{{ $department->id }}" data-action-url="{{ route('departments.destroy', $department) }}" data-modal-target="deleteDepartmentModal" data-modal-toggle="deleteDepartmentModal">
                                        <x-icons.trash />
                                    </button>
                                    <x-departments.edit-department-modal :department="$department" :faculties="$departments->pluck('faculty')->unique('id')->filter()->values()" />
                                    <x-departments.delete-department-modal />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$departments" />
            </div>
        @endif
        <x-departments.create-department-modal />
    </div>
</x-app-layout>
