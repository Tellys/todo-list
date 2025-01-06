const routes = [
  /*****************************
 * Product
 *****************************/
  {
    path: '/dashboard-master/product',
    name: 'dashboardMasterProduct',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard-master/product/Home.vue'),
    children: [
      {
        name: 'dashboardMasterProductCreate',
        path: "create",
        component: () => import('@/views/dashboard-master/product/Create.vue'),
      },
      {
        name: 'dashboardMasterProductList',
        path: "list",
        component: () => import('@/views/dashboard-master/product/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterProductEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard-master/product/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterProductShow',
        path: ":id",
        component: () => import('@/views/product/Show.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;