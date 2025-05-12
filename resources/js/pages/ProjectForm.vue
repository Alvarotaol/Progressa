<template>
	<div class="max-w-3xl w-full mx-auto p-6 bg-white rounded-xl shadow space-y-6">
		<h1 class="text-2xl font-bold">
			{{ isEdit ? 'Editar Projeto' : 'Criar Novo Projeto' }}
		</h1>

		<div class="space-y-4">
			<!-- Nome -->
			<div>
				<label class="block text-gray-700 font-medium">Nome</label>
				<input v-model="form.name" name="name" type="text" class="w-full mt-1 p-2 border rounded" maxlength="63" />
			</div>

			<!-- Descrição -->
			<div>
				<label class="block text-gray-700 font-medium">Descrição</label>
				<textarea v-model="form.description" name="description" class="w-full mt-1 p-2 border rounded" maxlength="255" />
			</div>

			<!-- Visibilidade -->
			<div class="flex items-center gap-4">
				<label class="flex items-center gap-2">
					<input type="checkbox" v-model="form.is_active" />
					Ativo
				</label>

				<label class="flex items-center gap-2">
					<input type="checkbox" v-model="form.is_public" />
					Público
				</label>
			</div>

			<!-- Slug público (somente no modo edição e se for público) -->
			<div v-if="isEdit && form.is_public && form.public_slug" class="bg-gray-100 p-3 rounded">
				<span class="text-gray-700">Link público:</span>
				<code class="text-indigo-600 break-all">{{ publicUrl }}</code>
			</div>

			<!-- Tags (somente no modo edição) -->
			<div v-if="isEdit">
				<label class="block text-gray-700 font-medium mb-1">Tags</label>
				<div class="flex flex-wrap gap-2 mb-2">
					<span v-for="tag in tags" :key="tag.id" class="flex items-center bg-gray-200 px-3 py-1 rounded-full text-sm">
						<button class="ml-2 hover:cursor-pointer" :style="{ color: tag.color }" @click="editTag(tag)">●</button>
						<span class="ml-2">{{ tag.label }}</span>
						<button class="ml-2 text-gray-500 hover:text-red-500" @click="removeTag(tag.id)">✕</button>
					</span>
				</div>
				<div class="flex gap-2">
					<input v-model="newTagLabel" placeholder="Nova tag" class="p-2 border rounded flex-1" />
					<input v-model="newTagColor" type="color" class="w-12 h-10 border rounded" />
					<button @click="saveTag" class="bg-indigo-600 text-white px-4 py-2 rounded">{{ editingTag ? 'Salvar' : 'Adicionar' }}</button>
				</div>
			</div>
		</div>

		<!-- Botão -->
		<button @click="handleSubmit" data-test="submit-project" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded">
			{{ isEdit ? 'Salvar alterações' : 'Criar Projeto' }}
		</button>
	</div>
</template>

<script setup lang="ts">
import { projectsModel, tagsModel } from '@/lib/models';
import { ModelId, Project, Tag } from '@/types';
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

interface Props {
	project_id?: ModelId;
}

const { project_id } = defineProps<Props>();

const isEdit = computed(() => !!project_id);

const form = ref<Partial<Project>>({
	name: '',
	description: '',
	is_public: false,
	is_active: true,
});

const tags = ref<Tag[]>([]);
const newTagLabel = ref('');
const newTagColor = ref('#000000');
const editingTag = ref<Tag | null>(null);

// Carrega projeto e tags no modo edição
onMounted(async () => {
	if (project_id) {
		projectsModel.get(project_id).then((res) => {
			form.value = res.data;
			tags.value = res.data.tags;
		});
	}
});

const handleSubmit = async () => {
	if (isEdit.value) {
		projectsModel.update(form.value, project_id).then((res) => {
			console.log(res.data);
			//alert('Projeto atualizado!');
		});
	} else {
		projectsModel
			.create(form.value)
			.then((res) => {
				router.push({ name: 'project.edit', params: { project_id: res.data.id } });
			})
			.catch((error) => {
				console.error(error);
			});
	}
};

const addTag = async () => {
	if (!newTagLabel.value.trim()) return;

	tagsModel
		.create({
			label: newTagLabel.value,
			color: newTagColor.value,
			project_id,
		})
		.then((res) => {
			// Atualizar a tag no componente
			tags.value.push(res.data);
			newTagLabel.value = '';
			newTagColor.value = '#000000';
		})
		.catch((error) => {
			console.error(error);
		});
};

const removeTag = async (id?: ModelId) => {
	if (!!id) await tagsModel.delete(id);
	tags.value = tags.value.filter((tag) => tag.id !== id);
};

const editTag = (tag: Tag) => {
	editingTag.value = tag;
	newTagLabel.value = tag.label;
	newTagColor.value = tag.color;
};

const saveTagEdit = () => {
	if (!editingTag.value) return;
	editingTag.value.label = newTagLabel.value;
	editingTag.value.color = newTagColor.value;
	tagsModel
		.update(editingTag.value, editingTag.value?.id)
		.then((response) => {
			// Atualizar a tag no componente
			tags.value = tags.value.map((tag) => {
				if (tag.id === editingTag.value?.id) {
					return response.data;
				}
				return tag;
			});
			editingTag.value = null;
		})
		.catch((error) => {
			console.error(error);
		});
};

const saveTag = () => {
	editingTag.value ? saveTagEdit() : addTag();
};

const publicUrl = computed(() => `/public/${form.value.public_slug}`);
</script>
