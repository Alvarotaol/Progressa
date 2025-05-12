<template>
	<div class="bg-white rounded-2xl shadow p-4 mb-6">
		<!-- ProgressaNewPost -->
		<h2 class="text-lg font-semibold mb-2" v-if="!is_edit">Novo post</h2>
		<form @submit.prevent="submit">
			<textarea
				v-model="content"
				rows="3"
				class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400"
				placeholder="O que você está fazendo?"
				maxlength="255"></textarea>

			<!-- Tags -->
			<div class="mt-3" v-if="tags.length > 0">
				<label class="block text-sm font-medium mb-1">Tags</label>
				<div class="flex flex-wrap gap-2">
					<button
						v-for="tag in toggleableTags"
						:key="tag.label"
						type="button"
						:data-test="'tag-' + tag.id"
						@click="tag.selected = !tag.selected"
						class="px-3 py-1 text-xs rounded-full border transition"
						:style="{
							backgroundColor: tag.selected ? tag.color : '#fff',
							borderColor: '#00000055',
							color: tag.selected ? '#000' : '#444',
						}">
						{{ tag.label }}
					</button>
				</div>
			</div>

			<div class="flex justify-end mt-4">
				<button
					type="button"
					@click="$emit('cancel')"
					v-if="is_edit"
					data-test="cancel-post"
					class="text-gray-500 hover:text-gray-700 px-4 py-2 mr-2 rounded-lg border border-gray-300">
					Cancelar
				</button>
				<button type="submit" data-test="submit-post" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
					{{ is_edit ? 'Salvar alterações' : 'Publicar' }}
				</button>
			</div>
		</form>
	</div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import { Post, Tag } from '@/types';

interface Props {
	post?: Post;
	tags: Tag[];
}
interface ToggleableTag extends Tag {
	selected: boolean;
}

const is_edit = computed(() => !!post);
const { post, tags } = defineProps<Props>();

const content = ref('');
const toggleableTags = ref<ToggleableTag[]>([]);

const emit = defineEmits<{
	(e: 'submit', value: { content: string; tags: Tag[] }): void;
	(e: 'cancel'): void;
}>();

function submit() {
	if (!content.value.trim()) return;
	const selectedTags = toggleableTags.value.filter((t) => t.selected);
	emit('submit', {
		content: content.value.trim(),
		tags: selectedTags,
	});
	content.value = '';
	toggleableTags.value.forEach((t) => (t.selected = false));
}

const init = function () {
	toggleableTags.value = tags.map((tag) => ({ ...tag, selected: false }));
	if (post) {
		content.value = post.content;
		post.tags.forEach((tag) => {
			const index = toggleableTags.value.findIndex((t) => t.label === tag.label);
			if (index >= 0) {
				toggleableTags.value[index].selected = true;
			}
		});
	}
};
watch(() => tags, init);
onMounted(init);
</script>
