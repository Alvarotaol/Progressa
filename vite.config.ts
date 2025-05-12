/// <reference types="vitest/config" />
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
	plugins: [
		laravel({
			input: ['resources/js/app.ts'],
			refresh: true,
		}),
		tailwindcss(),
		vue({
			template: {
				transformAssetUrls: {
					base: null,
					includeAbsolute: false,
				},
			},
		}),
	],
	server: {
		hmr: {
			host: 'localhost',
		},
		host: true,
		port: 5173,
	},
	test: {
		globals: true,
		environment: 'happy-dom',
		setupFiles: './resources/js/tests/setup.ts',
	},
});
