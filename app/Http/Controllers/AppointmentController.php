<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::now()->toDateString());

        // Gera horários de 07:00 às 19:00 (30 em 30 min)
        $start = Carbon::createFromTime(7, 0, 0);
        $end = Carbon::createFromTime(19, 0, 0);

        $times = [];
        while ($start <= $end) {
            $times[] = $start->format('H:i');
            $start->addMinutes(30);
        }

        // Busca agendamentos do dia e indexa por horário
        $appointments = Appointment::whereDate('date', $date)
            ->get()
            ->keyBy(function($item) {
                return substr($item->time, 0, 5); // pega só HH:MM
            });

        // Calcula estatísticas do dia
        $agendados = $appointments->where('status', 'agendado')->count();
        $finalizados = $appointments->where('status', 'finalizado')->count();
        $total = $appointments->where('status', 'finalizado')->sum('price');

        return view('appointments.index', compact(
            'times',
            'appointments',
            'date',
            'agendados',
            'finalizados',
            'total'
        ));
    }


    public function store(Request $request)
    {
        $appointment = Appointment::updateOrCreate(
            ['time' => $request->time, 'date' => $request->date],
            [
                'client_name' => $request->client_name,
                'status' => 'agendado',
                'price' => null
            ]
        );

        return redirect()->back();
    }

    public function finalize(Request $request, Appointment $appointment)
    {
        $price = str_replace(',', '.', $request->price); // converte 25,00 → 25.00

        $appointment->update([
            'status' => 'finalizado',
            'price' => $price ?: 25
        ]);

        return redirect()->back();
    }

    public function cancel(Appointment $appointment)
    {
        $appointment->update([
            'status' => 'disponivel',
            'client_name' => null,
            'price' => null
        ]);

        return redirect()->back();
    }

    public function updatePrice(Request $request, Appointment $appointment)
    {
        $appointment->update(['price' => $request->price]);
        return redirect()->back()->with('success', 'Valor atualizado com sucesso!');
    }
}