<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferFieldsTable extends Migration
{
    public function up(): void
    {
        Schema::create('offer_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->onDelete('cascade');
            $table->string('label');       // e.g., Email, Phone Number
            $table->string('name');        // Field name (used in form submission)
            $table->string('type');        // Input type (text, radio, etc.)
            $table->boolean('required')->default(false);
            $table->json('options')->nullable(); // For radio/checkbox options
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_fields');
    }
}
