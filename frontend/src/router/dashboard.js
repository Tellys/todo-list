import paginaHomeDashboard from '@/views/dashboard/Home';
import tennisCourt from './dashboard/tennisCourt';
import tennisCourtDescritpion from './dashboard/tennisCourtDescritpion';
import tennisCourtMedia from './dashboard/tennisCourtMedia';
import tennisCourtInvolvement from './dashboard/tennisCourtInvolvement';
import discountPolicy from './dashboard/discountPolicy';
import product from './dashboard/product';
import tennisCourtOpeningHour from './dashboard/tennisCourtOpeningHour';
import cart from './dashboard/cart';
import customerRequest from './dashboard/customerRequest';

const dashboard = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: paginaHomeDashboard,
        params: true,
        //meta: { authOnly: true },        
    },
    ...tennisCourt,
    ...tennisCourtDescritpion,
    ...tennisCourtMedia,
    ...tennisCourtInvolvement,
    ...discountPolicy,
    ...product,
    ...tennisCourtOpeningHour,
    ...cart,
    ...customerRequest,
];

export default dashboard;
