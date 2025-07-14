<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des jurys</h1>
            <button id="openModalBtn" data-modal-target="createJuryModal" data-modal-toggle="createJuryModal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                + Nouveau jury
            </button>
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
        @if ($juries->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucun jury enregistré.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Pseudo</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($juries as $jury)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">{{ ($juries->currentPage() - 1) * $juries->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $jury->name }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $jury->username }}</td>
                                <td class="px-6 py-4">{{ $jury->email }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button type="button" class="bg-gray-100 p-1.5 rounded" title="Voir" disabled>
                                        <x-icons.eye />
                                    </button>
                                    <button type="button" class="bg-blue-100 p-1.5 rounded" title="Modifier" disabled>
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 p-1.5 rounded" title="Supprimer" disabled>
                                        <x-icons.trash />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$juries" />
            </div>
        @endif
    </div>
        <x-juries.create-jury-modal />
</x-app-layout>
