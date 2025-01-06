const routes = [
  /*****************************
 * TennisCourtDescritpion
 *****************************/
  {
    path: '/dashboard/tennis-court-calendar',
    name: 'dashboardTennisCourtCalendar',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard/tennis-court-calendar/Home.vue'),
    children: [
      {
        name: 'dashboardTennisCourtCalendarCreate',
        path: "create/:id?",
        component: () => import('@/views/dashboard/tennis-court-calendar/Create.vue'),
        params: true,
      },
      {
        name: 'dashboardTennisCourtCalendarEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/tennis-court-calendar/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;