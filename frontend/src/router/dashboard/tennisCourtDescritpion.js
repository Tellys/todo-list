const routes = [
  /*****************************
 * TennisCourtDescritpion
 *****************************/
  {
    path: '/dashboard/tennis-court-description',
    name: 'dashboardTennisCourtDescritpion',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard/tennis-court-description/Home.vue'),
    children: [
      {
        name: 'dashboardTennisCourtDescritpionCreate',
        path: "create",
        component: () => import('@/views/dashboard/tennis-court-description/Create.vue'),
        params: true,
      },
      {
        name: 'dashboardTennisCourtDescritpionEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/tennis-court-description/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;