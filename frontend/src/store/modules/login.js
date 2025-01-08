import router from "@/router";
import Api from "@/services/Api";
import MyAlert from "@/services/MyAlert";

const state = () => ({
  accessToken: "",
  loggedInUser: null,
  isAuthenticated: false,
  isUserVerifyEmail: false,
})

const getters = {
  loggedInUser: state => state.loggedInUser,
  loggedInUserId: state => state.loggedInUser?.id,
  loggedInUserToken:state => state.loggedInUser?.dataToken?.token ?? null,
  loggedInUserEmail(state) {
    if (!state.loggedInUser?.email) {
      return false
    }
    return state.loggedInUser.data.email
  },

  accessToken: state => state.accessToken,

  isAuthenticated: state => state.isAuthenticated,
  isUserVerifyEmail: state => state.loggedInUser?.email_verified_at ?? false,

};

const actions = {

  ///
  async login(data, route = null) {
    return await Api.login(data, route)
  },

  /* ///
  async isAuthenticated({ commit }) {
    return await Api.isLoggedIn().then((response) => {
      console.log(response)
      commit('SET_IS_AUTHENTICATED', response);
    })
  }, */

  ///
  async logout({ commit }) {
    await commit('SET_LOGGED_OUT_USER');
    await Api.logout();
  },

  ///
  async getLoggedInUser({ commit }) {
    return await Api.get('user/profile').then((response) => {
      commit('SET_LOGGED_IN_USER', response);
    })
  },

  ///
  async forgotPassword(data) {

    await Api.post('user/forgot-password', data).then((response) => {
      console.log(response);

      var varMyAlert = MyAlert.init();
      varMyAlert.popup({
        title: response.message,
        text: '',
        icon: response.message ? 'success' : 'warning',
      });

      router.push(response.redirect ?? 'success');

      return

    })
  },

  ///
  async sendEmailToVerify({ commit, dispatch, getters }) {

    await dispatch('getLoggedInUser');

    var varMyAlert2 = MyAlert.init();
    return await varMyAlert2.alertConfirm('Verificação Necessária', 'Enviaremos um e-mail verificador para: ' + getters.loggedInUserEmail)
      .then(async (confirm) => {
        if (confirm) {
          await Api.get('email/verification-notification')
            .then((response) => {

              commit('SET_IS_USER_VERIFY_EMAIL', response);
              console.log('response', response)

              var varMyAlert = MyAlert.init();
              return varMyAlert.toast({
                title: response.message,
                text: '',
                icon: 'success',
              });

            }, (error) => {
              var varMyAlert = MyAlert.init();
              console.log('deu erro aqui', error)
              return varMyAlert.alertError(error);
            }
            );
        } else { return confirm; }
      })
  },
}

const mutations = {

  ///
  SET_USER_LOGGED: function (state, v) {
    state.loggedInUser = v;
  },

  ///
  SET_REFRESH_TOKEN: function (state, refreshToken) {
    state.refresh_token = refreshToken;
  },

  ///
  SET_ACCESS_TOKEN: function (state, accessToken) {
    state.access_token = accessToken;
  },

  ///
  // sets state with user information and toggles 
  // isAuthenticated from false to true
  SET_LOGGED_IN_USER: function (state, user) {
    state.loggedInUser = user;
    state.isAuthenticated = true;
    return;
  },

  ///
  SET_LOGGED_OUT_USER: function (state) {
    state.loggedInUser = null;
    state.isAuthenticated = false;
  },


  ///
  SET_IS_USER_VERIFY_EMAIL: function (state, bool) {
    state.isUserVerifyEmail = bool;
  },


  ///
  SET_IS_AUTHENTICATED: function (state, bool) {
    state.isAuthenticated = bool;
  },

  ///
  // delete all auth and user information from the state
  CLEAR_USER_DATA: function (state) {
    state.refresh_token = "";
    state.access_token = "";
    state.loggedInUser = null;
    state.isAuthenticated = false;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}