import { createStore } from 'vuex'
import loading from "./modules/loading";
import countDown from "./modules/countDown";
import dynamicForm from "./modules/dynamicForm";
import searchCpfCnpjApi from './modules/searchCpfCnpjApi';
import messages from './modules/messages';
import login from './modules/login';

import task from './modules/task';


//import users from "./modules/users";
//import usersForm from "./modules/components/usersForm";

export default createStore({
    modules: {
        loading,
        countDown,
        dynamicForm,
        searchCpfCnpjApi,
        messages,
        login,
        
        task
    },
})