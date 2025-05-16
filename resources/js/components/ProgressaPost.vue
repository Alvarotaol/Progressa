<template>
	<div
		class="bg-white rounded-2xl shadow p-4 mb-4 relative transition-opacity"
		:class="{ 'opacity-50': post.is_hidden }"
		v-if="!isEditing">
		<!-- Contexto de código se houver -->
		<div v-if="code !== ''" class="text-blue-500 text-sm bg-blue-200 px-2 rounded-lg my-2 absolute top-1 left-5">
			{{ code }}
		</div>
		<!-- Botões de ação -->
		<div class="absolute top-2 right-2 flex gap-2 text-gray-500 text-sm" v-if="!readonly">
			<button title="Editar" class="hover:text-blue-500" @click="isEditing = true" data-test="edit-post">✏️</button>
			<button title="Ocultar" class="hover:text-yellow-500" @click="$emit('toggleHidden', post)">
				{{ post.is_hidden ? '👀' : '🙈' }}
			</button>
			<button title="Excluir" class="hover:text-red-500" @click="$emit('delete', post)" data-test="delete-post">🗑️</button>
		</div>
		<!-- Conteúdo do post -->
		<div class="text-gray-800 my-2" v-html="formatMarkdown(post.content)"></div>
		<!-- Tags -->
		<div class="flex flex-wrap gap-2 mb-2">
			<ProgressaTag :tag="tag" v-for="tag in post.tags" :key="tag.label" />
		</div>
		<!-- Timestamp -->
		<p class="text-sm text-gray-500">
			{{ formatDate(post.created_at) }}
			<span v-if="wasEdited(post)" class="ml-2 italic text-blue-500">editado</span>
		</p>
	</div>
	<ProgressaNewPost :tags="tags" :post="post" @cancel="isEditing = false" @submit="submit" v-else />
</template>

<script setup lang="ts">
import { ModelId, Post, Tag } from '@/types';
import { computed, ref } from 'vue';
import ProgressaNewPost from './ProgressaNewPost.vue';
import ProgressaTag from './ProgressaTag.vue';
import { marked } from '@/lib/marked';

interface Props {
	post: Post;
	tags: Tag[];
	readonly?: boolean;
}

const { post, tags, readonly = false } = defineProps<Props>();
const isEditing = ref(false);

const code = computed(() => {
	const match = post.content.match(/```(.*)/);
	console.log(match);
	return match ? match[1] : '';
});

const $emit = defineEmits<{
	(e: 'edit', value: { id: ModelId; content: string; tags: Tag[] }): void;
	(e: 'delete', value: Post): void;
	(e: 'toggleHidden', value: Post): void;
}>();

function formatDate(dateString: string) {
	const date = new Date(dateString);
	const day = date.getDate();
	const month = date.getMonth() + 1;
	const year = date.getFullYear();
	return `${day}/${month}/${year}`;
}

function submit(value: { content: string; tags: Tag[] }) {
	console.log(post);
	isEditing.value = false;
	$emit('edit', { id: post.id, ...value });
}

function formatMarkdown(text: string) {
	return marked.parse(text);
}

function wasEdited(post: Post) {
	return post.created_at !== post.updated_at;
}
</script>
