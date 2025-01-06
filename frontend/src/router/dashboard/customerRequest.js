const routes = [
  /*****************************
 * customerRequest
 *****************************/
  {
    path: '/dashboard/customer-request',
    name: 'dashboardCustomerRequest',
    redirect: { name: 'dashboardCustomerRequestHome'},
    //meta: { authOnly: true },
    params: false,
    //component: () => import('@/views/dashboard/customer-request/Home.vue'),
    children: [
      {
        name: 'dashboardCustomerRequestCreate',
        path: "create/:id?",
        params: true,
        component: () => import('@/views/dashboard/customer-request/Create.vue'),
      },
      {
        name: 'dashboardCustomerRequestList',
        path: "list",
        component: () => import('@/views/dashboard/customer-request/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardCustomerRequestEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/customer-request/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardCustomerRequestShow',
        path: ":id",
        component: () => import('@/views/dashboard/customer-request/Show.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardCustomerRequestHome',
        path: "",
        params: false,
        component: () => import('@/views/dashboard/customer-request/Home.vue'),
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;