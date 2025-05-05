import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/pages/Home.vue';
import Login from '@/pages/Login.vue';
import Posts from '@/pages/Posts.vue';
import ProgressaLayout from '@/layouts/ProgressaLayout.vue';

const routes = [
  {
    path: '/',
    redirect: '/login',
    component: ProgressaLayout,
    children: [
      //{ path: '/dashboard', name: 'dashboard', component: Posts, },
      { path: '/posts/:project_id', name: 'posts', component: Posts, },
    ]
  },
  //{ path: '/', name: 'home', component: Home, },
  //{ path: '/login', name: 'login', component: Login, },
  //{ path: '/dashboard', name: 'dashboard', component: Posts, },
  //{ path: '/posts/:project_id', name: 'posts', component: Posts, },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: Home, },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
