<div id="assignResultToStudentModal-{{ $student->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 md:inset-0 h-modal md:h-full flex justify-center items-center">
    <div class="relative p-4 w-full maw-w-md lg:max-w-4xl h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="assignResultToStudentModal-{{ $student->id }}">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Fermer</span>
            </button>
            <div class="p-6">
                @php
                    $currentPeriod = \App\Models\Period::where('current', true)->first();
                    $assignedCourses = $currentPromotion->courses()->get();
                @endphp
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Publier Resultat de {{ $student->name }}</h3>
                <form method="POST" action="{{ route('students.assignResults', [$student, $currentPeriod]) }}">
                    @csrf
                    <div class="flex justify-between items-center gap-4">
                        <div class="mb-4 w-[30%]">
                            <label for="current" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Année Académique</label>
                            <input type="text" name="current" id="current" value="{{ old('current', $currentPeriod->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" disabled>
                        </div>
                        <div class="mb-4 w-[70%]">
                            <label for="session" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Session</label>
                            <select name="session" id="session" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 appearance-none required">
                                <option value="">Sélectionner une session</option> 
                                @foreach(App\Enums\ResultSession::cases() as $session)
                                    <option value="{{ $session->name }}" :selected="session == {{ $session->name }}">{{ $session->value }}</option>
                                @endforeach
                            </select>
                            @error('session')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Côte(s) à assigner</label>
                        <div class="mb-2 lg:grid grid-cols-4 gap-2">
                            @foreach ($assignedCourses as $index => $course)
                                <div>
                                    @php
                                        $coursePromotion = \DB::table('course_promotion')
                                            ->where('course_id', $course->id)
                                            ->where('promotion_id', $currentPromotion->id)
                                            ->first();
                                        $maxima = $coursePromotion ? $coursePromotion->maxima : 100; // Fallback maxima if not found
                                    @endphp
                                    <label for="note-{{ $index }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $course->name }}</label>
                                    <input type="number" min="1" max="{{ $maxima }}" name="notes[]" placeholder="entre 1 et {{ $maxima }} " id="note-{{ $index }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('notes.' . $index) border-red-500 @enderror" required>
                                </div>
                            @endforeach
                            </div>
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
