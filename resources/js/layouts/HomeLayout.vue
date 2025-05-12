<template>
	<div class="flex flex-col min-h-screen bg-gray-50">
		<header class="bg-indigo-600 p-6 text-white text-center">
			<h1 class="text-3xl font-bold">Bem-vindo ao Progressa!</h1>
			<p class="mt-2">Seu microblog de projetos</p>
		</header>

		<main class="flex-grow flex items-center justify-center">
			<slot />
		</main>

		<footer class="bg-gray-800 text-white text-center p-4">
			<p>&copy; 2025 Progressa. Todos os direitos livres.</p>
		</footer>
	</div>
</template>

<script setup lang="ts"></script>
