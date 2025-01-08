import paginaHomeDashboard from '@/views/dashboard/Home';
import task from './dashboard/task';

const dashboard = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: paginaHomeDashboard,
        params: true,
        //meta: { authOnly: true },        
    },
    ...task,
];

export default dashboard;
