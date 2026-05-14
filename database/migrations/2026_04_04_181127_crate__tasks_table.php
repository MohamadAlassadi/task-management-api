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
        Schema::create('tasks', function (Blueprint $table) {

            $table->id('task_id');
            $table->string('title');
            $table->text('description')->nullable();    
            $table->enum('status', ['pending','in_progress','completed'])->default('pending');
            $table->unsignedBigInteger('project_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('assigned_to')->nullable()->constrained('users');
            $table->unsignedBigInteger('created_by')->constrained('users');
            $table->timestamp('Execution_Date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
