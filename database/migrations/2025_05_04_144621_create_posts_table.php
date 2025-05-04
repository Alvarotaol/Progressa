<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create('posts', function (Blueprint $table) {
			$table->id();
			$table->string('content', 255);
			$table->boolean('is_hidden')->default(false);
			$table->foreignId('project_id');
			$table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists('posts');
	}
};
