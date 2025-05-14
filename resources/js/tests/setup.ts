// tests/setup.ts
import { config } from '@vue/test-utils';
import { vi } from 'vitest';

// Aqui dá pra registrar componentes globais ou mocks
config.global.stubs = {};

export const projectsModel = {
	create: vi.fn().mockResolvedValue({ data: { id: 1 } }),
	update: vi.fn().mockResolvedValue({ data: { id: 1 } }),
	delete: vi.fn(),
	get: vi.fn().mockResolvedValue({
		data: {
			id: 1,
			name: 'Projeto Antigo',
			description: 'Descrição antiga',
			is_public: true,
			is_active: true,
			public_slug: 'projeto-antigo',
			tags: [],
		},
	}),
};

export const tagsModel = {
	create: vi.fn(),
	update: vi.fn(),
	delete: vi.fn(),
};

export const postsModel = {
	create: vi.fn(),
	update: vi.fn(),
	delete: vi.fn(),
};

vi.mock('@/lib/models', () => ({
	projectsModel,
	tagsModel,
	postsModel,
}));

vi.mock('@/lib/http', () => ({
	request: vi.fn(),
}));

vi.mock('vue-router', async () => {
	const actual = await vi.importActual<typeof import('vue-router')>('vue-router');
	return {
		...actual,
		useRoute: () => ({
			params: { project_id: '123', slug: 'projeto-publico' },
		}),
	};
});

//export const { projectsModel, tagsModel, postsModel } = await import('@/lib/models');
