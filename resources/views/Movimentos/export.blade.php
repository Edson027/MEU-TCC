<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Movimentações</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Relatório de Movimentações</h2>
    <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Medicamento</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Motivo</th>
                <th>Usuário</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $movement)
            <tr>
                <td>{{ $movement->movement_date->format('d/m/Y') }}</td>
                <td>{{ $movement->medicine->name }}</td>
                <td>{{ $movement->type == 'entrada' ? 'Entrada' : 'Saída' }}</td>
                <td>{{ $movement->quantity }}</td>
                <td>{{ $movement->reason ?? 'Não informado' }}</td>
                <td>{{ $movement->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>