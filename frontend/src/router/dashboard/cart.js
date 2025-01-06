const routes = [
  /*****************************
 * Cart
 *****************************/
  {
    path: '/dashboard/cart',
    name: 'dashboardCart',
    redirect: { name: 'dashboardCartShow', params: {id:'all'} },
    //meta: { authOnly: true },
    //component: () => import('@/views/dashboard/cart/Home.vue'),
    children: [
      {
        name: 'dashboardCartCreate',
        path: "create/:id?",
        params: true,
        component: () => import('@/views/dashboard/cart/Create.vue'),
      },
      {
        name: 'dashboardCartList',
        path: "list",
        component: () => import('@/views/dashboard/cart/List.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardCartEdit',
        path: ":id/edit",
        component: () => import('@/views/dashboard/cart/Edit.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardCartShow',
        path: ":id",
        component: () => import('@/views/dashboard/cart/Show.vue'),
        params: true,
        //meta: { authOnly: true },
      },

      {
        name: 'dashboardCartPaymentPix',
        path: "payment/pix",
        component: () => import('@/views/dashboard/cart/PaymentPix.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardCartPaymentCartaoDeCredito',
        path: "payment/cartao-de-credito",
        component: () => import('@/views/dashboard/cart/PaymentCartaoDeCredito.vue'),
        params: true,
        //meta: { authOnly: true },
      },
      {
        name: 'dashboardCartPaymentBoleto',
        path: "payment/boleto",
        component: () => import('@/views/dashboard/cart/PaymentBoleto.vue'),
        params: true,
        //meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;