<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Consumo - {{ now()->format('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #2c3e50; text-align: center; }
        h2 { color: #3498db; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8f9fa; text-align: left; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        .period-info { margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>
    <h1>Relatório de Consumo de Medicamentos</h1>

    <div class="period-info">
        <strong>Período:</strong> {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}<br>
        <strong>Tipo de período:</strong> {{ ucfirst($period) }}
    </div>

    <h2>Top 10 Medicamentos Mais Consumidos</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Medicamento</th>
                <th>Quantidade Consumida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topConsumed as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->medicine->name }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Consumo por Categoria</h2>
    <table>
        <thead>
            <tr>
                <th>Categoria</th>
                <th>Quantidade Consumida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->category }}</td>
                    <td>{{ $category->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Consumo Mensal (Últimos 12 Meses)</h2>
    <table>
        <thead>
            <tr>
                <th>Mês</th>
                <th>Quantidade Consumida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyConsumption as $monthly)
                <tr>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $monthly->month)->format('m/Y') }}</td>
                    <td>{{ $monthly->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: center; margin-top: 30px; font-size: 12px; color: #7f8c8d;">
        Relatório gerado em: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
