<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des cours</h1>
            <button id="openModalBtn" data-modal-target="createCourseModal" data-modal-toggle="createCourseModal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                + Nouveau cours
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
        @if ($courses->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucun cours enregistré.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $course)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">{{ ($courses->currentPage() - 1) * $courses->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $course->name }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button type="button" class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir" data-modal-target="showCourseModal-{{ $course->id }}" data-modal-toggle="showCourseModal-{{ $course->id }}">
                                        <x-icons.eye />
                                    </button>
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier" data-course-id="{{ $course->id }}" data-modal-target="editCourseModal-{{ $course->id }}" data-modal-toggle="editCourseModal-{{ $course->id }}">
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Supprimer" data-course-id="{{ $course->id }}" data-modal-target="deleteCourseModal-{{ $course->id }}" data-modal-toggle="deleteCourseModal-{{ $course->id }}">
                                        <x-icons.trash class="w-6 h-6 text-red-600" />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$courses" />
            </div>
        @endif
    </div>
    <x-courses.create-course-modal />
    @foreach ($courses as $course)
        <x-courses.show-course-modal :course="$course" />
        <x-courses.edit-course-modal :course="$course" />
        <x-courses.delete-course-modal :course="$course" />
    @endforeach
</x-app-layout>
