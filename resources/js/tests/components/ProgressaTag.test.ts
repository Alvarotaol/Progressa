import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import ProgressaTag from '@/components/ProgressaTag.vue';

describe('ProgressaTag.vue', () => {
	it('renderiza corretamente com label e cor', () => {
		const tag = { label: 'Bug', color: '#ff0000' };
		const wrapper = mount(ProgressaTag, {
			props: { tag }
		});

		expect(wrapper.text()).toContain('Bug');
		expect(wrapper.attributes('style')).toContain('background-color: #ff0000');
	});
});