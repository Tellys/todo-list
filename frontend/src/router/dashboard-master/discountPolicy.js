const routes = [
  /*****************************
 * DiscountPolicy
 *****************************/
  {
    path: '/dashboard-master/discount-policy',
    name: 'dashboardMasterDiscountPolicy',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard-master/discount-policy/Home.vue'),
    children: [
      {
        name: 'dashboardMasterDiscountPolicyCreate',
        path: "create",
        component: () => import('@/views/dashboard-master/discount-policy/Create.vue'),
      },
      {
        name: 'dashboardMasterDiscountPolicyList',
        path: "list",
        component: () => import('@/views/dashboard-master/discount-policy/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardMasterDiscountPolicyEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard-master/discount-policy/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;