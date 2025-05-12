import { describe, it, expect } from 'vitest';
import { mount, shallowMount } from '@vue/test-utils';
import ProjectForm from '@/pages/ProjectForm.vue';
const { projectsModel } = await import('@/lib/models');



describe('ProjectForm.vue', () => {
	it('envia dados do formulário ao criar projeto', async () => {
		const wrapper = mount(ProjectForm);

		await wrapper.get('input[name="name"]').setValue('Meu Projeto');
		await wrapper.get('textarea[name="description"]').setValue('Descrição top');
		await wrapper.get('[data-test="submit-project"]').trigger('click');

		// Aguarda ciclo async
		await new Promise(resolve => setTimeout(resolve, 0));

		// Verifica se o método create foi chamado com os dados certos
		expect(projectsModel.create).toHaveBeenCalledWith({
			name: 'Meu Projeto',
			description: 'Descrição top',
			is_public: false,
			is_active: true
		});
	});

	it('envia dados atualizados ao editar projeto', async () => {
		const wrapper = shallowMount(ProjectForm, {
			props: {
				project_id: 1,
			},
		});

		// Espera o projeto ser carregado
		await Promise.resolve();

		// Atualiza os campos
		await wrapper.find('input[name="name"]').setValue('Novo Nome');
		await wrapper.find('textarea[name="description"]').setValue('Nova descrição');

		// Dispara o submit
		await wrapper.get('[data-test="submit-project"]').trigger('click');
		await new Promise(resolve => setTimeout(resolve, 0));

		expect(projectsModel.update).toHaveBeenCalledWith(
			expect.objectContaining({
				name: 'Novo Nome',
				description: 'Nova descrição',
			}),
			1
		);
	});
});
