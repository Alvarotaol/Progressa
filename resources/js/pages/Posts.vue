<template>
	<div class="flex max-w-6xl w-full mx-auto p-4 gap-6">
		<!-- Feed de posts -->
		<div class="w-full md:w-2/3">
			<h1 class="text-2xl font-bold mb-6">{{ project.name }}</h1>
			<ProgressaNewPost @submit="handleNewPost" />
			<ProgressaPost :post="post" v-for="post in posts" :key="post.id" />
		</div>
		<!-- Calendário -->
		<div class="hidden md:block w-1/3">
			<div class="bg-white rounded-2xl shadow p-4">
				<h2 class="text-lg font-semibold mb-4">Filtrar por data</h2>
				<div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
					Calendário aqui
					{{ $route.fullPath }}
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

const posts = ref<Post[]>([]);
const project_id = $route.params.project_id as string;
const project = ref<Project>({} as Project);

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
}

const fetchPosts = async () => {
	const { data } = await postsModel.list({ project_id });
	posts.value = data;
}

const fetchProject = async () => {
	if (!$route.params.project_id) return;
	const { data } = await postsModel.get(project_id);
	project.value = data;
}


onMounted(() => {
	fetchPosts();
	fetchProject();
});

</script>
