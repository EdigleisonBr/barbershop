<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Cortes 💈</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="max-w-md mx-auto p-4">
        <div class="bg-white shadow-md rounded-lg p-4 mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center mb-2 sm:mb-0">
                <span class="text-gray-800 font-semibold text-sm mr-2">Filtrar por data:</span>
                <form method="GET" action="{{ route('appointments.index') }}" class="flex items-center space-x-2">
                    <input 
                        type="date" 
                        name="date" 
                        value="{{ $date }}" 
                        class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-700"
                    >
                    <button 
                        type="submit" 
                        class="bg-blue-600 text-white font-semibold px-3 py-1 rounded-md shadow-sm hover:bg-blue-700 transition"
                    >
                        Filtrar
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-600 to-blue-400 text-white shadow-lg rounded-lg p-4 mb-3 text-center">
            <span class="text-lg font-semibold tracking-wide">
                💈 Agenda {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
            </span>
        </div>

        <div class="flex justify-between items-center mt-2 mb-4 text-sm">
            <div class="flex-1 text-center bg-blue-100 text-blue-800 font-semibold py-2 mx-1 rounded-lg shadow-sm">
                ✂️ Agendados: {{ $agendados }}
            </div>
            <div class="flex-1 text-center bg-green-100 text-green-800 font-semibold py-2 mx-1 rounded-lg shadow-sm">
                ✂️ Finalizados: {{ $finalizados }}
            </div>
            <div class="flex-1 text-center bg-yellow-100 text-yellow-800 font-semibold py-2 mx-1 rounded-lg shadow-sm">
                💵 R$ {{ number_format($total, 2, ',', '.') }}
            </div>
        </div>

        @foreach ($times as $time)
            @php
                $appointment = $appointments->get($time);
                $status = $appointment->status ?? 'disponivel';
            @endphp

            @if(!$appointment || $appointment->status === 'disponivel')
                <div class="flex items-center justify-between p-2 mb-2 rounded-lg bg-gray-200">
                    <span class="w-16 font-semibold">{{ $time }}</span>
                    <form action="{{ route('appointments.store') }}" method="POST" class="flex gap-2 w-full">
                        @csrf
                        <input type="hidden" name="time" value="{{ $time }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="text" name="client_name" placeholder="Nome do cliente"
                            class="flex-1 p-1 border rounded text-sm" required>
                        <button type="submit"
                            class="bg-blue-500 text-white px-2 py-1 rounded text-sm">
                            Agendar
                        </button>
                    </form>
                </div>
            @elseif($appointment->status === 'agendado')
                <div class="flex flex-col w-full gap-2 relative p-2 mb-2 rounded-lg bg-blue-200">
                    <!-- badge de status -->
                    <span class="absolute top-1 right-2 bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full shadow">
                        {{ ucfirst($appointment->status) }}
                    </span>

                    <!-- Nome do cliente -->
                    <div class="flex items-center gap-4">
                        <span class="w-16 font-semibold text-gray-700">{{ $time }}</span>
                        <span class="font-semibold text-sm text-gray-800">Cliente:</span>
                        <span class="text-sm">{{ $appointment->client_name }}</span>
                    </div>

                    <!-- área de ações -->
                    <div class="flex items-center gap-2">
                        <form action="{{ route('appointments.finalize', $appointment) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <label class="text-sm text-gray-700">Valor</label>
                            <div class="flex items-center bg-white border border-gray-300 rounded px-2">
                                <span class="text-gray-500 text-sm">R$</span>
                                <input 
                                    type="number" 
                                    name="price"
                                    step="0.01"
                                    value="25.00"
                                    class="w-20 text-sm border-none focus:ring-0 text-center bg-transparent"
                                    inputmode="decimal"
                                />
                            </div>
                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-sm shadow hover:bg-green-600 transition">
                                Finalizar
                            </button>
                        </form>

                        <form action="{{ route('appointments.cancel', $appointment) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm shadow hover:bg-red-600 transition">
                                Cancelar
                            </button>
                        </form>
                    </div>
                </div>
            @elseif($appointment->status === 'finalizado')
                <div class="flex items-center justify-between p-3 mb-2 rounded-lg bg-green-100 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <span class="w-16 font-semibold text-gray-700">{{ $time }}</span>
                        <span class="font-semibold text-gray-800 text-sm">
                            {{ $appointment->client_name }}
                        </span>
                        <span class="text-base font-bold text-green-700">
                            R$ {{ number_format($appointment->price, 2, ',', '.') }}
                        </span>
                    </div>
                    <span class="bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm flex items-center gap-1">
                        ✅ Ok
                    </span>
                </div>
            @endif
        @endforeach
    </div>

</body>
</html>
