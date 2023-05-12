<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuaris', function (Blueprint $table) {

            $table->string('nom');
            $table->string('cognoms')->nullable();
            $table->string('email')->primary();
            $table->string('etapa')->nullable();
            $table->string('curs')->nullable();
            $table->string('grup')->nullable();
            $table->boolean('admin')->nullable();
            $table->boolean('superadmin')->nullable();
            $table->timestamps();

        });

        // Inert superadmin
        DB::table('usuaris')->insert(
            array(
                'nom' => 'Administrador',
                'email' => 'cicles@sapalomera.cat',
                'superadmin' => 1));

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuaris');
    }
};

?>