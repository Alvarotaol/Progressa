<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create('projects', function (Blueprint $table) {
			$table->id();
			$table->string('name', 63);
			$table->string('description', 255)->nullable();
			$table->foreignId('user_id');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->boolean('is_active')->default(true);
			$table->boolean('is_public')->default(false);
			$table->string('public_slug', 255)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists('projects');
	}
};
