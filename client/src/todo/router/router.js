import Vue from "vue";
import VueRouter from "vue-router";
import Login from "../views/Login.vue";
import Todo from "../views/Todo.vue";
import Register from "../views/Register.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/login",
        name: "Login",
        component: Login,
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
    },
    {
        path: "/todo",
        name: "Todo",
        component: Todo,
    },
];

const router = new VueRouter({
    mode: "history",
    //   base: process.env.BASE_URL,
    base: "/todo/",
    // base: "/",
    routes,
});

export default router;
