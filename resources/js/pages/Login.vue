<template>
	<HomeLayout>
		<div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md text-center">
			<h2 class="text-xl font-semibold text-gray-800 mb-6">Entre com sua conta Google</h2>

			<button @click="loginWithGoogle"
				class="w-full flex items-center justify-center gap-3 bg-white border border-gray-300 text-gray-700 font-medium py-2 rounded-xl hover:bg-gray-50 transition shadow">
				<img src="google-logo.svg" alt="Google" class="w-5 h-5" />
				<span>Entrar com Google</span>
			</button>
		</div>
	</HomeLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import HomeLayout from '@/layouts/HomeLayout.vue'
import storage from '@/libs/storage'

const email = ref('')
const password = ref('')

const loginWithGoogle = () => {
	const width = 500;
	const height = 600;
	const left = window.screen.width / 2 - width / 2;
	const top = window.screen.height / 2 - height / 2;

	const loginWindow = window.open(
		'/api/auth/google/redirect',
		'Login com Google',
		`width=${width},height=${height},top=${top},left=${left}`
	);

	window.addEventListener('message', (event) => {
		console.log('Evento:', event.origin);
		if (event.origin !== window.location.origin) return;

		const { token, user } = event.data;

		console.log('Token recebido:', token);
		console.log('User recebido:', user);

		if (token) {
			storage.saveToken(token);
		}
	});
}
</script>
