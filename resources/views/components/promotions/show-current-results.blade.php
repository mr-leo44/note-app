<div id="showCurrentResultsModal-{{ $student->id }}-{{ $session->id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full flex justify-center items-center">
    <div class="relative p-4 w-full max-w-3xl h-full md:h-auto">
        @php
            $currentPeriod = \App\Models\Period::where('current', true)->first();
            $coursesByPromotion = \DB::table('course_promotion')
                ->join('courses', 'course_promotion.course_id', '=', 'courses.id')
                ->where('course_promotion.promotion_id', $currentPromotion->id)
                ->select('courses.*', 'course_promotion.*')
                ->get();
            $result = \App\Models\Result::where('student_id', $student->id)
                ->where('result_session_id', $session->id)
                ->first();
            $notes = $result->notes ?? [];
            $totalMaxima = 0;
            $totalNotes = 0;
        @endphp
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Resultats de l'étudiant {{ $student->name }} - {{ $session->name }} - {{ $currentPeriod->name }}
                </h3>
                <button type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-hide="showCurrentResultsModal-{{ $student->id }}-{{ $session->id }}">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Fermer</span>
                </button>
            </div>
            <div class="p-6 space-y-6">
                @if (empty($result) || !(Auth::user()))
                    <div class="text-center text-gray-500 py-8">
                        <div class="text-center text-gray-500 py-6">
                            <svg class="mx-auto mb-4 w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
                            </svg>
                            <p class="text-lg text-gray-800 dark:text-gray-200">Aucun résultat disponible pour cette
                                session.</p>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto" id="currentresultContent">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">#</th>
                                    <th scope="col" class="px-6 py-3">Cours</th>
                                    <th scope="col" class="px-6 py-3">Maxima</th>
                                    <th scope="col" class="px-6 py-3">Côte</th>
                                </tr>
                            </thead>
                            <tbody class="overflow-y-auto">
                                @foreach ($coursesByPromotion as $course)
                                    @php
                                        $totalMaxima += $course->maxima;
                                        $totalNotes += $notes[$course->name] ?? 0;
                                    @endphp
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 transition">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 font-semibold">{{ $course->name }}</td>
                                        <td class="px-6 py-4">{{ (int) $course->maxima }}</td>
                                        <td class="px-6 py-4">{{ $notes[$course->name] ?? 0 }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td colspan="3" class="px-6 py-4 font-semibold">Total</td>
                                    <td class="px-6 py-4 font-semibold dark:text-white">{{ $totalNotes . '/' . $totalMaxima }}</td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td colspan="3" class="px-6 py-4 font-semibold">Pourcentage</td>
                                    <td class="px-6 py-4 font-semibold dark:text-white">{{ $result->percentage }}</td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td colspan="3" class="px-6 py-4 font-semibold">Mention</td>
                                    <td class="px-6 py-4 font-semibold dark:text-white">{{ $result->mention->label() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
