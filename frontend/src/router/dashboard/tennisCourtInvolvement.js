const routes = [
  /*****************************
 * TennisCourtInvolvement
 *****************************/
  {
    path: '/dashboard/tennis-court-involvement',
    name: 'dashboardTennisCourtInvolvement',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard/tennis-court-involvement/Home.vue'),
    children: [
      {
        name: 'dashboardTennisCourtInvolvementCreate',
        path: "create",
        component: () => import('@/views/dashboard/tennis-court-involvement/Create.vue'),
      },
      {
        name: 'dashboardTennisCourtInvolvementList',
        path: "list",
        component: () => import('@/views/dashboard/tennis-court-involvement/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardTennisCourtInvolvementEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/tennis-court-involvement/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;