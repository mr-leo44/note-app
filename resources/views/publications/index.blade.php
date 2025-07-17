<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des publications</h1>
            <a href=""
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                + Nouvelle publication
            </a>
        </div>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if ($errors->any())
            <x-alert type="error">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif
        @if ($publications->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucune publication enregistrée.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Promotion</th>
                            <th scope="col" class="px-6 py-3">Nbre Etudiants</th>
                            <th scope="col" class="px-6 py-3">Resultats publiés</th>
                            <th scope="col" class="px-6 py-3">Statut</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($publications as $publication)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">
                                    {{ ($publications->currentPage() - 1) * $publications->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 font-semibold">
                                    <a href="{{ route('publications.show', $publication->id) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ $publication->title ?? 'Sans titre' }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">{{ Str::limit($publication->description, 60) }}</td>
                                <td class="px-6 py-4">{{ $publication->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">{{ $publication->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="{{ route('publications.edit', $publication->id) }}"
                                        class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier">
                                        <x-icons.pencil-square />
                                    </a>
                                    <form action="{{ route('publications.destroy', $publication->id) }}" method="POST"
                                        onsubmit="return confirm('Supprimer cette publication ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-100 hover:bg-red-200 p-1.5 rounded"
                                            title="Supprimer">
                                            <x-icons.trash />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$publications" />
            </div>
        @endif
    </div>
    <div>
        {{ auth()->user()->account->accountable_type !== 'App\Models\Jury' ? 'Admin' : 'Jury' }}
    </div>
</x-app-layout>
