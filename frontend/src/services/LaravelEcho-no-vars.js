import Echo from 'laravel-echo';
import Api from './Api';
require('pusher-js');

class LaravelEcho {

    config;

    ///
    constructor(properties = {}) {
        this.config = {
            broadcaster: 'reverb',
            encrypted: true,
            key: 'eneqchrwdbaksgut6j1r', // import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: 'api.beachtennis.test', //import.meta.env.VITE_REVERB_HOST,
            wsPort: '8080', //import.meta.env.VITE_REVERB_PORT,
            wssPort: '8080', //import.meta.env.VITE_REVERB_PORT,
            forceTLS: ('http' ?? 'https') === 'https', //(import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
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
            encrypted: true,
            broadcaster: 'reverb',
            key: 'eneqchrwdbaksgut6j1r', // import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: 'api.beachtennis.test', //import.meta.env.VITE_REVERB_HOST,
            wsPort: '8080', //import.meta.env.VITE_REVERB_PORT,
            wssPort: '8080', //import.meta.env.VITE_REVERB_PORT,
            forceTLS: ('http' ?? 'https') === 'https', //(import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
            authorizer: (channel) => {
                return {
                    authorize: async (socketId, callback) => {
                        // console.log('socketId', socketId)
                        // console.log('callback', callback)
                        // console.log('channel', channel)

                        await Api.csrf()
                            .catch((errCsrf) => {
                            callback(false, errCsrf);
                        });

                        await Api.api.post('http://api.beachtennis.test/api/reverb/auth', {
                            socket_id: socketId,
                            channel_name: channel.name
                        }, {
                            headers: Api.headersBearer('application/json')
                        }).then(response => {
                                //console.log('success:', response)
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