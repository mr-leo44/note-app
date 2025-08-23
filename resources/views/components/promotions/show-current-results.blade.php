<div id="showCurrentResultsModal-{{ $student->id }}-{{ $semester->id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full flex justify-center items-center">
    <div class="relative p-4 w-full max-w-3xl h-full md:h-auto">
        @php
            $currentPeriod = \App\Models\Period::where('current', true)->first();
            $coursesByPromotion = \DB::table('course_promotion')
                ->join('courses', 'course_promotion.course_id', '=', 'courses.id')
                ->where('course_promotion.promotion_id', $currentPromotion->id)
                ->select('courses.*', 'course_promotion.*')
                ->get();
            $semesterSessions = $semester->result_sessions()->orderBy('id')->get();
            $semesterResults = [];
            $hasValidatedFirstSession = false;
            $totalCredits = 0;
            $capitalizedCredits = 0;

            foreach ($semesterSessions as $session) {
                $result =
                    \App\Models\Result::where('student_id', $student->id)
                        ->where('result_session_id', $session->id)
                        ->first() ?? null;

                if ($result) {
                    $semesterResults[] = $result;
                }
            }

            $semesterResults = collect($semesterResults);

            // Calculer le total des crédits indépendamment des résultats
            foreach ($coursesByPromotion as $course) {
                $totalCredits += (int) $course->maxima;
            }

            // Calculer les crédits capitalisés seulement si on a des résultats
            if ($semesterResults->isNotEmpty()) {
                $notes = $semesterResults->first()->notes ?? [];
                foreach ($coursesByPromotion as $course) {
                    if (isset($notes[$course->name]) && $notes[$course->name] >= 10) {
                        $capitalizedCredits += (int) $course->maxima;
                    }
                }
            }
        @endphp
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Resultats de l'étudiant {{ $student->name }} - {{ $semester->name }} - {{ $currentPeriod->name }}
                </h3>
                <button type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-hide="showCurrentResultsModal-{{ $student->id }}-{{ $semester->id }}">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Fermer</span>
                </button>
            </div>

            <div class="flex items-center justify-between px-4 border-b rounded-t dark:border-gray-700">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center"
                        id="default-tab-{{ $student->id }}-{{ $semester->id }}"
                        data-tabs-toggle="#default-tab-content-{{ $student->id }}-{{ $semester->id }}" role="tablist">
                        @foreach ($semesterSessions as $session)
                            @php
                                $result = $session->results()->where('student_id', $student->id)->first() ?? null;

                                if ($result) {
                                    // Vérifie si la première session est validée
                                    if (
                                        $session->name === \App\Enums\ResultSession::S1->label() &&
                                        isset($result->decision) &&
                                        $result->decision === 'V'
                                    ) {
                                        $hasValidatedFirstSession = true;
                                    }
                                }
                                $isDisabled =
                                    $hasValidatedFirstSession &&
                                    $session->name === \App\Enums\ResultSession::S2->label();
                                $tabClasses =
                                    'inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300';

                                if ($isDisabled) {
                                    $tabClasses .= ' cursor-not-allowed opacity-50';
                                } elseif ($loop->first) {
                                    $tabClasses .= ' text-blue-600 border-blue-600 active';
                                } else {
                                    $tabClasses .= ' border-transparent';
                                }
                            @endphp
                            <li class="me-2" role="presentation">
                                <button class="{{ $tabClasses }}"
                                    id="tab-{{ $session->short_name }}-{{ $student->id }}"
                                    data-tabs-target="#content-{{ $session->short_name }}-{{ $student->id }}"
                                    type="button" role="tab"
                                    aria-controls="content-{{ $session->short_name }}-{{ $student->id }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    @if ($isDisabled) disabled @endif>
                                    {{ $session->name }}
                                    @if ($isDisabled)
                                        <span class="ml-2 text-xs text-gray-500">(Session 1 validée)</span>
                                    @endif
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- PDF export button (opens in new tab) -->
                <a href="{{ route('results.pdf', ['student' => $student->id, 'semester' => $semester->id, 'promotion' => $currentPromotion->id ?? null]) }}"
                    target="_blank" rel="noopener" +
                    class="inline-flex items-center gap-2 text-sm bg-white/5 hover:bg-white/10 text-gray-800 dark:text-white rounded-md px-2 py-1.5 shadow-sm"
                    title="Générer le PDF">
                    <svg class="w-6 h-6 text-red-500 dark:text-red-600" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2 2 2 0 0 0 2 2h12a2 2 0 0 0 2-2 2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2V4a2 2 0 0 0-2-2h-7Zm-6 9a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0v-1h.5a2.5 2.5 0 0 0 0-5H5Zm1.5 3H6v-1h.5a.5.5 0 0 1 0 1Zm4.5-3a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h1.376A2.626 2.626 0 0 0 15 15.375v-1.75A2.626 2.626 0 0 0 12.375 11H11Zm1 5v-3h.375a.626.626 0 0 1 .625.626v1.748a.625.625 0 0 1-.626.626H12Zm5-5a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0v-1h1a1 1 0 1 0 0-2h-1v-1h1a1 1 0 1 0 0-2h-2Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="hidden md:block text-sm">Générer le PDF</span>
                </a>
            </div>
            <div id="default-tab-content-{{ $student->id }}-{{ $semester->id }}" class="p-4">
                @if ($semester->current === 0)
                    <div class="flex flex-col items-center justify-center py-8">
                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <p class="text-xl font-medium text-gray-500 dark:text-gray-400 mb-2">
                            Aucun résultat disponible
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Les résultats pour ce Semestre seront affichés ici une fois publiés.
                        </p>
                    </div>
                @else
                    @foreach ($semesterSessions as $session)
                        @php
                            $result = $session->results()->where('student_id', $student->id)->first() ?? null;
                            $isDisabled =
                                $hasValidatedFirstSession && $session->name === \App\Enums\ResultSession::S2->label();
                        @endphp
                        <div class="{{ $loop->first ? '' : 'hidden' }} p-4 rounded-lg bg-gray-50 dark:bg-gray-800"
                            id="content-{{ $session->short_name }}-{{ $student->id }}" role="tabpanel"
                            aria-labelledby="tab-{{ $session->short_name }}-{{ $student->id }}">
                            @if (is_null($result))
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    <p class="text-xl font-medium text-gray-500 dark:text-gray-400 mb-2">
                                        Aucun résultat disponible
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Les résultats pour cette session seront affichés ici une fois publiés.
                                    </p>
                                </div>
                            @else
                                <div class="overflow-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Cours
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Crédit EC
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Cote /20
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                                            @foreach ($coursesByPromotion as $course)
                                                <tr>
                                                    <td
                                                        class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $course->name }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                                                        {{ (int) $course->maxima }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center font-bold">
                                                        @if (isset($result->notes[$course->name]))
                                                            {{ $result->notes[$course->name] }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <td class="px-6 py-1.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                                    colspan="2">
                                                    Total credits (EC)
                                                </td>
                                                <td
                                                    class="px-6 py-1.5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ $totalCredits }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-1.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                                    colspan="2">
                                                    Moyenne Semestre
                                                </td>
                                                <td
                                                    class="px-6 py-1.5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ number_format($result->average, 5) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-1.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                                    colspan="2">
                                                    Credits capitalisés
                                                </td>
                                                <td
                                                    class="px-6 py-1.5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ $capitalizedCredits }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-1.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                                    colspan="2">
                                                    Décision semestre
                                                </td>
                                                <td
                                                    class="px-6 py-1.5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ $result->decision }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-1.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                                    colspan="2">
                                                    Pourcentage
                                                </td>
                                                <td
                                                    class="px-6 py-1.5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ $result->percentage }}%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-1.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                                    colspan="2">
                                                    Mention
                                                </td>
                                                <td
                                                    class="px-6 py-1.5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ $result->mention }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
