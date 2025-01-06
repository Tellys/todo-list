import PaginaHomePublic from '@/views/public/Home';

import User from './public/user';
import TennisCourt from './public/tennisCourt';
import Auth from './public/auth';
import Cart from './public/cart';

const dashboard = [
    {
        path: '/',
        name: 'public',
        component: PaginaHomePublic,
        params: true,
        //meta: { authOnly: false },
    },
    ...User,
    ...TennisCourt,
    ...Auth,
    ...Cart,
];
export default dashboard;