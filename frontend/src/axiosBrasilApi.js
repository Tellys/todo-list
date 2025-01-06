import axios from "axios";
import store from "./store";

//api.defaults.headers.common['Access-Control-Allow-Origin'] = '*';

const http = axios.create({
  baseURL: `https://brasilapi.com.br/api/`,
  headers: {
    //Accept: "application/json",
  },
});

http.defaults.headers.common['Accept'] = 'application/json';
// http.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
// http.defaults.headers.common['Access-Control-Allow-Headers'] = '*';
// http.defaults.headers.common['Access-Control-Allow-Credentials'] = 'true';

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

export default http;