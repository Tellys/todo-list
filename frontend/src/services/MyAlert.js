import Swal from 'sweetalert2';
import 'animate.css';

class MyAlert {

    config;
    popUpTimer;
    popUpToast;
    popUpTimerConfig;
    popUpToastConfig;

    constructor(properties = {}) {
        this.config = {
            position: 'center',
        };

        this.popUpTimer = false;
        this.popUpToast = false;
        this.popUpTimerConfig = {
            timerProgressBar: true,
            timer: 3500,
        };

        this.popUpToastConfig = {
            showCloseButton: true,
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
        }

        Object.assign(this, properties);
    }

    init() {
        return new this.constructor();
    }

    get popUpToast() {
        return this.popUpToast;
    }
    set popUpToast(x) {
        this.popUpToast = x;
    }

    get popUpTimer() {
        return this.popUpTimer;
    }
    set popUpTimer(x) {
        this.popUpTimer = x;
    }

    async popup(myObject = {}) {
        return this.popUp(myObject)
    }
    async popUp(myObject = {}) {

        //this.init();
        let config = this.config;

        if (this.popUpTimer) {
            config = Object.assign(config, this.popUpTimerConfig);
        }

        if (this.popUpToast) {
            config = Object.assign(config, this.popUpToastConfig);
        }

        const myToast = Swal.mixin(config);
        return await myToast.fire(myObject)
    }

    async toast(myObject) {
        this.popUpToast = true;
        this.popUpTimer = true;
        return await this.popUp(myObject);
    }

    /**
     * 
     * @param {String} title 
     * @param {String} text 
     * @returns {bool}
     */
    async alertConfirm(title = 'Confirme', text = 'Confirma a exlusão do item?') {
        return await this.popUp({
            title: title,
            html: text,
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

    /**
     * 
     * @param {Array||Object} error 
     * @returns false
     */
    async alertError(error) {

        // caso o servidor apresente alguma instabilidade temporária, o sistema irá iginorar
        if (error === 'Server Error') {
            return;
        }

        let myText = this.displayError(error);
        await this.popUp({
            title: 'OPPS',
            html: myText,
            icon: 'warning',
        }).then(() => {
            return false;
        });

        return false;
    }

    async alertSuccess(success = 'Success') {
        let myText = this.displayError(success);
        await this.popUp({
            title: myText.title ?? 'Sucesso',
            html: myText,
            icon: myText.icon ?? 'success',
        }).then(() => {
            return true;
        });
    }

    async alertCountDown(myHtml = "", myTitle = "Aguarde...", myTimer = '3000', myIcon = false,) {

        let timerInterval;
        return await this.popUp({
            title: myTitle,
            html: myHtml,
            timer: myTimer,
            icon: myIcon,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                // myHtml + ' Tempo restante: <b></b>'
                // const timer = Swal.getPopup().querySelector("b");
                // timerInterval = setInterval(() => {
                //     timer.textContent = `${Swal.getTimerLeft()/1000}`;
                // }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            // Read more about handling dismissals below
            if (result.dismiss === Swal.DismissReason.timer) {
                return true;
            }
            return false;
        });
    }

    confirm(type, other = []) {
        type = type ? type.toLowerCase() : false;
        switch (type) {
            case 'confirm':
            case 'confirmar':
            case 'confirmacao':
                this.popUp({
                    title: other.title ?? 'Confirma sua ação?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    denyButtonText: 'Não',
                    customClass: {
                        actions: other.action ?? 'my-actions',
                        cancelButton: 'order-1 right-gap',
                        confirmButton: 'order-2',
                        denyButton: 'order-3',
                    }
                })
                break;

            default:
                this.popUp({
                    title: other.title ?? 'Mensagem',
                    icon: other.type ?? 'success',
                });
                break;
        }
        return;
    }

    displayError(error) {
        console.log(error);
        let r = error;

        if (error.statusText) {
            r = error.statusText;
        }

        // error of axios
        if (error.code || error.message) {
            r = error.message;
        }

        // error of api laravel
        if (error.response) {
            r = error.response.data.error ?? error.response.data.message;
        }

        switch (r.toString().trim().toLowerCase()) {
            case ('AxiosError: Network Error').toLowerCase():
                r = 'Servidor não conectado! Tente novamente mais tarde';
                break;
            case ('Validation Error').toLowerCase():
                r = 'Existem campos vazios obrigatórios';
                break;
            case ('request failed with status code 401').toLowerCase():
                r = 'Usuário não logado / not auth';
                break;
            case ('Request failed with status code 409').toLowerCase():
                r = 'O servidor não pôde processar o pedido. Conflito!';
                break;
            case ('User denied Geolocation').toLowerCase():
                r = 'Localização negada pelo usuário!';
                break;
            case ('Unauthenticated.').toLowerCase():
                r = 'Usuário não logado ou Não Autorizado. Faça Login!';
                break;

            default:
                break;
        }
        return r;
    }
}
export default new MyAlert();