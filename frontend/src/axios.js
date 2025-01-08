import axios from "axios";
import store from "@/store";

const http = axios.create({
  baseURL: `${process.env.VUE_APP_API_URL}`,
  // headers: {
  //   //Accept: "application/json",
  // },
});

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