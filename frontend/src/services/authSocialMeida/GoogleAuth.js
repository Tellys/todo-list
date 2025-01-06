import axios from "axios";
import { googleSdkLoaded, googleLogout } from "vue3-google-login"
import Api from "../Api";

import store from "@/store";
class GoogleAuth {

    userIdSub = null // user unique id Google
    userAccessToken = null

    async login() {
        return googleSdkLoaded((google) => {
            google.accounts.oauth2.initCodeClient({
                client_id: `${process.env.VUE_APP_GOOGLE_CLIENT_ID}`,
                client_secret: `${process.env.VUE_APP_GOOGLE_CLIENT_SECRET}`,
                scope: 'email profile openid',
                callback: (response) => {
                    store.commit("loading/setLoading", false);
                    console.log("Handle the response", response)
                    if (response.code) {
                        store.commit("loading/setLoading", true);
                        this.sendCodeToBackend(response.code);
                    }
                },
            }).requestCode()
        })
    }

    async sendCodeToBackend(code) {
        try {
            await axios.post(
                "https://oauth2.googleapis.com/token",
                {
                    code,
                    client_id: `${process.env.VUE_APP_GOOGLE_CLIENT_ID}`,
                    client_secret: `${process.env.VUE_APP_GOOGLE_CLIENT_SECRET}`,
                    redirect_uri: "postmessage",
                    grant_type: "authorization_code"
                }
            ).then(async (responseToken) => {

                console.log('responseToken', responseToken)

                if (!responseToken === undefined) {
                    store.commit("loading/setLoading", false);
                    Api.alertError(Api.displayError('Erro, ao validar o Google Token!'));
                }

                this.userAccessToken = responseToken.data.access_token;

                await axios.get(
                    "https://www.googleapis.com/oauth2/v3/tokeninfo",
                    {
                        headers: {
                            Authorization: `Bearer ${this.userAccessToken}`
                        }
                    }
                ).then((responseTokeninfo) => {

                    this.userIdSub = responseTokeninfo.data.sub

                    console.log('responseTokeninfo.sub', responseTokeninfo.data.sub)

                    //user is logged
                }).catch((errTokeninfo) => {
                    console.error("Erro ao verificar o 'tokeninfo'.", errTokeninfo);
                    Api.alertError(Api.displayError('Erro ao verificar o tokeninfo!'));
                });

                // Fetch user details using the access token
                await axios.get(
                    "https://www.googleapis.com/oauth2/v3/userinfo",
                    {
                        headers: {
                            Authorization: `Bearer ${this.userAccessToken}`
                        }
                    }
                ).then((responseUserInfo) => {

                    if (!responseUserInfo === undefined) {
                        store.commit("loading/setLoading", false);
                        Api.alertError(Api.displayError('Erro, ao recuperar seus dados no Google!'));
                    }

                    this.userDetails = responseUserInfo.data;
                    //user is logged
                    return this.saveUser(this.userDetails);
                }).catch((errUserInfo) => {
                    console.error("(1) Failed to fetch user details.", errUserInfo);
                    Api.alertError(Api.displayError('Erro, ao solicitar Informações do usuário via o Google Token!'));
                });

            }).catch((err) => {
                console.error("(2) Failed to fetch user details.", err);
                Api.alertError(Api.displayError('Erro, ao solicitar Informações do usuário via o Google Token!'));
            });

        } catch (error) {
            console.error("Token exchange failed:", error);
            Api.alertError(Api.displayError('Erro, ao tentar fazer login com sua conta Google!'));
        }
    }

    async logout() {
        console.log('Googlehe.logout()', googleLogout());
        return googleLogout();
    }

    async saveUser(data) {
        data['type_login'] = 'google';
        return await Api.post('user/save-for-auth-social-media', data
        ).then((r) => {
            console.log('user/save-for-auth-social-media', r)
            return Api.auth(r)
        })
    }
}

export default new GoogleAuth();
