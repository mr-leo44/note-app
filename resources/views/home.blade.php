<x-guest-layout>
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if (session('info'))
        <x-alert type="info">{{ session('info') }}</x-alert>
    @endif
    @if (session('warning'))
        <x-alert type="warning">{{ session('warning') }}</x-alert>
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
    @php
        $currentPeriod = App\Models\Period::where('current', true)->first();
        $periodSemesters = $currentPeriod ? $currentPeriod->semesters : [];
    @endphp

    <div class="px-3">
        <h2 class="text-xl text-center dark:text-white font-semibold mb-4">Consultation des résultats</h2>

        <form id="searchForm" method="POST" action="{{ route('results.search') }}" class="space-y-4">
            @csrf
            <div>
                <div>
                    <x-input-label for="matricule" value="Matricule" />
                    <x-text-input id="matricule" name="matricule" type="text" class="mt-1 block w-full" required
                        placeholder="Entrez votre matricule" />
                </div>

                <div class="my-4">
                    <x-input-label for="semester" value="Semestre" />
                    <select name="semester" id="semester"
                        class="bg-gray-50 dark:bg-gray-900 dark:text-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 pr-10 appearance-none"
                        required>
                        <option value="">Sélectionner un semestre</option>
                        @foreach ($periodSemesters as $semester)
                            <option value="{{ $semester->id }}" :selected="semester == {{ $semester->name }}">
                                {{ $semester->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="my-8 flex items-center justify-between">
                    <a href="{{ route('login') }}" class="dark:text-gray-300 underline">Espace Jury</a>
                    <x-primary-button type="submit">
                        Consulter les résultats
                    </x-primary-button>
                </div>
            </div>
        </form>
        
    </div>
</x-guest-layout>
