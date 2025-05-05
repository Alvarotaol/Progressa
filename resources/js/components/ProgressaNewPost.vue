<template>
	<div class="bg-white rounded-2xl shadow p-4 mb-6">
		<h2 class="text-lg font-semibold mb-2">Novo post</h2>
		<form @submit.prevent="submit">
			<textarea v-model="content" rows="3"
				class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400"
				placeholder="O que você está fazendo?"></textarea>

			<!-- Tags -->
			<div class="mt-3">
				<label class="block text-sm font-medium mb-1">Tags</label>
				<div class="flex flex-wrap gap-2">
					<button v-for="tag in availableTags" :key="tag.label" type="button" @click="toggleTag(tag)"
						class="px-3 py-1 text-xs rounded-full border transition" :style="{
							backgroundColor: selectedTags.includes(tag) ? tag.color : '#fff',
							borderColor: '#00000055',
							color: selectedTags.includes(tag) ? '#000' : '#444'
						}">
						{{ tag.label }}
					</button>
				</div>
			</div>

			<div class="flex justify-end mt-4">
				<button type="submit"
					class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
					Postar
				</button>
			</div>
		</form>
	</div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const content = ref('')
const selectedTags = ref<{ label: string; color: string }[]>([])

const availableTags = [
	{ label: 'Bug', color: '#F8B4B4' },
	{ label: 'Feature', color: '#A7F3D0' },
	{ label: 'Refactor', color: '#BFDBFE' },
	{ label: 'Discussão', color: '#FEF3C7' },
	{ label: 'Urgente', color: '#FBCFE8' }
]

const emit = defineEmits<{
	(e: 'submit', value: { content: string; tags: { label: string; color: string }[] }): void
}>()

function toggleTag(tag: { label: string; color: string }) {
	const index = selectedTags.value.findIndex(t => t.label === tag.label)
	if (index >= 0) {
		selectedTags.value.splice(index, 1)
	} else {
		selectedTags.value.push(tag)
	}
}

function submit() {
	if (!content.value.trim()) return
	emit('submit', {
		content: content.value,
		tags: selectedTags.value
	})
	content.value = ''
	selectedTags.value = []
}
</script>
