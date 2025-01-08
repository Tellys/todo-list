const routes = [
  /*****************************
 * TennisCourt
 *****************************/
  {
    path: '/dashboard/task',
    name: 'dashboardTask',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard/task/Home.vue'),
    children: [
      {
        name: 'dashboardTaskCreate',
        path: "create",
        component: () => import('@/views/dashboard/task/Create.vue'),
      },
      {
        name: 'dashboardTaskList',
        path: "list",
        component: () => import('@/views/dashboard/task/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardTaskEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/task/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardTaskShow',
        path: ":id",
        component: () => import('@/views/dashboard/task/Show.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;