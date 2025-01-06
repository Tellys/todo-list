const routes = [
  /*****************************
 * TennisCourtDescritpion
 *****************************/
  {
    path: '/dashboard/tennis-court-media',
    name: 'dashboardTennisCourtMedia',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard/tennis-court-media/Home.vue'),
    children: [
      {
        name: 'dashboardTennisCourtMediaCreate',
        path: "create/:id?",
        component: () => import('@/views/dashboard/tennis-court-media/Create.vue'),
        params: true,
      },
      {
        name: 'dashboardTennisCourtMediaEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/tennis-court-media/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;