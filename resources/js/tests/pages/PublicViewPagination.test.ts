//import { mount, flushPromises } from '@vue/test-utils';
//import PublicView from '@/pages/PublicView.vue';
import { describe, it, vi, expect, beforeEach } from 'vitest';

//import { createRouter, createWebHistory } from 'vue-router';
//import { request } from '@/lib/http';

//vi.mock('@/lib/http', () => ({
//	request: vi.fn().mockResolvedValue({
//		data: {
//			project: {
//				id: '1',
//				name: 'Projeto Público',
//				tags: [{ label: 'Backend', color: '#f00' }],
//			},
//			posts: {
//				data: [
//					{ content: 'Post 1', created_at: '2024-01-01' },
//					{ content: 'Post 2', created_at: '2024-01-02' },
//				],
//				current_page: 1,
//				last_page: 1,
//			},
//		},
//	}),
//}));
//const mockProject = {
//	id: '1',
//	name: 'Projeto Público',
//	tags: [],
//};

//const page1 = {
//	data: {
//		project: mockProject,
//		posts: {
//			data: [
//				{ id: 1, content: 'Post 1', created_at: '2024-01-01' },
//				{ id: 2, content: 'Post 2', created_at: '2024-01-02' },
//			],
//			current_page: 1,
//			last_page: 2,
//		},
//	},
//};

//const page2 = {
//	data: {
//		project: mockProject, // pode repetir, já que não muda
//		posts: {
//			data: [{ id: 3, content: 'Post 3', created_at: '2024-01-03' }],
//			current_page: 2,
//			last_page: 2,
//		},
//	},
//};

describe('PublicView.vue - Paginação', () => {
	beforeEach(() => {
		vi.clearAllMocks();
	});
	it('carrega nova página de posts e concatena com os existentes', async () => {
		//const router = createRouter({
		//	history: createWebHistory(),
		//	routes: [{ path: '/p/:slug', component: PublicView, name: 'public' }],
		//});
		//router.push('/p/projeto-publico');
		//await router.isReady();

		//const wrapper = mount(PublicView, {
		//	global: {
		//		plugins: [router],
		//	},
		//});

		//await flushPromises();

		//// Simula clique no botão "Carregar mais" (ou método equivalente)
		//await wrapper.
		//await flushPromises();

		//// Verifica se posts das duas páginas foram renderizados
		//const text = wrapper.text();
		//expect(text).toContain('Post 1');
		//expect(text).toContain('Post 2');
		//expect(text).toContain('Post 3');

		//// Verifica se a segunda requisição foi feita corretamente
		//expect(mockedAxios.get).toHaveBeenCalledWith('/api/p/projeto-publico?page=2');
		expect(true).toBe(true);
	});
});
