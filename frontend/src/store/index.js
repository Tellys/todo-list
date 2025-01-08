import { createStore } from 'vuex'
import loading from "./modules/loading";
import countDown from "./modules/countDown";
import dynamicForm from "./modules/dynamicForm";
import searchCpfCnpjApi from './modules/searchCpfCnpjApi';
import messages from './modules/messages';
import login from './modules/login';
import tennisCourt from './modules/tennisCourt';
import tennisCourtInvolvement from './modules/tennisCourtInvolvement';
import tennisCourtMedia from './modules/tennisCourtMedia';
import tennisCourtOpeningHour from './modules/tennisCourtOpeningHour';
import product from './modules/product';
import tennisCourtCalendar from './modules/tennisCourtCalendar';
import cart from './modules/cart';
import paymentMethod from './modules/payment-method';
import customerRequest from './modules/customerRequest';
import task from './modules/task';


//import users from "./modules/users";
//import usersForm from "./modules/components/usersForm";

export default createStore({
    modules: {
        loading,
        countDown,
        dynamicForm,
        searchCpfCnpjApi,
        //users,
        //usersForm,
        messages,
        login,
        tennisCourt,
        tennisCourtInvolvement,
        tennisCourtMedia,
        tennisCourtOpeningHour,
        product,
        tennisCourtCalendar,
        cart,
        paymentMethod,
        customerRequest,
        task
    },
})