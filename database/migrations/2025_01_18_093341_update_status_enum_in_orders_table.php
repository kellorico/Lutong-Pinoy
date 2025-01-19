<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Update the ENUM values for the `status` column
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled', 'in_progress') DEFAULT 'pending'");
    }

    public function down()
    {
        // Revert to the original ENUM values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
