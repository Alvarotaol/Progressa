import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/pages/Home.vue';
import Login from '@/pages/Login.vue';
import Posts from '@/pages/Posts.vue';
import ProgressaLayout from '@/layouts/ProgressaLayout.vue';
import ProjectForm from '@/pages/ProjectForm.vue';
import PublicView from '@/pages/PublicView.vue';

const routes = [
	{
		path: '/',
		redirect: '/login',
		component: ProgressaLayout,
		children: [
			//{ path: '/dashboard', name: 'dashboard', component: Posts, },
			{ path: '/posts/:project_id', name: 'posts', component: Posts, props: true },
			{ path: '/projects/new', name: 'project.create', component: ProjectForm },
			{ path: '/projects/:project_id/edit', name: 'project.edit', component: ProjectForm, props: true },
			{ path: '/dashboard', name: 'dashboard', component: ProgressaLayout },
		],
	},
	{ path: '/login', name: 'login', component: Login },
	{ path: '/p/:slug', name: 'project.public', component: PublicView, props: true },
	//{ path: '/', name: 'home', component: Home, },
	{ path: '/:pathMatch(.*)*', name: 'not-found', component: Home },
];

const router = createRouter({
	history: createWebHistory(),
	routes,
	linkActiveClass: 'text-indigo-600 bg-indigo-100',
});

export default router;
