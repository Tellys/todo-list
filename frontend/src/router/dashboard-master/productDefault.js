const routes = [
  /*****************************
 * ProductDefault
 *****************************/
  {
    path: '/dashboard-master/products-default',
    name: 'dashboardMasterProductDefault',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard-master/products-default/Home.vue'),
    children: [
      {
        name: 'dashboardMasterProductDefaultCreate',
        path: "create",
        component: () => import('@/views/dashboard-master/products-default/Create.vue'),
      },
      {
        name: 'dashboardMasterProductDefaultList',
        path: "list",
        component: () => import('@/views/dashboard-master/products-default/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterProductDefaultEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard-master/products-default/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterProductDefaultShow',
        path: ":id",
        component: () => import('@/views/dashboard-master/products-default/Show.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;