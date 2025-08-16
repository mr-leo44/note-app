<div id="showCourseModal-{{ $course->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full justify-center items-center flex">
    <div class="relative w-full max-w-2xl h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $course->name }}
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="showCourseModal-{{ $course->id }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-end mb-2">
                    <button type="button" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-1 px-3 rounded flex items-center gap-2" data-modal-target="assignPromotionModal-{{ $course->id }}" data-modal-toggle="assignPromotionModal-{{ $course->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Assigner une promotion
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2">Promotion</th>
                                <th class="px-4 py-2">Crédit EC</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($course->promotions as $promotion)
                                <tr>
                                    <td class="px-4 py-2 font-semibold">{{ $promotion->name }}</td>
                                    <td class="px-4 py-2">{{ $promotion->pivot->maxima ?? '-' }}</td>
                                    <td class="px-4 py-2 flex gap-2">
                                        <button type="button" class="bg-sky-100 hover:bg-sky-200 p-1.5 rounded" title="Modifier le maxima" data-modal-target="editMaximaModal-{{ $course->id }}-{{ $promotion->id }}" data-modal-toggle="editMaximaModal-{{ $course->id }}-{{ $promotion->id }}">
                                            <x-icons.pencil-square />
                                        </button>
                                        <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Retirer la promotion" data-modal-target="deletePromotionModal-{{ $course->id }}-{{ $promotion->id }}" data-modal-toggle="deletePromotionModal-{{ $course->id }}-{{ $promotion->id }}">
                                            <x-icons.trash />
                                        </button>
                                        <x-courses.edit-maxima-modal :course="$course" :promotion="$promotion" />
                                        <x-courses.delete-promotion-modal :course="$course" :promotion="$promotion" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-400 py-4">Aucune promotion assignée à ce cours.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-courses.assign-promotion-modal :course="$course" />
            </div>
            <div class="flex justify-end p-4 border-t border-gray-200 rounded-b dark:border-gray-700">
                <button type="button" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded" data-modal-hide="showCourseModal-{{ $course->id }}">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
