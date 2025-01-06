const routes = [
  /*****************************
 * Cart
 *****************************/
  {
    path: '/cart',
    name: 'cart',
    params: true,
    redirect: { name: 'dashboardCartShow', params:{id:'all'} }
  },
  //fim
];
export default routes;