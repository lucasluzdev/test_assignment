import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/Subscription/HomeView.vue";
import CreateView from "@/views/Subscription/CreateView.vue";
import UpdateView from "@/views/Subscription/UpdateView.vue"

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/create",
      name: "create",
      component: () => CreateView,
    },
    {
      path: "/update/:id",
      name: "update",
      component: () => UpdateView,
    },
  ],
});

export default router;
