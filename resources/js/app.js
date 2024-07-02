import "./bootstrap";
import { routes } from "./router";
import { createRouter, createWebHistory } from "vue-router";
import { createPinia } from 'pinia'
import { createApp } from "vue";
import { useAuthStore } from "./stores/AuthStore";
import App from "./App.vue";
import axios from 'axios';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'

// Get CSRF token from the meta tag
// const tokenElement = document.querySelector('meta[name="csrf-token"]');

// if (tokenElement) {
//   const token = tokenElement.getAttribute('content');
//   axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
// } else {
//   console.error('CSRF token not found in meta tag');
// }

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(createPinia());
const store = useAuthStore();

// Set up Axios interceptor
axios.interceptors.response.use(
  response => response,
  async error => {
    if (error.response && error.response.status === 401) {
      // Token has expired or user is not authenticated
      await store.logout();
      router.push('/');
    }
    return Promise.reject(error);
  }
);

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// authenticate routes
router.beforeEach((to, from, next) => {
    const authUser = store.authUser;
    const reqAuth = to.matched.some((record) => record.meta.requiresAuth);
    
    if(reqAuth && !authUser) {
      store.getAuthUser().then(() => {
        if (!store.authUser) next('/login');
        else next();
      });
    } else {
      next(); // make sure to always call next()!
    }
});

app.use(router);
app.use(ElementPlus)
app.mount("#app");