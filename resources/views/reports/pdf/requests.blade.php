<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Solicitações - {{ now()->format('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 30px; }
        h2 { color: #3498db; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background-color: #f8f9fa; text-align: left; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        .status-pendente { color: #e67e22; }
        .status-aprovado { color: #2ecc71; }
        .status-rejeitado { color: #e74c3c; }
        .urgente { background-color: #ffebee; }
        .filters { margin-bottom: 20px; padding: 15px; background-color: #f5f5f5; border-radius: 5px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #7f8c8d; }
    </style>
</head>
<body>
    <h1>Relatório de Solicitações de Medicamentos</h1>

    <div class="filters">
        <strong>Filtros aplicados:</strong>
        <ul>
            <li>Status: {{ $status == 'all' ? 'Todos' : ucfirst($status) }}</li>
            <li>Urgência: {{ $urgency == 'all' ? 'Todas' : ucfirst($urgency) }}</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Medicamento</th>
                <th>Solicitante</th>
                <th>Quantidade</th>
                <th>Status</th>
                <th>Nível de Urgência</th>
                <th>Data</th>
                <th>Responsável</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $request)
                <tr class="{{ $request->urgency_level == 'alta' ? 'urgente' : '' }}">
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->medicine->name }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->quantity }}</td>
                    <td class="status-{{ $request->status }}">{{ ucfirst($request->status) }}</td>
                    <td>{{ ucfirst($request->urgency_level) }}</td>
                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $request->responder->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Relatório gerado em: {{ now()->format('d/m/Y H:i:s') }} |
        Total de solicitações: {{ $requests->total() }}
    </div>
</body>
</html>
