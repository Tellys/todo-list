const routes = [
  /*****************************
 * TennisCourtOpeningHour
 *****************************/
  {
    path: '/dashboard/tennis-court-opening-hour',
    name: 'dashboardTennisCourtOpeningHour',
    children: [
      {
        name: 'dashboardTennisCourtOpeningHourCreate',
        path: "create/:id?",
        component: () => import('@/views/dashboard/tennis-court-opening-hour/Create.vue'),
        params: true,
      },
      {
        name: 'dashboardTennisCourtOpeningHourEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/tennis-court-opening-hour/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;