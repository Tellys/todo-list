const routes = [
  /*****************************
 * Product
 *****************************/
  {
    path: '/dashboard/product',
    name: 'dashboardProduct',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard/product/Home.vue'),
    children: [
      {
        name: 'dashboardProductCreate',
        path: "create/:id?",
        params: true,
        component: () => import('@/views/dashboard/product/Create.vue'),
      },
      {
        name: 'dashboardProductList',
        path: "list",
        component: () => import('@/views/dashboard/product/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardProductEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/product/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardProductShow',
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