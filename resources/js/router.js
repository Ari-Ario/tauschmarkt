
import { createRouter, createWebHistory } from 'vue-router';
import ChooseCategories from './components/SellerComponents/AddProduct.vue';

export const routes = [
    {
        path: "/login",
        name:"login",
        component: () => import("./pages/Auth/Login.vue"),
        meta: { showLogo: true, allBlogs: false, getStarted: false, home: true},
    },

    {
        path: "/register",
        name:"register",
        component: () => import("./pages/Auth/RegisterUser.vue"),
        meta: { showLogo: true, allBlogs:false, getStarted: false, home: true}
    },

    {
        path: "/registerSeller",
        name:"registerSeller",
        component: () => import("./pages/Auth/RegisterSeller.vue"),
        meta: { showLogo: true, allBlogs:false, getStarted: false, home: true}
    },

    {
        path: "/",
        name:"home",
        component: () => import("./pages/Home.vue"),
        meta: { showLogo: false, allBlogs: true, getStarted: true, home: false},
    },

    {
        path: "/map",
        name:"map",
        component: () => import("./pages/Map.vue"),
        meta: { showLogo: false, allBlogs: true, getStarted: true, home: false},
    },

    {
        path: "/map-user",
        name:"mapUser",
        component: () => import("./pages/MapUser.vue"),
        meta: { showLogo: false, allBlogs: true, getStarted: true, home: false},
    },

    {
        path: "/dashboard",
        name:"dashboard",
        component: () => import("./pages/Dashboard.vue"),
    
    },

    {
        path:"/profile-information",
        name: "profileInformation", 
        component: () => import("./components/ProfileInformation.vue"),
    },

    {
        path:"/add-product",
        name: "addProduct", 
        component: () => import("./components/SellerComponents/AddProduct.vue"),
        props: route => ({ selectedCategories: route.query.selectedCategories || [] }),
    },

    {
        path:"/orders-list",
        name:"OrdersList", 
        component: () => import("./components/SellerComponents/ListOfOrders.vue"),
    },

    {
        path: "/favorites",
        name: 'Favorites',
        component: () => import("./components/UserComponents/Favorites.vue"),
        //  meta: { requiresAuth: true },
    },

/*     {
        path:"/create",
        name:"createPost",
        component: () => import("./pages/CreateBlog.vue"),
        meta: { showLogo: true, allBlogs:false, getStarted: false, home: true}
    },
    {
        path:"/edit-blog/:id",
        name:"editBlog",
        component: () => import("./pages/EditBlog.vue"),
        meta: { showLogo: true, allBlogs:false, getStarted: false, home: true}
    } */
];


// const router = createRouter({
//     history: createWebHistory(process.env.VUE_APP_BASE_URL),
//     routes,
//   });
  
//   export default router;








