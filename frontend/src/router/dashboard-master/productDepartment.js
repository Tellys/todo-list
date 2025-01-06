const routes = [
  /*****************************
 * ProductDepartment
 *****************************/
  {
    path: '/dashboard-master/product-department',
    name: 'dashboardMasterProductDepartment',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard-master/product-department/Home.vue'),
    children: [
      {
        name: 'dashboardMasterProductDepartmentCreate',
        path: "create",
        component: () => import('@/views/dashboard-master/product-department/Create.vue'),
      },
      {
        name: 'dashboardMasterProductDepartmentList',
        path: "list",
        component: () => import('@/views/dashboard-master/product-department/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterProductDepartmentEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard-master/product-department/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;