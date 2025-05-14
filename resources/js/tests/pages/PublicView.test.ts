import { mount, flushPromises } from '@vue/test-utils';
import PublicView from '@/pages/PublicView.vue';
import { describe, it, vi, expect, beforeEach } from 'vitest';
import { createRouter, createWebHistory } from 'vue-router';
import { request } from '@/lib/http';

vi.mock('@/lib/http', () => ({
	request: vi.fn().mockResolvedValue({
		data: {
			project: {
				id: '1',
				name: 'Projeto Público',
				tags: [{ label: 'Backend', color: '#f00' }],
			},
			posts: {
				data: [
					{ content: 'Post 1', created_at: '2024-01-01' },
					{ content: 'Post 2', created_at: '2024-01-02' },
				],
				current_page: 1,
				last_page: 1,
			},
		},
	}),
}));

describe('PublicView.vue', () => {
	beforeEach(() => {
		vi.clearAllMocks();
	});
	it('carrega e exibe os posts públicos do projeto', async () => {
		const router = createRouter({
			history: createWebHistory(),
			routes: [{ path: '/p/:slug', component: PublicView, name: 'project.public', props: true }],
		});
		router.push({ name: 'project.public', params: { slug: 'projeto-publico' } });
		await router.isReady();

		const wrapper = mount(PublicView, {
			global: {
				plugins: [router],
			},
			props: {
				slug: 'projeto-publico',
			},
		});

		await flushPromises();

		expect(request).toHaveBeenCalledWith(
			'public.project',
			{ page: 1 },
			{
				slug: 'projeto-publico',
			},
		);
		expect(wrapper.text()).toContain('Projeto Público');
		expect(wrapper.text()).toContain('Post 1');
		expect(wrapper.text()).toContain('Post 2');
	});
});
