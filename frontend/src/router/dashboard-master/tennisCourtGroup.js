const routes = [
  /*****************************
 * TennisCourtGroup
 *****************************/
  {
    path: '/dashboard-master/tennis-court-group',
    name: 'dashboardMasterTennisCourtgroup',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard-master/tennis-court-group/Home.vue'),
    children: [
      {
        name: 'dashboardMasterTennisCourtGroupCreate',
        path: "create",
        component: () => import('@/views/dashboard-master/tennis-court-group/Create.vue'),
      },
      {
        name: 'dashboardMasterTennisCourtGroupList',
        path: "list",
        component: () => import('@/views/dashboard-master/tennis-court-group/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterTennisCourtGroupEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard-master/tennis-court-group/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterTennisCourtGroupShow',
        path: ":id",
        component: () => import('@/views/dashboard-master/tennis-court-group/Show.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterTennisCourtGroupHome',
        path: "",
        component: () => import('@/views/dashboard-master/tennis-court-group/Home.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;