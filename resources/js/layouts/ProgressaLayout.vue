<template>
	<div class="flex flex-col h-screen">
		<!-- Navbar superior -->
		<header class="bg-white shadow px-6 py-3 flex justify-between items-center">
			<AppLogo />
			<!--<div class="text-xl font-bold text-indigo-600">Progressa</div>-->
			<nav class="hidden md:flex gap-6 text-gray-600">
				<a href="#" class="hover:text-indigo-500">Home</a>
				<a href="#" class="hover:text-indigo-500">Meus Projetos</a>
				<a href="#" class="hover:text-indigo-500">Configurações</a>
			</nav>
			<!-- Ícone do usuário com dropdown -->
			<div class="relative" @click="toggleDropdown">
				<img :src="$page.props.auth.user?.avatar" alt="Usuário"
					class="w-10 h-10 rounded-full cursor-pointer border border-gray-300" />

				<div v-if="open"
					class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg py-2 text-gray-700 z-50">
					<button @click="logout" class="w-full text-left px-4 py-2 hover:bg-gray-100 transition">
						Sair
					</button>
				</div>
			</div>
		</header>

		<!-- Conteúdo principal -->
		<div class="flex flex-1 overflow-hidden">
			<!-- Sidebar esquerda -->
			<aside class="bg-gray-100 w-64 p-4 overflow-y-auto hidden md:block border-r">
				<h2 class="text-md font-semibold mb-4" @click="refreshProjects">Projetos</h2>
				<ul class="space-y-2">
					<li v-for="project in projects" :key="project.id">
						<a :href="route('posts', project.id)" class="block px-3 py-2 rounded-lg hover:bg-indigo-100">
							{{ project.name }}
						</a>
					</li>
				</ul>
				<button class="mt-6 w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
					+ Novo Projeto
				</button>
			</aside>

			<!-- Conteúdo do feed + calendário -->
			<main class="flex-1 overflow-y-auto">
				<slot />
			</main>
		</div>
	</div>
</template>

<script setup lang="ts">

import AppLogo from '@/components/AppLogo.vue';
import { Model, request } from '@/lib/http';
import { onMounted, ref } from 'vue';
import { Project } from '@/types';

const open = ref(false);
const projects = ref<Project[]>([]);
const projectsModel = new Model('projects');

const toggleDropdown = () => {
	open.value = !open.value;
};

const logout = () => {
	request('logout').then(() => {
		window.location.href = '/login';
	})
};

const refreshProjects = () => {
	fetchProjects();
};

const fetchProjects = () => {
	projectsModel.list().then((response) => {
		projects.value = response.data;
	});
};

onMounted(fetchProjects);
</script>
