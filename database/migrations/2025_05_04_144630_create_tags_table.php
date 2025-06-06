<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create('tags', function (Blueprint $table) {
			$table->id();
			$table->string('label', 31);
			$table->string('color', 7);
			$table->foreignId('project_id');
			$table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			$table->unique(['label', 'project_id']);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists('tags');
	}
};
