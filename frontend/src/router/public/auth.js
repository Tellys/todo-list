const routes = [
  /*****************************
 * Auth
 *****************************/
  {
    path: '/login',
    name: 'login',
    params: true,
    component: () => import('@/views/public/Login'),
  },
  {
    path: '/logout',
    name: 'logout',
    params: true,
    component: () => import('@/views/public/Logout'),
  },
  {
    path: '/session-expires',
    name: 'sessionExpires',
    component: () => import('@/views/public/SessionExpires'),
  },
  {
    path: '/success',
    name: 'success',
    component: () => import('@/views/template/Success'),
  },
  //fim
];
export default routes;