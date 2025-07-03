<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Estoque</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Relatório de Estoque - {{ now() }}</h1>

    <h2>Estoque Crítico</h2>
    <table>
        <thead>
            <tr>
                <th>Medicamento</th>
                <th>Estoque Atual</th>
                <th>Estoque Mínimo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($criticalStock as $medicine)
                <tr>
                    <td>{{ $medicine->name }}</td>
                    <td>{{ $medicine->stock }}</td>
                    <td>{{ $medicine->minimum_stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Medicamentos Próximos do Vencimento</h2>
    <table>
        <thead>
            <tr>
                <th>Medicamento</th>
                <th>Data de Validade</th>
                <th>Dias Restantes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expiringSoon as $medicine)
                <tr>
                    <td>{{ $medicine->name }}</td>
                    <td>{{ $medicine->expiration_date }}</td>
                    <td>{{ now()->diffInDays($medicine->expiration_date) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Medicamentos com Baixa Rotação</h2>
    <table>
        <thead>
            <tr>
                <th>Medicamento</th>
                <th>Saídas (Último Ano)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowRotation as $medicine)
                <tr>
                    <td>{{ $medicine->name }}</td>
                    <td>{{ $medicine->movements_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
