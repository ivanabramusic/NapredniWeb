<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, nastavnik, student
            $table->timestamps();
        });

        // Dodavanje početnih uloga u tablicu
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'nastavnik'],
            ['name' => 'student']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
