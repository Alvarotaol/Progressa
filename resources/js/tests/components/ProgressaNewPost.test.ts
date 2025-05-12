import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import ProgressaNewPost from '@/components/ProgressaNewPost.vue';

const tags = [
	{ id: 1, label: 'Bug', color: '#ff0000' },
	{ id: 2, label: 'Feature', color: '#00ff00' },
];

describe('ProgressaNewPost.vue', () => {
	it('emite "submit" com conteúdo e tags selecionadas', async () => {
		const wrapper = mount(ProgressaNewPost, {
			props: { tags },
		});

		// Preenche o conteúdo
		const textarea = wrapper.get('textarea');
		await textarea.setValue('Texto de teste');

		// Marca a primeira tag
		const tagButton = wrapper.get('[data-test="tag-1"]');
		await tagButton.trigger('click');

		// Clica no botão de submit
		const submitButton = wrapper.get('[data-test="submit-post"]');
		await submitButton.trigger('submit');

		// Verifica se o evento foi emitido corretamente
		expect(wrapper.emitted('submit')).toBeTruthy();
		const event: any = wrapper.emitted('submit')![0][0];

		expect(event.content).toBe('Texto de teste');
		expect(event.tags).toHaveLength(1);
		expect(event.tags[0].label).toBe('Bug');
	});
});
