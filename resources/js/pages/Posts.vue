<template>
	<div class="flex max-w-6xl w-full mx-auto p-4 gap-6">
		<!-- Feed de posts -->
		<div class="w-full md:w-2/3">
			<div class="flex justify-between items-center mb-6">
				<h1 class="text-2xl font-bold">{{ project.name }}</h1>
				<div class="flex gap-2">
					<router-link
						:to="{ name: 'project.edit', params: { id: project.id } }"
						class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700"
					>
						Editar projeto
					</router-link>
					<button
						@click="copyLink"
						class="text-sm bg-gray-100 text-gray-700 px-3 py-1.5 rounded border hover:bg-gray-200"
					>
						Obter link público
					</button>
				</div>
			</div>

			<ProgressaNewPost @submit="handleNewPost" />
			<ProgressaPost :post="post" v-for="post in posts" :key="post.id" />
		</div>
		<!-- Calendário -->
		<div class="hidden md:block w-1/3">
			<div class="bg-white rounded-2xl shadow p-4">
				<h2 class="text-lg font-semibold mb-4">Filtrar por data</h2>
				<div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
					Calendário aqui
				</div>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import ProgressaPost from '@/components/ProgressaPost.vue';
import ProgressaNewPost from '@/components/ProgressaNewPost.vue';
import { Model } from '@/lib/http';
import { Post, Project } from '@/types';
import { useRoute } from 'vue-router';

const $route = useRoute();
const postsModel = new Model('posts');
const projectsModel = new Model('projects');

const posts = ref<Post[]>([]);
const project_id = $route.params.project_id as string;
const project = ref<Project>({ id: '', name: 'Projeto' } as Project);

//Methods
const handleNewPost = function ({ content, tags }: { content: string; tags: any[] }) {
	posts.value.unshift({
		id: Date.now(),
		content,
		tags,
		project_id: project_id,
		created_at: new Date().toISOString(),
		updated_at: new Date().toISOString()
	})
	postsModel.create({ content, project_id, tags });
}

const fetchPosts = () => {
	if (!$route.params.project_id) return;
	postsModel.list({ project_id }).then((response) => {
		posts.value = response.data;
	}); //TODO: catch
}

const fetchProject = () => {
	if (!$route.params.project_id) return;
	projectsModel.get(project_id).then((response) => {
		project.value = response.data;
	}); //TODO: catch
}

const copyLink = () => {
	if (!project.value.public_slug) return;
	const url = `${window.location.origin}/p/${project.value.public_slug}`;
	navigator.clipboard.writeText(url).then(() => {
		alert('Link copiado!');
	});
};

onMounted(() => {
	fetchPosts();
	fetchProject();
});

</script>
