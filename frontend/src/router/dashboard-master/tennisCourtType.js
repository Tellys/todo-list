const routes = [
  /*****************************
 * TennisCourtGroup
 *****************************/
  {
    path: '/dashboard-master/tennis-court-type',
    name: 'dashboardMasterTennisCourtType',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard-master/tennis-court-type/Home.vue'),
    children: [
      {
        name: 'dashboardMasterTennisCourtTypeCreate',
        path: "create",
        component: () => import('@/views/dashboard-master/tennis-court-type/Create.vue'),
      },
      {
        name: 'dashboardMasterTennisCourtTypeList',
        path: "list",
        component: () => import('@/views/dashboard-master/tennis-court-type/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterTennisCourtTypeEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard-master/tennis-court-type/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterTennisCourtTypeShow',
        path: ":id",
        component: () => import('@/views/dashboard-master/tennis-court-type/Show.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterTennisCourtTypeHome',
        path: "",
        component: () => import('@/views/dashboard-master/tennis-court-type/Home.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;