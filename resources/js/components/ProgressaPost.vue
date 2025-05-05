<script setup lang="ts">
import { Post } from '@/types';
import ProgressaTag from './ProgressaTag.vue';


interface Props {
	post: Post
}

const props = defineProps<Props>();

function formatDate(dateString: string) {
	const date = new Date(dateString);
	const day = date.getDate();
	const month = date.getMonth() + 1;
	const year = date.getFullYear();
	return `${day}/${month}/${year}`;
}

function wasEdited(post: Post) {
	return post.created_at !== post.updated_at;
}

</script>

<template>
	<div class="bg-white rounded-2xl shadow p-4 mb-4 relative">
		<!-- Botões de ação -->
		<div class="absolute top-2 right-2 flex gap-2 text-gray-500 text-sm">
			<button title="Editar" class="hover:text-blue-500">✏️</button>
			<button title="Ocultar" class="hover:text-yellow-500">🙈</button>
			<button title="Excluir" class="hover:text-red-500">🗑️</button>
		</div>
		<!-- Conteúdo do post -->
		<p class="text-gray-800 mb-2">{{ props.post.content }}</p>
		<!-- Tags -->
		<div class="flex flex-wrap gap-2 mb-2">
			<ProgressaTag :tag="tag" v-for="tag in props.post.tags" :key="tag.label" />
		</div>
		<!-- Timestamp -->
		<p class="text-sm text-gray-500">
			{{ formatDate(props.post.created_at) }}
			<span v-if="wasEdited(props.post)" class="ml-2 italic text-blue-500">editado</span>
		</p>
	</div>
</template>