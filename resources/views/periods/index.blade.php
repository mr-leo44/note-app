<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Liste des périodes</h1>
            <button id="openModalBtn" data-modal-target="createPeriodModal" data-modal-toggle="createPeriodModal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                + Nouvelle période
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
        @if ($periods->isEmpty())
            <div class="text-center text-gray-500 py-8">Aucune période enregistrée.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($periods as $period)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4">{{ ($periods->currentPage() - 1) * $periods->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $period->name }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button type="button" class="bg-gray-100 p-1.5 rounded" title="Voir" disabled>
                                        <x-icons.eye />
                                    </button>
                                    <button type="button" class="bg-blue-100 hover:bg-blue-200 p-1.5 rounded" title="Modifier" data-period-id="{{ $period->id }}" data-modal-target="editPeriodModal-{{ $period->id }}" data-modal-toggle="editPeriodModal-{{ $period->id }}">
                                        <x-icons.pencil-square />
                                    </button>
                                    <button type="button" class="bg-red-100 hover:bg-red-200 p-1.5 rounded" title="Supprimer" data-period-id="{{ $period->id }}" data-modal-target="deletePeriodModal-{{ $period->id }}" data-modal-toggle="deletePeriodModal-{{ $period->id }}">
                                        <x-icons.trash />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                <x-pagination :paginator="$periods" />
            </div>
        @endif
    </div>
    <x-periods.create-period-modal />
    @foreach ($periods as $period)
        <x-periods.edit-period-modal :period="$period" />
        <x-periods.delete-period-modal :period="$period" />
    @endforeach
</x-app-layout>
