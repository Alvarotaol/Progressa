<template>
	<HomeLayout>
		<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
			<h2 class="text-xl font-semibold text-gray-800">O que é o Progressa?</h2>
			<p class="mt-4 text-gray-600">
				O Progressa é uma plataforma para monitoramento de projetos, onde você pode registrar suas atividades, prestar contas para a sua equipe, e obter
				uma visão clara do progresso de suas tarefas e metas.
			</p>
			<div class="mt-6 flex gap-4">
				<router-link to="/login" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Entrar</router-link>
			</div>
		</div>
	</HomeLayout>
</template>

<script setup lang="ts">
import HomeLayout from '../layouts/HomeLayout.vue';
</script>

<style scoped></style>
