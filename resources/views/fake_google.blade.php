<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="UTF-8">
	<title>Simulação d Login Google</title>
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
	<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md text-center">
		<!-- Fake Google Logo -->
		<div class="flex justify-center mb-6">
			<div class="flex items-center space-x-2 text-2xl font-bold">
				<span class="text-blue-500">G</span>
				<span class="text-red-500">o</span>
				<span class="text-yellow-500">o</span>
				<span class="text-blue-500">g</span>
				<span class="text-green-500">l</span>
				<span class="text-red-500">e</span>
			</div>
		</div>

		<h1 class="text-xl font-semibold text-gray-700 mb-4">Escolha um usuário para simular o login</h1>

		<ul class="space-y-3 text-left">
			@foreach ($users as $user)
			<li>
				<a
					href="{{ route('fakeCallback', ['user_id' => $user->id]) }}"
					class="block bg-indigo-100 hover:bg-indigo-200 text-indigo-800 font-medium px-4 py-3 rounded-lg transition"
				>
					{{ $user->name }} <span class="text-sm text-gray-500">({{ $user->email }})</span>
				</a>
			</li>
			@endforeach
		</ul>

		<hr class="my-6">

		<h2 class="text-lg font-semibold text-gray-700 mb-2">Criar novo usuário</h2>

		<form method="GET" action="{{ route('fakeCallback') }}" class="space-y-4 text-left">
			<input type="hidden" name="user_id" value="new">
			<div>
				<label class="block text-sm font-medium text-gray-700">Nome</label>
				<input type="text" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700">Email</label>
				<input type="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
			</div>
			<button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
				Criar e logar
			</button>
		</form>

		<p class="text-sm text-gray-400 mt-6">Modo desenvolvimento — simulação de login com Google</p>
	</div>

</body>
</html>