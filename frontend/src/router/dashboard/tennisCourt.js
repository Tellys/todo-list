const routes = [
  /*****************************
 * TennisCourt
 *****************************/
  {
    path: '/dashboard/tennis-court',
    name: 'dashboardTennisCourt',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard/tennis-court/Home.vue'),
    children: [
      {
        name: 'dashboardTennisCourtCreate',
        path: "create",
        component: () => import('@/views/dashboard/tennis-court/Create.vue'),
      },
      {
        name: 'dashboardTennisCourtList',
        path: "list",
        component: () => import('@/views/dashboard/tennis-court/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardTennisCourtEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/tennis-court/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardTennisCourtShow',
        path: ":id",
        component: () => import('@/views/dashboard/tennis-court/Show.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardTennisRegistrationPhaseList',
        path: "registration-phase",
        component: () => import('@/views/dashboard/tennis-court/RegistrationPhase.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;