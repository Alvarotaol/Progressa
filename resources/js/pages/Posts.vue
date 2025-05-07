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
			<div ref="scrollSentinel" class="h-12 flex items-center justify-center text-sm text-gray-400">
				<span v-if="loading">Carregando mais...</span>
				<span v-if="currentPage === lastPage">Não há mais posts</span>
			</div>
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
import { onMounted, ref, onUnmounted, watch } from 'vue';
import ProgressaPost from '@/components/ProgressaPost.vue';
import ProgressaNewPost from '@/components/ProgressaNewPost.vue';
import { Model } from '@/lib/http';
import { Post, Project } from '@/types';
import { useRoute } from 'vue-router';

const $route = useRoute();
const postsModel = new Model('posts');
const projectsModel = new Model('projects');

const posts = ref<Post[]>([]);
//const project_id = $route.params.project_id as string;
const { project_id } = defineProps<{ project_id: string }>();
const project = ref<Project>({ id: '', name: 'Projeto' } as Project);

const currentPage = ref(0);
const lastPage = ref(1);
const loading = ref(false);

const scrollSentinel = ref<HTMLElement | null>(null);
let observer: IntersectionObserver;

//Methods
const handleNewPost = function ({ content, tags }: { content: string; tags: any[] }) {

	postsModel.create({ content, project_id, tags }).then((response) => {
		posts.value.unshift(response.data);
	}).catch((error) => {
		console.error(error);
	});
}

const fetchPosts = (page = 1) => {
	if (!project_id || loading.value || (page > lastPage.value)) return;
	postsModel.list({ project_id, page }).then((response) => {
		posts.value.push(...response.data.data);
		currentPage.value = response.data.meta.current_page;
		lastPage.value = response.data.meta.last_page;
	}).catch((error) => {
		//
	}).finally(() => {
		loading.value = false;
	})
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
const initObserver = () => {
	observer = new IntersectionObserver(([entry]) => {
		if (entry.isIntersecting && currentPage.value < lastPage.value) {
			fetchPosts(currentPage.value + 1);
		}
	});

	if (scrollSentinel.value) {
		observer.observe(scrollSentinel.value);
	}
};

onMounted(() => {
	fetchProject();
	//fetchPosts();
	initObserver();
});

onUnmounted(() => {
	observer.disconnect();
});

</script>
