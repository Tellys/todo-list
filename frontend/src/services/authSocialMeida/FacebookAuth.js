import Api from "../Api";

class FacebookAuth {

    // load facebook sdk script
    init() {

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) { return; }
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    }

    logout() {
        console.log('Facebook.logout() - chamou');

        this.init();

        if (window.fbAsyncInit !== undefined) {
            // //window.fbAsyncInit.hasRun
            window.fbAsyncInit = undefined;
        }

        if (window.fbAsyncInit === undefined && typeof (FB) !== 'undefined') {
            window.fbAsyncInit = new function () {

                FB.init({
                    appId: `${process.env.VUE_APP_FACEBOOK_CLIENT_ID}`,
                    cookie: true,
                    xfbml: true,
                    version: 'v19.0'
                });

                FB.getLoginStatus(function (response) {

                    if (response && response.status === 'connected') {
                        console.log('Logout: ok')
                        return FB.logout();
                        //document.location.reload();
                    } else {
                        console.log('Logout: not loddeg user')
                        //document.location.reload();
                    }
                });
            }
        }
    }

    login() {
        console.log('chamou a func login()');

        this.init()

        if (typeof (FB) !== 'undefined') {

            // wait for facebook sdk to initialize before starting the vue app
            window.fbAsyncInit = new function () {

                FB.init({
                    appId: `${process.env.VUE_APP_FACEBOOK_CLIENT_ID}`,
                    cookie: true,
                    xfbml: true,
                    version: 'v19.0'
                });

                FB.AppEvents.logPageView();

                // auto authenticate with the api if already logged in with facebook
                FB.getLoginStatus(function (response) {

                    if (response.authResponse) {
                        FB.api(
                            '/' + response.authResponse.userID + '/',
                            {
                                fields: 'email,name,picture,birthday,gender,friends,age_range,hometown,likes,link,location,photos,posts,videos',
                                access_token: response.authResponse.accessToken
                            },
                            async function (response) {
                                let data = response;
                                data['type_login'] = 'facebook';
                                data['picture'] = `http://graph.facebook.com/` + data.id + `/picture/?width=300`;

                                const r = await Api.post('user/save-for-auth-social-media', data);
                                return Api.auth(r);
                            }//, { scope: 'public_profile ', return_scopes: true }
                        );

                        /* FB.api(
                            '/me',
                            //{ "scope": "public_profile,id,name,email" },
                            //{ scope: "email" },
                            //'/' + response.id + '/',
                            { fields: 'email,name,picture,birthday,gender' },
                            function (response) {
                                console.log('/me', response);
                            }); */
                    } else {
                        FB.login(function (response) {
                            if (response.status === 'connected') {
                                alert("You are logged in &amp; cookie set!", response);
                                FB.api(
                                    '/' + response.authResponse.userID + '/',
                                    {
                                        fields: 'email,name,picture,birthday,gender,friends,age_range,hometown,likes,link,location,photos,posts,videos',
                                        access_token: response.authResponse.accessToken
                                    },
                                    async function (response) {
                                        let data = response;
                                        data['type_login'] = 'facebook';
                                        data['picture'] = `http://graph.facebook.com/` + data.id + `/picture/?width=300`;
                                        const r = await Api.post('user/save-for-auth-social-media', data);
                                        return Api.auth(r);
                                    }
                                );
                            } else {
                                console.log('Usuário cancelou o login')
                            }
                        },
                            { scope: 'public_profile,email' }
                        );
                    }
                });
            };
        }
        else {
            // wait for facebook sdk to initialize before starting the vue app
            window.fbAsyncInit = function () {
                FB.init({
                    appId: `${process.env.VUE_APP_FACEBOOK_CLIENT_ID}`,
                    cookie: true,
                    xfbml: true,
                    version: 'v19.0'
                });

                FB.AppEvents.logPageView();

                // auto authenticate with the api if already logged in with facebook
                FB.getLoginStatus(function (response) {

                    if (response.authResponse) {
                        FB.api(
                            '/' + response.authResponse.userID + '/',
                            {
                                fields: 'email,name,picture,birthday,gender,friends,age_range,hometown,likes,link,location,photos,posts,videos',
                                access_token: response.authResponse.accessToken
                            },
                            async function (response) {
                                let data = response;
                                data['type_login'] = 'facebook';
                                data['picture'] = `http://graph.facebook.com/` + data.id + `/picture/?width=300`;

                                const r = await Api.post('user/save-for-auth-social-media', data);
                                return Api.auth(r);
                            }
                        );
                    } else {
                        FB.login(function (response) {
                            if (response.status === 'connected') {
                                alert("You are logged in &amp; cookie set!", response);
                                FB.api(
                                    '/' + response.authResponse.userID + '/',
                                    {
                                        fields: 'email,name,picture,birthday,gender,friends,age_range,hometown,likes,link,location,photos,posts,videos',
                                        access_token: response.authResponse.accessToken
                                    },
                                    async function (response) {
                                        let data = response;
                                        data['type_login'] = 'facebook';
                                        data['picture'] = `http://graph.facebook.com/` + data.id + `/picture/?width=300`;

                                        const r = await Api.post('user/save-for-auth-social-media', data);
                                        return Api.auth(r);
                                    }
                                );
                            } else {
                                console.log('Usuário cancelou o login')
                            }
                        },
                            { scope: 'public_profile,email' }
                        );
                    }
                });
            };
        }
    }
}
export default new FacebookAuth();
