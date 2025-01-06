const routes = [
  /*****************************
 * DiscountPolicy
 *****************************/
  {
    path: '/dashboard/discount-policy',
    name: 'dashboardDiscountPolicy',
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard/discount-policy/Home.vue'),
    children: [
      {
        name: 'dashboardDiscountPolicyCreate',
        path: "create",
        component: () => import('@/views/dashboard/discount-policy/Create.vue'),
      },
      {
        name: 'dashboardDiscountPolicyList',
        path: "list",
        component: () => import('@/views/dashboard/discount-policy/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardDiscountPolicyEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/discount-policy/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;