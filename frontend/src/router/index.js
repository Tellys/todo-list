import { createRouter, createWebHistory } from 'vue-router'

//folders
import publicRouters from './public';
import dashboard from './dashboard';
import dashboardMaster from './dashboardMaster';

//api
import Api from '@/services/Api'

const routes = [
  {
    path: '/',
    name: 'home',
    meta: { authOnly: true },
    component: () => import('@/views/public/Home.vue')
  },
  {
    path: '/about',
    name: 'about',
    meta: { authOnly: false },
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '@/views/public/About')
  },
  
  /*****************************
 * public
 *****************************/
  ...publicRouters,

  /*****************************
 * dashboard
 *****************************/
  ...dashboard,

  /*****************************
 * dashboardMaster
 *****************************/
  ...dashboardMaster,

  //fim
];

//const routes = baseRoutes.concat(dashboard);

const router = createRouter({
  //history: createWebHistory(process.env.BASE_URL),
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {

  //checa se url pede meta = authOnly
  if (to.meta.authOnly && !Api.isLoggedIn()) {
      return next({
        path: "/login",
        query: { redirect: to.fullPath }
      });
  }

  // encaminha a proxima call
  else return next();
});

/* router.beforeEach((to, from, next) => {

  //checa se url pede meta = authOnly
  if (to.matched.some(record => record.meta.authOnly)) {

    // check user logged
    if (!Api.isLoggedIn()) {

      return next({
        path: "/login",
        query: { redirect: to.fullPath }
      });
    }
    // encaminha a proxima call
    //return next();
  }

  // encaminha a proxima call
  else return next();
}); */
export default router