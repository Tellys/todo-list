// user
const routes = [
  /*****************************
 * User
 *****************************/
  {
    path: '/user',
    name: 'user',
    //component: PaginaUser,
    //  children: [{ path: 'settings', component: AdminSettings }],
    children: [
      {
        name: 'userForgotPassword',
        path: "forgot-password",
        component: () => import('@/views/public/user/ForgotPassword.vue'),
      },
      {
        name: 'userResetPassword',
        path: "reset-password/:token/:email",
        component: () => import('@/views/public/user/ResetPassword.vue'),
        params: true,
      },
      {
        name: 'userVefify',
        path: "vefify/:url",
        component: () => import('@/views/public/user/Verify.vue'),
        params: true,
        meta: { authOnly: true },
      },
      {
        name: 'userSearchCnpj',
        path: "search-cnpj",
        component: () => import('@/views/public/user/SearchCnpjApi.vue'),
        meta: { authOnly: true },
      },
      {
        name: 'userProfile',
        path: "profile",
        component: () => import('@/views/public/user/Profile.vue'),
        params: false,
        meta: { authOnly: true },
      },
      {
        name: 'userCreate',
        path: "create",
        component: () => import('@/views/public/user/Create.vue'),
      },
      {
        name: 'userCreateSimple',
        path: "create-simple",
        component: () => import('@/views/public/user/CreateSimple.vue'),
      },
      {
        name: 'userList',
        path: "list",
        component: () => import('@/views/public/user/List.vue'),
        params: true,
        meta: { authOnly: true },
      },
      {
        name: 'userEdit',
        path: ":id/edit",
        component: () => import('@/views/public/user/Edit.vue'),
        params: true,
        meta: { authOnly: true },
      },
      {
        name: 'userShow',
        path: ":id",
        component: () => import('@/views/public/user/Show.vue'),
        params: true,
        meta: { authOnly: true },
      },
    ]
  },
  //fim
];
export default routes;