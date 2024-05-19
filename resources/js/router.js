


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
        path: "/dashboard",
        name:"dashboard",
        component: () => import("./pages/Dashboard.vue"),
        meta: { requiresAuth: true, 
        showLogo: true, allBlogs:true , getStarted: false, home: true},
    
    },


/*     {
        path:"/edit-profile",
        name:"editProfile", 
        component: () => import("./pages/EditProfile.vue"),
        meta: { showLogo: true, allBlogs:false, getStarted: false, home: true}
    },
    {
        path:"/alle-blogs",
        name: "allBlogs", 
        component: () => import("./pages/AllBlog.vue"),
        meta: { showLogo: true, allBlogs:false, getStarted: false, home: true}
    },
    {
        path: "/blogs/detail/:id",
        name: 'blogdetail',
        component: () => import("./pages/SingleBlog.vue"),
        //  meta: { requiresAuth: true },
    },
    {
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









