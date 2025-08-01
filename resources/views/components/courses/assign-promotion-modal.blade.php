<div id="assignPromotionModal-{{ $course->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full justify-center items-center flex">
    <div class="relative w-full max-w-lg h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Assigner une promotion à ce cours
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="assignPromotionModal-{{ $course->id }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('courses.assignPromotion', $course) }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="promotion_id-{{ $course->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Promotion</label>
                    <select id="promotion_id-{{ $course->id }}" name="promotion_id" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5" required>
                        <option value="">Sélectionner une promotion</option>
                        @foreach(App\Models\Promotion::orderBy('name')->get() as $promotion)
                            <option value="{{ $promotion->id }}">{{ $promotion->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="maxima-{{ $course->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Maxima</label>
                    <input type="number" step="0.01" min="0" max="100" id="maxima-{{ $course->id }}" name="maxima" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded">Assigner</button>
                </div>
            </form>
        </div>
    </div>
</div>
