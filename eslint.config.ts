import vue from 'eslint-plugin-vue';
import vueParser from 'vue-eslint-parser';
import tsParser from '@typescript-eslint/parser';
import tseslint from 'typescript-eslint';
import json from '@eslint/json';

export default [
	//vue.configs.recommended, // substitui "plugin:vue/vue3-recommended"
	//prettier,
	//...tseslint.configs.recommended,
	{
		files: ['**/*.vue'],
		languageOptions: {
			parser: vueParser,
			parserOptions: {
				parser: tsParser,
				ecmaVersion: 'latest',
				sourceType: 'module',
			},
		},
		plugins: {
			vue,
		},
		rules: {
			'vue/multi-word-component-names': 'off',
			'vue/html-indent': ['error', 'tab'],
		},
	},
	{
		files: ['**/*.js', '**/*.ts'],
		languageOptions: {
			parser: tsParser,
			ecmaVersion: 'latest',
			sourceType: 'module',
			globals: {
				console: 'readonly',
				window: 'readonly',
				document: 'readonly',
			},
		},
		plugins: {
			//ts,
			'@typescript-eslint': tseslint.plugin,
		},
		rules: {
			//'prettier/prettier': ['warn', { endOfLine: 'auto', usetTabs: true }],
			'@typescript-eslint/no-unused-vars': ['warn', { argsIgnorePattern: '^_*' }],
			'@/indent': ['error', 'tab'],
		},
	},
	{
		files: ['**/*.json', '*.json'],
		language: 'json/json',
		plugins: {
			json,
		},
		rules: {
			//// Indentação com tab
			//'json/indent': ['error', 'tab'],
			//// Sempre usar aspas duplas em chaves e strings (padrão do JSON)
			//'quotes': ['error', 'double'],
			//// Obrigatório ter uma vírgula no final do objeto
			//'comma-dangle': ['error', 'always-multiline'],
			//// Obrigatório ter uma vírgula entre pares de chave:valor
			//'comma-style': ['error', 'last'],
			//// Espaço depois dos dois pontos
			//'key-spacing': ['error', { beforeColon: false, afterColon: true }],
		},
	},

	{
		ignores: ['node_modules/', 'vendor/*', 'dist/', 'build/', 'public/'],
	},
];
