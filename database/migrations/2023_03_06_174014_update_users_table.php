<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Modify the users table to allow Oauth logins
        Schema ::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('avatar')->nullable();
            $table->string('external_id')->nullable();
            //save the name of the provider that was used to login
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        

    }
};
?>
