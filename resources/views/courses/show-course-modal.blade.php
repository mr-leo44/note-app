<div id="showCourseModal-{{ $course->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full justify-center items-center flex">
    <div class="relative w-full max-w-lg h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Détails du cours
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="showCourseModal-{{ $course->id }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex flex-col gap-2">
                    <div>
                        <span class="font-semibold">Nom :</span> {{ $course->name }}
                    </div>
                    <div>
                        <span class="font-semibold">Code :</span> {{ $course->code }}
                    </div>
                    <div>
                        <span class="font-semibold">Créé le :</span> {{ $course->created_at->format('d/m/Y') }}
                    </div>
                    <div>
                        <span class="font-semibold">Mis à jour le :</span> {{ $course->updated_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
            <div class="flex justify-end p-4 border-t border-gray-200 rounded-b dark:border-gray-700">
                <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" data-modal-hide="showCourseModal-{{ $course->id }}">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
