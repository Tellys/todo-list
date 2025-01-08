import PaginaHomePublic from '@/views/public/Home';

import User from './public/user';
import Auth from './public/auth';

const dashboard = [
    {
        path: '/',
        name: 'public',
        component: PaginaHomePublic,
        params: true,
        //meta: { authOnly: false },
    },
    ...User,
    ...Auth,
];
export default dashboard;