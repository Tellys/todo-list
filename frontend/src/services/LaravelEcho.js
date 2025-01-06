import Echo from 'laravel-echo';
import Api from './Api';
require('pusher-js');

class LaravelEcho {

    config;

    ///
    constructor(properties = {}) {
        this.config = {
            broadcaster: 'reverb',
            encrypted: process.env.VUE_APP_REVERB_ENCRYPTED,
            key: process.env.VUE_APP_REVERB_KEY, // import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: process.env.VUE_APP_REVERB_WS_HOST, //import.meta.env.VITE_REVERB_HOST,
            wsPort: process.env.VUE_APP_REVERB_WS_PORT, //import.meta.env.VITE_REVERB_PORT,
            wssPort: process.env.VUE_APP_REVERB_WSS_PORT, //import.meta.env.VITE_REVERB_PORT,
            forceTLS: process.env.VUE_APP_REVERB_FORCE_TLS, //(import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: process.env.VUE_APP_REVERB_ENABLED_TRANSPORTS.split(","),
            //host: window.location.hostname + ':8080',
            // auth: {
            //     headers: {
            //         'Authorization': 'Bearer ' + this.token  // Certifique-se de passar o token de autenticação correto aqui
            //     }
            // }
        };

        Object.assign(this, properties);
    }

    ///
    init() {
        new this.constructor();
        return new Echo(this.config);
    }

    ///
    auth() {
        const authConfigVars = {
            encrypted: process.env.VUE_APP_REVERB_ENCRYPTED === true,
            broadcaster: 'reverb',
            key: process.env.VUE_APP_REVERB_KEY, // import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: process.env.VUE_APP_REVERB_WS_HOST, //import.meta.env.VITE_REVERB_HOST,
            wsPort: process.env.VUE_APP_REVERB_WS_PORT, //import.meta.env.VITE_REVERB_PORT,
            wssPort: process.env.VUE_APP_REVERB_WSS_PORT, //import.meta.env.VITE_REVERB_PORT,
            forceTLS: process.env.VUE_APP_REVERB_FORCE_TLS === true, //(import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: process.env.VUE_APP_REVERB_ENABLED_TRANSPORTS.split(","),
            authorizer: (channel) => {
                return {
                    authorize: async (socketId, callback) => {
                        //  console.log('socketId', socketId)
                        //  console.log('callback', callback)
                        //  console.log('channel', channel)

                        // await Api.csrf()
                        //     .catch((errCsrf) => {
                        //     callback(false, errCsrf);
                        // });

                        await Api.api.post(process.env.VUE_APP_REVERB_AUTH_END_POINT, {
                            socket_id: socketId,
                            channel_name: channel.name
                        }, {
                            headers: Api.headersBearer('application/json')
                        }).then(response => {
                                console.log('success:', response)
                                callback(false, response);
                            })
                            .catch(error => {
                                console.log('error:',error)
                                console.log('error:',error)
                                callback(true, error);
                            });
                    }
                }
            }
        };
        return new Echo(authConfigVars);
    }

}
export default new LaravelEcho();