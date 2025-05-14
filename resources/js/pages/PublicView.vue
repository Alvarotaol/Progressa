<template>
	<div class="flex max-w-6xl w-full mx-auto p-4 gap-6">
		<!-- Feed de posts -->
		<div class="w-full md:w-2/3 overflow-y-auto">
			<div class="flex justify-between items-center mb-6">
				<h1 class="text-2xl font-bold">{{ project.name }}</h1>
				<span class="text-sm text-gray-500">{{ project.description }}</span>
			</div>

			<ProgressaPost v-for="post in posts" :key="post.id" :post="post" :tags="project.tags || []" readonly />

			<div ref="scrollSentinel" class="h-12 flex items-center justify-center text-sm text-gray-400">
				<span v-if="loading">Carregando mais...</span>
				<span v-else-if="currentPage === lastPage">Não há mais posts</span>
			</div>
		</div>

		<!-- Calendário ou espaço vazio -->
		<!--<div class="hidden md:block w-1/3">
			<div class="bg-white rounded-2xl shadow p-4">
				<h2 class="text-lg font-semibold mb-4">Filtrar por data</h2>
				<div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
					Calendário aqui
				</div>
			</div>
		</div>-->
	</div>
</template>

<script setup lang="ts">
import ProgressaPost from '@/components/ProgressaPost.vue';
import { Post, Project } from '@/types';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { request } from '@/lib/http';

const $route = useRoute();
const slug = $route.params.slug as string;

const project = ref<Project>({ id: '', name: 'Projeto', tags: [], is_public: true, is_active: true } as Project);
const posts = ref<Post[]>([]);
const currentPage = ref(0);
const lastPage = ref(1);
const loading = ref(false);
const scrollSentinel = ref<HTMLElement | null>(null);

let observer: IntersectionObserver;

const fetchProject = () => {
	loading.value = true;
	console.log($route, $route.params);
	console.log(`public.project`, { page: currentPage.value + 1 }, { slug });
	request(`public.project`, { page: currentPage.value + 1 }, { slug })
		.then((response: any) => {
			const data = response.data;
			project.value = data.project;
			posts.value.push(...data.posts.data);
			currentPage.value = data.posts.current_page;
			lastPage.value = data.posts.last_page;
		})
		.catch(console.error)
		.finally(() => {
			loading.value = false;
		});
};

const initObserver = () => {
	observer = new IntersectionObserver(([entry]) => {
		if (entry.isIntersecting && currentPage.value < lastPage.value && !loading.value) {
			fetchProject();
		}
	});
	if (scrollSentinel.value) {
		observer.observe(scrollSentinel.value);
	}
};

onMounted(() => {
	fetchProject();
	initObserver();
});
</script>
