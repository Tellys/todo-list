const { defineConfig } = require('@vue/cli-service')
// add require for fs node library
const fs = require('fs');

module.exports = defineConfig({
  transpileDependencies: true,
  chainWebpack: (config) => {
    config.plugin('define').tap((definitions) => {
      Object.assign(definitions[0], {
        __VUE_OPTIONS_API__: 'true',
        __VUE_PROD_DEVTOOLS__: 'false',
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: 'false'
      })
      return definitions
    })
  },
  devServer: {
    // host: 'f.beachtennis.app.br',
    // server: {
    //   type: 'https',
    //   options: {
    //     key: fs.readFileSync('./certs/fullchain.pem'),
    //     cert: fs.readFileSync('./certs/privkey.pem'),
    //   }
    //   /* http2: true,
    //   https: {
    //     key: fs.readFileSync('/path/to/server.key'),
    //     cert: fs.readFileSync('/path/to/server.crt'),
    //     ca: fs.readFileSync('/path/to/ca.pem'),
    //   }, */
    // },
    client: {
      //webSocketURL: 'wss://laravel-vueform.test:8080/ws',
      //webSocketURL: 'wss://localhost:8080/ws',
      /* hostname: '0.0.0.0',
      pathname: '/ws',
      password: 'dev-server',
      port: 8080,
      protocol: 'ws',
      username: 'webpack', */
      progress: true,
      //compress: true,
    },
    proxy: {
      // '/api': {
      //   target: 'https://other-server.example.com',
      //   secure: false,
       

      // "^/api": {
      //   target: 'https://beachtennis.app.br/backend/public/',
      //   ws: true,
      //   changeOrigin: false
      // },
    },
  }
})
