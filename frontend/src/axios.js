import axios from "axios";
import store from "@/store";

const http = axios.create({
  baseURL: `${process.env.VUE_APP_API_URL}`,
  // headers: {
  //   //Accept: "application/json",
  // },
});

// http.defaults.headers.common['Accept'] = 'application/json';
// http.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
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
    return Promise.reject(err);
  }
);


/* import get from 'lodash/get'
import router from "./router";

http.interceptors.response.use(response => response, async err => {
  const status = get(err, 'response.status')

  if (status === 419) {
    // Refresh our session token
    await http.get('csrf-token')

    // Return a new request using the original request's configuration
    return axios(err.response.config)
  }

  if (status === 401) {
    // Refresh our session token
    await http.get('logout')
    return router.push('/logout');
  }

  return Promise.reject(err)
}) */

/* http.interceptors.request.use((config) => {
  // Retrieve the CSRF token from the meta tag
  const csrfToken = document.head.querySelector('meta[name="csrf-token"]');

  if (csrfToken) {
      config.headers.common["X-CSRF-TOKEN"] = csrfToken.content;
  } else {
      console.error('CSRF token not found');
  }

  return config;
}); */

export default http;