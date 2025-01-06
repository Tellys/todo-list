const routes = [
  /*****************************
 * TennisCourt
 *****************************/
  {
    path: '/tennis-court',
    name: 'tennisCourt',
    children: [
      {
        name: 'tennisCourtVefify',
        path: "vefify/:url",
        component: () => import('@/views/public/tennis-court/Verify.vue'),
        params: true,
        meta: { authOnly: true },
      },
      {
        name: 'tennisCourtSearchCnpj',
        path: "search-cnpj",
        component: () => import('@/views/public/tennis-court/SearchCnpjApi'),
      },
      {
        name: 'profile',
        path: "profile",
        component: () => import('@/views/public/tennis-court/Profile'),
        meta: { authOnly: true },
      },
      {
        name: 'tennisCourtCreateSimple',
        path: "create-simple",
        component: () => import('@/views/public/tennis-court/CreateSimple'),
      },
      {
        name: 'tennisCourtList',
        path: "list",
        component: () => import('@/views/public/tennis-court/List'),
        params: true,
        meta: { authOnly: true },
      },
      {
        name: 'tennisCourtPlayLoad',
        path: "play-load",
        component: () => import('@/views/public/tennis-court/PlayLoad.vue'),
        params: true,
        meta: { authOnly: true },
      },
      {
        name: 'tennisCourtEdit',
        path: ":id/edit",
        component: () => import('@/views/public/tennis-court/Edit'),
        params: true,
        meta: { authOnly: true },
      },
      {
        name: 'tennisCourtShow',
        path: ":id",
        component: () => import('@/views/public/tennis-court/Show'),
        params: true,
        meta: { authOnly: true },
      },
      {
        name: 'tennisCourtReserve',
        path: ":id/reserve",
        component: () => import('@/views/public/tennis-court/Reserve'),
        params: true,
        meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;