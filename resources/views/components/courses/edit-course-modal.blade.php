<div id="editCourseModal-{{ $course->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full flex justify-center items-center">
    <div class="relative p-4 w-full max-w-lg h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="editCourseModal-{{ $course->id }}">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Fermer</span>
            </button>
            <div class="p-6">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Modifier le cours</h3>
                <form method="POST" action="{{ route('courses.update', $course) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name-{{ $course->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                        <input type="text" name="name" id="name-{{ $course->id }}" value="{{ old('name', $course->name) }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 @error('name') border-red-500 @enderror" required>
                        @error('name')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="course_category_id-{{ $course->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Section</label>
                        <select name="course_category_id" id="course_category_id-{{ $course->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 @error('course_category_id') border-red-500 @enderror" required>
                            @foreach(App\Models\CourseCategory::orderBy('name')->get() as $category)
                                <option value="{{ $category->id }}" @if(old('course_category_id', $course->course_category_id) == $category->id) selected @endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('course_category_id')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="w-full text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
