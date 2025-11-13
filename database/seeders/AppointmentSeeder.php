<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{
    public function run()
    {
        $start = strtotime('07:00');
        $end = strtotime('19:00');

        for ($time = $start; $time <= $end; $time += 30 * 60) {
            Appointment::create([
                'hour' => date('H:i', $time),
                'status' => 'disponivel',
            ]);
        }
    }
}
