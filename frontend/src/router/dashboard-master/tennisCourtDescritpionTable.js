const routes = [
  /*****************************
 * TennisCourtGroup
 *****************************/
  {
    path: '/dashboard-master/tennis-court-description-table',
    name: 'dashboardMasterTennisCourtDescritpionTable',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard-master/tennis-court-description-table/Home.vue'),
    children: [
      {
        name: 'dashboardMasterTennisCourtDescritpionTableCreate',
        path: "create",
        component: () => import('@/views/dashboard-master/tennis-court-description-table/Create.vue'),
      },
      {
        name: 'dashboardMasterTennisCourtDescritpionTableList',
        path: "list",
        component: () => import('@/views/dashboard-master/tennis-court-description-table/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterTennisCourtDescritpionTableEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard-master/tennis-court-description-table/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterTennisCourtDescritpionTableShow',
        path: ":id",
        component: () => import('@/views/dashboard-master/tennis-court-description-table/Show.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterTennisCourtDescritpionTableHome',
        path: "",
        component: () => import('@/views/dashboard-master/tennis-court-description-table/Home.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;