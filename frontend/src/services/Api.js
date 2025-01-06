//import axios from "../axios";
import axios from "axios";
import store from "@/store";
import router from "@/router";
// import FacebookAuth from "./authSocialMeida/FacebookAuth";
// import GoogleAuth from "./authSocialMeida/GoogleAuth";
import MyAlert from "./MyAlert";
import CacheCollection from "./CacheCollection";

const http = axios.create({
    baseURL: `${process.env.VUE_APP_API_URL}`,
    // headers: {
    //   //Accept: "application/json",
    // },
});

//http.defaults.headers.common['Accept'] = 'application/json';
//http.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
// http.defaults.headers.common['Access-Control-Allow-Headers'] = '*';
// http.defaults.headers.common['Access-Control-Allow-Credentials'] = 'true';
// http.defaults.withCredentials = true;
// http.defaults.withXSRFToken = true;
//http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// Override timeout default for the library
// Now all requests using this instance will wait 2.5 seconds before timing out
//instance.defaults.timeout = 2500;

// Override timeout for this request as it's known to take a long time
// instance.get('/longRequest', {
//   timeout: 5000
// });

http.interceptors.request.use((config) => {
    store.commit("loading/setLoading", true);
    return config;
});

http.interceptors.response.use(
    (res) => {
        store.commit("loading/setLoading", false);
        return Promise.resolve(res.data);
    },
    (err) => {
        store.commit("loading/setLoading", false);

        //if Unauthenticated
        if (err?.response?.status === 401) {
            router.push(`/login?redirect=${router.options.history.state.current}`);
        }

        return Promise.reject(err);
    }
);
class Api {

    api = http;
    d1 = new Date().getTime();
    d2 = new Date(localStorage.getItem('tokenExpiresAt') ?? 0).getTime();
    sessionCountDownInterval;
    //timeNeedRefreshToken = process.env.TIME_TO_REFRESH_TOKEN //60 * 60 * 1000
    timeNeedRefreshToken = 2 * 60 * 1000; // 2min

    ///
    interceptDefault() {
        this.api.interceptors.request.use((config) => {
            store.commit("loading/setLoading", true);
            return config;
        });

        this.api.interceptors.response.use(
            (res) => {
                store.commit("loading/setLoading", false);
                return Promise.resolve(res.data);
            },
            (err) => {
                store.commit("loading/setLoading", false);
                return Promise.reject(err);
            }
        );
    }

    ///
    isLoggedIn(boolean = false) {

        if (!localStorage.getItem('tokenExpiresAt')) {
            return false;
        }

        // reset this.d2 e this.d1
        let dif = this.funcD1D2();

        //se dif = 0 ou negativo
        if (dif <= 0) {

            if (boolean) {
                return false;
            }

            return this.logout('logout', 'sessionExpires')
        }

        //chamamos o sessionCountDown aqui pois acima, verificamos se a var 'dif' é menor que zero
        // a sessionCountDown é interessante ser chamada com 'dif' > 0
        this.sessionCountDown(dif)

        //se dif menor que 2
        if (dif <= this.timeNeedRefreshToken) {
            if (boolean) {
                return false;
            }

            var varMyAlert = MyAlert.init();
            varMyAlert.config.toast = true;
            varMyAlert.config.timer = dif;
            varMyAlert.config.position = 'bottom-end'
            varMyAlert.config.icon = 'warning';
            varMyAlert.config.showClass = {
                popup: `
                animate__animated
                animate__headShake
                animate__infinite
                `};
            varMyAlert.alertConfirm('Login expirando, deseja renovar?', '')
                .then((response) => {
                    if (response) {
                        return this.refresToken();
                    }
                });
            return true;
        }

        //se dif maior que 2
        if (dif > this.timeNeedRefreshToken) {
            return true;
        }

        return this.checkLogin();
    }

    ///
    async userLoggedIn(){        
        let r = CacheCollection.get('user');
        if (r) {
            return r;
        }

        r = this.get('user/profile');
        return r?.data;
    }

    ///
    sessionCountDown(timer = 0) {

        this.sessionCountDownInterval = setInterval(() => {
            if (timer === 0) {
                this.isLoggedIn();
                clearInterval(this.sessionCountDownInterval)
            } else {
                timer--;

                this.funcD1D2();
                let dif = Math.floor(this.d2 - this.d1);

                // só chama a função quando necessário refresh
                if (dif <= this.timeNeedRefreshToken) {
                    this.isLoggedIn();
                }
                //console.log(timer, dif, this.timeNeedRefreshToken)
            }
        }, this.timeNeedRefreshToken - 1);

        return timer;
    }

    ///
    funcD1D2() {
        this.d1 = new Date().getTime();
        this.d2 = new Date(localStorage.getItem('tokenExpiresAt')).getTime();
        let r = this.d2 - this.d1;
        store.commit("countDown/SET_TIME_TO_COUNT_DOWN", r);
        return r;
    }

    ///
    async csrf() {
        //return true;
        return await this.api.get('csrf-cookie', {
            headers: this.headersBearer()
        }).catch(async(error) => {
            console.log('async csrf', error);
            await this.logoutClearSystem();
            await this.logoutSocialMedia();
                    
            var varMyAlert = MyAlert.init(); varMyAlert.alertError(this.displayError("Erro Crítico CSRF"));
            router.push('/login');
            return false
        })
    }

    ///
    async login(data, urlReturn = (this.$route?.query?.redirect ?? '/')) {

        console.log(data, urlReturn);

        await this.csrf();

        await this.api.post('login', data, {
            headers: this.headersBearer()
        }).then((response) => {

            if (!this.auth(response)) {
                var varMyAlert = MyAlert.init();
                varMyAlert.alertError(this.displayError('Usuário e Senha incorretos'));
                return false;
            }

            router.push(response?.redirect ?? { path: this.redirectValidation(urlReturn) });

            var varMyAlertToast = MyAlert.init();
            varMyAlertToast.toast({
                title: response.message,
                text: '',
                icon: 'success',
            });

            return;

        }, (error) => {
            var varMyAlert = MyAlert.init();
            varMyAlert.alertError(this.displayError(error));
            return false;
        })
    }

    ///
    async logout(path = 'logout', urlReturn = 'success') {
        return await this.api.get(path, [], {
                    //return this.api.get(path, [], {
                    headers: this.headersBearer()
                }).then(async (response) => {

                    await this.logoutClearSystem();
                    await this.logoutSocialMedia();

                    router.push({ name: urlReturn });

                    var varMyAlert = MyAlert.init();
                    return await varMyAlert.toast({
                        title: 'Sucesso',
                        text: response.message,
                        icon: 'success',
                    })

                }, (error) => {
                    var varMyAlert = MyAlert.init();
                    varMyAlert.alertError(this.displayError(error));
                    router.push({ name: 'login' });
                    return false;
                });
    }

    ///
    async auth(response) {

        if (!response.data.dataToken.token) {
            var varMyAlert = MyAlert.init();
            varMyAlert.alertError(this.displayError('Erro, tente novamente!'));
            return false;
        }

        await this.setLocalStorage(response);
        return true;
    }

    ///
    async checkLogin() {
        await this.api.get('user/check-login', {
            headers: this.headersBearer()
        }).then(
            (response) => {

                if (response.success) {
                    this.funcD1D2();
                    return true;
                }
                return false;

            }, (error) => {
                console.log('checkLogin', error);
                return false;
            }
        );
    }

    ///
    async refresToken() {
        if (!this.isLoggedIn()) {
            return false;
        }
        if (this.funcD1D2() <= 0) {

            var varMyAlert = MyAlert.init(); varMyAlert.alertConfirm('Seu token expirou!', 'Deseja refazer o login?')
                .then((response) => {

                    console.log(response);

                    if (response) {
                        return router.push('/login');
                    } else {
                        return this.logout();
                    }
                });
            return false;
        }
        await this.get('user/refresh-token')
            .then((response) => {
                if (!response.success) {
                    router.push({ path: '/login' });
                    return false;
                }

                this.logoutClearSystem()
                this.setLocalStorage(response);
                this.headersBearer();
                this.funcD1D2()

                var varMyAlert = MyAlert.init();
                varMyAlert.popUp({
                    title: 'Sucesso',
                    text: response.message,
                    icon: 'success',
                })

                return true;
            }, (error) => {
                var varMyAlert = MyAlert.init();
                varMyAlert.alertError(this.displayError(error));
                router.push({ path: '/login' });
                return false;
            }
            );
    }

    ///
    async setLocalStorage(response) {

        localStorage.setItem("authOnly", true);

        //data.dataToken
        localStorage.setItem('tokenExpiresAt', response.data.dataToken.expires_at);
        localStorage.setItem('token', response.data.dataToken.token);

        //data
        //localStorage.setItem('userId', response.data.id);
        CacheCollection.add('user',response.data);
        //localStorage.setItem('user', response.data);
        localStorage.setItem('userName', response.data.name);
        localStorage.setItem('userImage', response.data.image ? process.env.VUE_APP_API_URL_STORAGE + response.data.image : process.env.VUE_APP_USER_DEFAULT_IMAGE);
        localStorage.setItem('userEmailVerified', response.data.email_verified_at ?? false);
        this.d2 = response.data.expires_at;

        this.headersBearer();
        this.funcD1D2()

        //store for user logged in
        store.commit('login/SET_LOGGED_IN_USER', response.data)
    }

    ///
    async logoutSocialMedia() {
        // await FacebookAuth.logout();
        // await GoogleAuth.logout();
    }

    ///
    async create(path, data) {
        return await this.api.post(path, data, {
            headers: this.headersBearer()
        })
            .then((response) => {

                if (!data?.alertDefault) {
                    var varMyAlert = MyAlert.init(); varMyAlert.alertSuccess(response);
                }

                // array = {path: /}
                if (data?.redirect) {
                    router.push(data.redirect);
                }

                return response;
            }, (error) => {
                var varMyAlert = MyAlert.init();
                varMyAlert.alertError(this.displayError(error));
                return false;
            }
            );
    }

    ///
    // path = /path/
    async getAll(path) {
        return await this.api.get(path, {
            headers: this.headersBearer()
        });
    }

    ///
    // path = /path/?id
    async get(path) {
        return await this.api.get(path, {
            headers: this.headersBearer()
        }).then((response) => {
            return response;
        }, (error) => {
            var varMyAlert = MyAlert.init();
            varMyAlert.alertError(this.displayError(error));
            return false;
        });
    }

    ///
    // path = /path/, data
    async post(path, data, contentType = 'application/json') {
        return await this.api.post(path, data, {
            headers: this.headersBearer(contentType)
        }).then((response) => {
            return response;
        }, (error) => {
            var varMyAlert = MyAlert.init();
            varMyAlert.alertError(this.displayError(error));
            return error;
        })
    }

    ///
    // path = /path/id, data
    async patch(path, data) {
        return await this.update(path, data);
    }    // path = /path/id, data
    
    ///
    async put(path, data) {
        return await this.update(path, data);
    }
    
    ///
    // path = /path/id, data
    async update(path, data, alert = true) {
        console.log('path, data', path, data);
        return await this.api.patch(path, data, {
            headers: this.headersBearer()
        })
            .then((response) => {

                if (!data?.alertDefault && alert) {
                    var varMyAlert = MyAlert.init(); varMyAlert.alertSuccess(response);
                }

                // array = {path: /}
                if (data?.redirect) {
                    router.push(data.redirect);
                }

                return response;
            }, (error) => {
                var varMyAlert = MyAlert.init();
                varMyAlert.alertError(this.displayError(error));
                return false;
            }
            );
    }

    ///
    // path = /path/id
    async delete(path) {
        var varMyAlert = MyAlert.init();
        return await varMyAlert.alertConfirm()
            .then(async (confirm) => {
                if (confirm) {
                    await this.api.delete(path, {
                        headers: this.headersBearer()
                    })
                        .then((response) => {
                            console.log('delete', response)

                            var varMyAlert = MyAlert.init();
                            varMyAlert.alertSuccess(response);

                            return response;
                        }, (error) => {
                            var varMyAlert = MyAlert.init();
                            varMyAlert.alertError(error);
                            return false;
                        }
                        );
                } else { return confirm; }
            })
    }

    ///
    // path = /path/id
    async forceDelete(path) {
        var varMyAlert = MyAlert.init();
        return await varMyAlert.alertConfirm()
            .then(async (confirm) => {
                if (confirm) {
                    await this.api.delete(path, {
                        headers: this.headersBearer()
                    })
                        .then((response) => {
                            console.log('delete', response)

                            var varMyAlert = MyAlert.init();
                            varMyAlert.alertSuccess(response);

                            return response;
                        }, (error) => {
                            var varMyAlert = MyAlert.init();
                            varMyAlert.alertError(error);
                            return false;
                        }
                        );
                } else { return confirm; }
            })
    }

    ///
    // path = /path/
    deleteAll(path) {
        return this.api.delete(path);
    }

    ///
    // path = /path/, key, value
    findByTitle(path, key, value) {
        return this.api.get(path + '?' + key + '=' + value);
    }

    ///
    displayError(error) {
        console.log('linha 179 api.js', error)

        let r = '';

        if (error.statusText) {
            r = error.statusText;
        }

        // error of axios
        if (error.code) {
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

            default:
                break;
        }
        return r;
    }

    ///
    headersBearer(contentType = 'application/json') {

        let bearer = localStorage.getItem("token");
        if (bearer) {

            this.api.defaults.withCredentials = true;
            this.api.defaults.withXSRFToken = true;
            this.api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
            this.api.defaults.headers.common['Content-Type'] = contentType;

            //this.api.defaults.headers.common['Content-Type'] = 'application/json';         
            //this.api.defaults.headers.common['Content-Type']= 'multipart/form-data';

            // this.api.defaults.headers.common['Accept'] = 'application/json';
            this.api.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
            // this.api.defaults.headers.common['Access-Control-Allow-Headers'] = '*';
            // this.api.defaults.headers.common['Access-Control-Allow-Credentials'] = 'true';
            // this.api.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        }
        return;
    }

    ///
    logoutClearSystem() {
        localStorage.clear();
        clearInterval(this.sessionCountDownInterval);

        //store for user logged OUT
        store.commit('login/CLEAR_USER_DATA')

    }

    ///
    redirectValidation(vars){
        vars = vars.toString().toLowerCase();
        switch (vars) {
            case "/success":
            case "/session-expires":    
                return "/";

            default:
                return vars;
        }
    }
}

export default new Api();