// resources/js/tests/pages/ProjectPosts.test.ts
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import Posts from '@/pages/Posts.vue';
import { postsModel } from '../setup';
import { Post } from '@/types';

describe('ProjectPosts.vue', () => {
	beforeEach(() => {
		vi.clearAllMocks();
	});

	it('atualiza post após edição', async () => {
		const wrapper = mount(Posts, {
			props: { project_id: '123' },
			global: {
				stubs: ['router-link'],
			},
		});
		const vm = wrapper.vm as unknown as {
			posts: Post[];
			handleEditPost: (post: Post) => void;
		};

		const originalPost = {
			id: '1',
			project_id: '123',
			content: 'Antigo conteúdo',
			is_hidden: false,
			created_at: '2025-05-08T12:00:00Z',
			updated_at: '2025-05-08T12:00:00Z',
			tags: [],
		};

		// Simula estado inicial
		vm.posts = [originalPost];

		const updatedPost = {
			...originalPost,
			content: 'Novo conteúdo',
		};

		// Mock da resposta da API
		postsModel.update.mockResolvedValue({ data: updatedPost });

		// Dispara evento de edição
		await vm.handleEditPost({
			id: '1',
			project_id: '123',
			content: 'Novo conteúdo',
			is_hidden: false,
			created_at: '2025-05-08T12:00:00Z',
			updated_at: '2025-05-08T13:00:00Z',
			tags: [],
		});

		expect(postsModel.update).toHaveBeenCalledWith({ content: 'Novo conteúdo', tags: [] }, '1');

		expect(vm.posts[0].content).toBe('Novo conteúdo');
	});
});
