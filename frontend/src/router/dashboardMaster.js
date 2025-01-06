// tennisCourtGroup
import PaginaHomeDashboardMaster from '@/views/dashboard-master/Home.vue';
import TennisCourtGroup from './dashboard-master/tennisCourtGroup';
import TennisCourtType from './dashboard-master/tennisCourtType';
import TennisCourtDescritpionTable from './dashboard-master/tennisCourtDescritpionTable';
import ProductDepartment from './dashboard-master/productDepartment';
import Product from './dashboard-master/product';
import ProductDefault from './dashboard-master/productDefault';
import DiscountPolicy from './dashboard-master/discountPolicy';

const dashboard = [
    {
        path: '/dashboard-master',
        name: 'dashboard-master',
        component: PaginaHomeDashboardMaster,
        params: true,
        //meta: { authOnly: true },
    },
    ...TennisCourtGroup,
    ...TennisCourtType,
    ...TennisCourtDescritpionTable,
    ...ProductDepartment,
    ...Product,
    ...ProductDefault,
    ...DiscountPolicy,
];

export default dashboard;