<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who created the offer
            $table->string('type'); 
            $table->string('value');      
            $table->integer('percentage')->nullable(); 
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->text('terms')->nullable();             
            $table->string('success_message');   
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
}
