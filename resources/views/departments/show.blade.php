<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-900 p-2 rounded" title="Retour">
                    <x-icons.arrow-left />
                </a>
                <h1 class="text-2xl font-bold">{{ $department->name }}</h1>
            </div>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2" data-modal-target="createPromotionModal" data-modal-toggle="createPromotionModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Ajouter
            </button>
            <x-promotions.create-promotion-modal :departments="App\Models\Department::orderBy('name')->get()" :departmentId="$department->id" />
        </div>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        @if ($department->promotions->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucune promotion liée à ce département.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Abbreviations</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($department->promotions as $promotion)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $promotion->name }}</td>
                                <td class="px-6 py-4">{{ $promotion->short_name }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="{{ route('promotions.show', $promotion) }}" class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded" title="Voir">
                                        <x-icons.eye />
                                    </a>
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier" data-promotion-id="{{ $promotion->id }}" data-modal-target="editPromotionModal-{{ $promotion->id }}" data-modal-toggle="editPromotionModal-{{ $promotion->id }}">
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Supprimer" data-promotion-id="{{ $promotion->id }}" data-action-url="{{ route('promotions.destroy', $promotion) }}" data-modal-target="deletePromotionModal" data-modal-toggle="deletePromotionModal">
                                        <x-icons.trash />
                                    </button>
                                    <x-promotions.edit-promotion-modal :promotion="$promotion" :departments="App\Models\Department::orderBy('name')->get()" />
                                    <x-promotions.delete-promotion-modal />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
