import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

const projectRoutes = [
    {
        name: "Dashboard",
        path: "/dashboard",
        meta: {
            code: "DASHBOARD"
        }
    },
    {
        name: "ImportData",
        path: "/import-data",
        meta: {
            code: "IMPORT_DATA"
        }
    }
];

const router = new Router({
    mode: "history",
    base: process.env.BASE_URL,
    routes: projectRoutes
});

export default router;