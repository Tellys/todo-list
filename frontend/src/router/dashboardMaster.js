// tennisCourtGroup
import PaginaHomeDashboardMaster from '@/views/dashboard-master/Home.vue';

const dashboard = [
    {
        path: '/dashboard-master',
        name: 'dashboard-master',
        component: PaginaHomeDashboardMaster,
        params: true,
        //meta: { authOnly: true },
    },
];

export default dashboard;