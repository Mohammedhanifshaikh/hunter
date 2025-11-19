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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sheat_id');
            $table->bigInteger('agent_id');
            $table->bigInteger('company_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('lead_source');
            $table->string('status');
            $table->date('follow_up')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
