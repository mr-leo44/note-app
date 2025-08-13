<div id="createPromotionModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full flex justify-center items-center">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="createPromotionModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Fermer</span>
            </button>
            <div class="p-6">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Créer une nouvelle promotion</h3>
                <form method="POST" action="{{ route('promotions.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 @error('name') border-red-500 @enderror" required>
                        @error('name')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="short_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom court</label>
                        <input type="text" name="short_name" id="short_name" value="{{ old('short_name') }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 @error('short_name') border-red-500 @enderror" required>
                        @error('short_name')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="faculty_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Section</label>
                        <div class="relative">
                            <select name="faculty_id" id="faculty_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 pr-10 appearance-none @error('faculty_id') border-red-500 @enderror" required>
                                <option value="">Sélectionner une section</option>
                                @foreach(App\Models\Faculty::orderBy('name')->get() as $faculty)
                                    <option value="{{ $faculty->id }}" @if(old('faculty_id') == $faculty->id) selected @endif>{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('faculty_id')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="department_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Département</label>
                        <div class="relative">
                            @php
                                $departments = App\Models\Department::query()->select(["id","name","faculty_id"])->orderBy("name")->get();
                            @endphp
                            <select name="department_id" id="department_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 pr-10 appearance-none @error('department_id') border-red-500 @enderror" required data-departments="{{ json_encode($departments) }}" data-old="{{ old('department_id') }}">
                                <option value="">Sélectionner un département</option>
                                {{-- Les options seront injectées dynamiquement par JS --}}
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('department_id')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="w-full text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Créer</button>
                </form>
                @push('scripts')
                <script src="/js/components/create-promotion-modal.js"></script>
                @endpush
            </div>
        </div>
    </div>
</div>
