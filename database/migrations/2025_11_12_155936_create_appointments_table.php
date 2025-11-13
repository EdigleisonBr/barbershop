<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->string('client_name')->nullable();
            $table->enum('status', ['disponivel', 'agendado', 'finalizado'])->default('disponivel');
            $table->decimal('price', 8, 2)->nullable();
            $table->date('date')->default(date('Y-m-d'));
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
