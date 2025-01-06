import AxiosBrasilApi from "../axiosBrasilApi";
//import ApiDb from '@/services/Api';
import Swal from 'sweetalert2';


/**
 * import Api from "@/services/Api";
 * Vue.prototype.$Api= new ClassApi('/path/');
 * this.$Api.getAll();
 */
class BrasilApi {

    //api = ApiDb;
    apiBrasilApi = AxiosBrasilApi;
    varIsLoggedIn = false;

    // path = /path/?id
    async cnpj(value) {
        return await this.apiBrasilApi.get('cnpj/v1/' + value, {
            headers: this.headersBearer()
        }).then((response) => {
            console.log(response);
            return response;
        }, (error) => {
            console.log(error);
            this.alertError(this.displayError(error));
            return false;
        }
        );
    }

    async alertConfirm(msg = 'Confirma a exlusão do item?') {
        return await Swal.fire({
            title: msg,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Sim',
            denyButtonText: 'Não',
            customClass: {
                //actions: 'my-actions',
                cancelButton: 'order-1 right-gap',
                confirmButton: 'order-2',
                denyButton: 'order-3',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                return true;
            } /* else if (result.isDenied) {
              Swal.fire('Changes are not saved', '', 'info')
            } */
            return false;
        })
    }

    alertError(error) {
        if (error.errors) {
            error = error.message;
        }
        Swal.fire({
            title: 'OPPS',
            text: error,
            icon: 'warning',
        });
        return false;
    }

    alertSuccess(success = 'Success') {
        /* if (success.response) {
            success = success.response.data.message;
        }

        if (success.message) {
            success = success.message;
        } */

        Swal.fire({
            title: success.title ?? 'Sucesso',
            text: success,
            icon: success.icon ?? 'success',
        });
    }

    displayError(error) {
        console.log('linha 179 api.js', error)

        if (error.statusText) {
            error = error.statusText;
        }

        if (error.code) {
            error = error.message;
        }

        // error of api laravel
        if (error.response) {
            console.log('linha 190 api.js', error.response.data)

            error = error.response.data.error ?? error.response.data.message;
        }

        let r = error.toString().trim().toLowerCase();

        switch (r) {
            case ('Network Error').toLowerCase():
                r = 'Servidor não conectado! Tente novamente mais tarde';
                break;
            case ('AxiosError: Network Error').toLowerCase():
                r = 'Servidor não conectado! Tente novamente mais tarde';
                break;
            case ('Validation Error').toLowerCase():
                r = 'Existem campos vazios obrigatórios';
                break;
            case ('request failed with status code 401').toLowerCase():
                r = 'Usuário não logado / not auth';
                break;

            default:
                break;
        }
        return r;
    }

    headersBearer() {
        let bearer = localStorage.getItem("token");
        if (bearer) {
            this.apiBrasilApi.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
            this.apiBrasilApi.defaults.headers.common['Content-Type'] = 'application/json';
        }
        return;
    }
}

export default new BrasilApi();

/**axios.interceptors.response.use(
    response => {
        return response;
    },
    error => {
        if(error.response.status == 403){
            refreshToken()
        }
    }
); */