<div id="editJuryModal-{{ $jury->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full flex justify-center items-center">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="editJuryModal-{{ $jury->id }}">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Fermer</span>
            </button>
            <div class="p-6">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Modifier le jury</h3>
                <form method="POST" action="{{ route('juries.update', $jury) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name-{{ $jury->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                        <input type="text" name="name" id="name-{{ $jury->id }}" value="{{ old('name', $jury->name) }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('name') border-red-500 @enderror" required>
                        @error('name')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="username-{{ $jury->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pseudo</label>
                        <input type="text" name="username" id="username-{{ $jury->id }}" value="{{ old('username', $jury->username) }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('username') border-red-500 @enderror" required>
                        @error('username')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="email-{{ $jury->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="email" id="email-{{ $jury->id }}" value="{{ old('email', $jury->email) }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('email') border-red-500 @enderror" required>
                        @error('email')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Promotion(s) à assigner</label>
                        <div x-data="{ promotions: {{ json_encode(old('promotions', (optional($jury->promotions)->pluck('id') && optional($jury->promotions)->pluck('id')->count()) ? $jury->promotions->pluck('id')->toArray() : [null])) }} }">
                            <template x-for="(promotion, index) in promotions" :key="index">
                                <div class="flex items-center gap-2 mb-2">
                                    <select :name="'promotions['+index+']'" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <option value="">Sélectionner une promotion</option>
                                        @foreach(App\Models\Promotion::orderBy('name')->get() as $promotion)
                                            <option value="{{ $promotion->id }}" :selected="promotion == {{ $promotion->id }}">{{ $promotion->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" x-show="index === promotions.length - 1" @click="promotions.push(null)" class="inline-flex items-center p-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200" title="Ajouter une promotion">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                    <button type="button" x-show="index !== promotions.length - 1" @click="promotions.splice(index, 1)" class="inline-flex items-center p-1 text-sm font-medium text-red-600 bg-red-100 rounded-full hover:bg-red-200" title="Retirer cette promotion">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        @error('promotions')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
