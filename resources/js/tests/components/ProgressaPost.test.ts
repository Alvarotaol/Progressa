// tests/components/ProgressaPost.test.ts
import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import ProgressaPost from '@/components/ProgressaPost.vue';
import { Post, Tag } from '@/types';

const post: Post = {
	id: 1,
	content: 'Post de teste',
	project_id: 1,
	is_hidden: false,
	created_at: '2025-05-08T12:00:00Z',
	updated_at: '2025-05-08T12:00:00Z',
	tags: [] as Tag[],
};

const postWithTags: Post = {
	...post,
	tags: [
		{ id: 1, label: 'Tag 1', color: '#000000' },
		{ id: 2, label: 'Tag 2', color: '#FF0000' },
	],
}

const tags: Tag[] = [
	{ id: 1, label: 'Tag 1', color: '#000000' },
	{ id: 2, label: 'Tag 2', color: '#FF0000' },
];

describe('ProgressaPost.vue', () => {
	it('renderiza o conteúdo do post', () => {
		const wrapper = mount(ProgressaPost, {
			props: { post, tags },
		});
		expect(wrapper.text()).toContain(post.content);
	});

	it('não renderiza post sem tags', () => {
		const wrapper = mount(ProgressaPost, {
			props: { post, tags },
		});
		post.tags.forEach(tag => {
			expect(wrapper.text()).toContain(tag.label);
		});
	});

	it('renderiza as tags do post com tags', () => {
		const wrapper = mount(ProgressaPost, {
			props: { post: postWithTags, tags},
		});
		postWithTags.tags.forEach(tag => {
			expect(wrapper.text()).toContain(tag.label);
		});
	});

	it('emite evento de exclusão ao clicar no botão delete', async () => {
		const wrapper = mount(ProgressaPost, {
			props: { post, tags },
		});
		await wrapper.get('[data-test="delete-post"]').trigger('click');
		expect(wrapper.emitted('delete')).toBeTruthy();
		expect(wrapper.emitted('delete')![0][0]).toEqual(post);
	});

	it('ativa modo edição ao clicar em editar mostrando tags', async () => {
		const wrapper = mount(ProgressaPost, {
			props: { post: postWithTags, tags },
		});
		await wrapper.get('[data-test="edit-post"]').trigger('click');
		expect(wrapper.html()).toContain('ProgressaNewPost'); // forma indireta
	});

	it('mostra marcação de "editado" quando updated_at for diferente', () => {
		const updatedPost = { ...post, updated_at: '2025-05-08T13:00:00Z' };
		const wrapper = mount(ProgressaPost, {
			props: { post: updatedPost, tags },
		});
		expect(wrapper.text()).toContain('editado');
	});

	it('emite "edit" com dados ao submeter edição', async () => {
		const wrapper = mount(ProgressaPost, {
			props: { post, tags: post.tags },
			global: {
				stubs: {
					ProgressaNewPost: {
						template: `<button @click="$emit('submit', { content: 'Post editado', tags: [{ id: 1, label: 'Bug', color: '#ff0000' }] })">Salvar</button>`,
					},
				},
			},
		});

		// Ativar modo edição
		await wrapper.get('[data-test="edit-post"]').trigger('click');

		// Simular envio
		await wrapper.find('button').trigger('click');

		expect(wrapper.emitted('edit')).toBeTruthy();
		expect(wrapper.emitted('edit')![0][0]).toEqual({
			id: 1,
			content: 'Post editado',
			tags: [{ id: 1, label: 'Bug', color: '#ff0000' }],
		});
	});
});
