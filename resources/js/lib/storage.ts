const base = 'progressa';

function setKey(key: string, value: string) {
	localStorage.setItem(`${base}.${key}`, value);
}

function getKey(key: string) {
	return localStorage.getItem(`${base}.${key}`) || '';
}

function clearStorage() {
	return localStorage.clear();
}

export default {
	isLoggedIn: function () {
		const token = getKey('access_token');
		return token != null;
	},
	getToken: function () {
		const token = getKey('access_token');
		return token;
	},
	saveToken: function (token: string) {
		setKey('access_token', `Bearer ${token}`);
	},
	saveUserData: function (nome: string, email: string, avatar: string) {
		setKey('nome', nome);
		setKey('email', email);
		setKey('avatar', avatar);
	},
	getUserName: () => getKey('nome'),
	getUserEmail: () => getKey('email'),
	getUserAvatar: () => getKey('avatar'),
	//TODO o que é isso?
	clear: function () {
		clearStorage();
	},
};
