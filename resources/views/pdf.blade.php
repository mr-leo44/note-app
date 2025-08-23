<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ISIPA Resultat Checker') }}</title>

    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        body {
            font-family: 'Figtree', Arial, sans-serif;
            color: #374151;
            margin: 0;
            padding: 0;
        }
        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            background: #f3f4f6;
        }
        .modal {
            margin: 0 auto;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        .header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #d1d5db;
        }
        .header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }
        .info {
            padding: 1rem 0;
            font-size: 1rem;
            font-weight: bold;
            text-transform: uppercase;
            color: #111827;
        }
        .info span {
            display: block;
            margin-bottom: 0.25rem;
        }
        .table-container {
            border-radius: 0.5rem;
            padding: 1.5rem 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }
        thead {
            background: #f3f4f6;
        }
        thead th {
            padding: 0.75rem 0.5rem;
            text-align: left;
            font-size: 0.85rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            border-bottom: 1px solid #d1d5db;
        }
        tbody tr {
            background: #fff;
        }
        tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.95rem;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        tbody td.text-center, thead th.text-center, tfoot td.text-center {
            text-align: center;
        }
        tbody td.font-bold, tfoot td.font-bold {
            font-weight: bold;
        }
        tfoot {
            background: #f3f4f6;
        }
        tfoot td {
            padding: 0.5rem 0.5rem;
            font-size: 0.85rem;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            border-top: 1px solid #d1d5db;
        }
        tfoot td.text-center {
            font-weight: bold;
            color: #374151;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="modal">
            @php
                $currentPeriod = \App\Models\Period::where('current', true)->first();
                $totalCredits = 0;
                $capitalizedCredits = 0;

                foreach ($coursesByPromotion as $course) {
                    $totalCredits += (int) $course->maxima;
                }

                $notes = $result->notes;
                foreach ($coursesByPromotion as $course) {
                    if (isset($notes[$course->name]) && $notes[$course->name] >= 10) {
                        $capitalizedCredits += (int) $course->maxima;
                    }
                }
            @endphp
            <div class="header">
                <h3>
                    Grille de déliberation - {{ $session->name }} - {{ $session->semester->name }} -
                    {{ $currentPeriod->name }}
                </h3>
            </div>
            <div class="info">
                <span>Etudiant : {{ $student->name }}</span>
                <span>Matricule : {{ $student->matricule }}</span>
                <span>Promotion : {{ $promotion->name }}</span>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Cours</th>
                            <th class="text-center">Crédit EC</th>
                            <th class="text-center">Cote /20</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coursesByPromotion as $course)
                            <tr>
                                <td>{{ $course->name }}</td>
                                <td class="text-center">{{ (int) $course->maxima }}</td>
                                <td class="text-center font-bold">
                                    @if (isset($result->notes[$course->name]))
                                        {{ $result->notes[$course->name] }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total credits (EC)</td>
                            <td class="text-center font-bold">{{ $totalCredits }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Moyenne Semestre</td>
                            <td class="text-center font-bold">{{ number_format($result->average, 5) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Credits capitalisés</td>
                            <td class="text-center font-bold">{{ $capitalizedCredits }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Décision semestre</td>
                            <td class="text-center font-bold">{{ $result->decision }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Pourcentage</td>
                            <td class="text-center font-bold">{{ $result->percentage }}%</td>
                        </tr>
                        <tr>
                            <td colspan="2">Mention</td>
                            <td class="text-center font-bold">{{ $result->mention }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>
</html>