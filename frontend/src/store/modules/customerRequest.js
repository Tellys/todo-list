import Api from "@/services/Api"
import LaravelEcho from '@/services/LaravelEcho';

// initial state
const state = () => ({
  getCustomerRequest: null,
  getCustomerRequestPaymentMethodSelected: null,
  getAllCustomerRequest: null,
  getCheckPaymentIsMade: null,
})

// getters
const getters = {
  getCustomerRequest: state => state.getCustomerRequest,
  getCustomerRequestPaymentMethodSelected: state => state.getCustomerRequestPaymentMethodSelected,
  getAllCustomerRequest: state => state.getAllCustomerRequest,
  getPaymentEfiPayPixCobTxid: state => state.getCustomerRequest?.payment_method?.payment_selected?.txid ?? null,
  //getCheckPaymentIsMade: state => state.getCustomerRequest?.status == "paid" ? true : false,
  getCheckPaymentIsMade(state) {
    if (state.getCustomerRequest?.status == "paid") {
      return true;
    }
    return state.getCheckPaymentIsMade;
  },
  getCustomerRequestPaymentSelected: state => state.getCustomerRequest?.payment_method?.payment_selected ?? null,
}

// actions
const actions = {

  /// customer-request
  async all({ commit }) {
    await Api.get('customer-request/all').then(async (r) => {
      return await commit('SET_ALL_CUSTUMER_REQUEST', r);
    })
  },

  /// customer-request
  async show({ commit }, id) { //this.$route.params.id
    await commit('SET_CUSTUMER_REQUEST', null);
    //await commit('SET_CUSTUMER_REQUEST', null);
    await Api.get('customer-request/' + id).then(async (r) => {
      //console.log('costummerRequest.js / show', r.data);
      //console.log('costummerRequest.js / show', r.data);
      return await commit('SET_CUSTUMER_REQUEST', r.data);
    })
  },

  /// customer-request
  async getCurrentCustomerRequest({ commit }) {
    await Api.get('customer-request/current').then(async (r) => {
      return await commit('SET_VARS', r);
    })
  },

  ///
  async updateItem({ dispatch }, data) {
    await Api.update('customer-request/' + data.id, data).then(async () => {
      return await dispatch('getCustomerRequest', data.tennis_court_id);
    })
  },

  ///
  async deleteItem({ dispatch }, id) {
    await Api.forceDelete('customer-request/' + id + '/force-delete').then(async () => {
      return await dispatch('getCustomerRequest');
    })
  },

  ///
  async checkPaymentPixIsMade({ commit, getters }, txid) {
    if (getters.getCheckPaymentIsMade) {
      return true;
    }

    await Api.get('payment-efi-pay-pix-cob/check-payment-is-made/' + txid).then(async (r) => {
      return await commit('SET_CHECK_PAYMENT_IS_MADE', r.success);
    })

    /***
     * parei aqui , o botão validar o pagamento n esta rodando
     */

  },

  ///
  async checkPaymentIsMade({ commit }, txid) {

    /* if (getters.getCheckPaymentIsMade) {
      console.log('dentro do if getters.getCheckPaymentIsMade', getters.getCustomerRequest);
      return true;
    } */

    await Api.get('payment-efi-pay-pix-cob/check-payment-is-made/' + txid).then(async (r) => {
      console.log('checkPaymentIsMade', txid, r);
      return await commit('SET_CHECK_PAYMENT_IS_MADE', r.success);
    })
  },

  broadcastPaymentPixIsPay({ commit, getters }) {
    var varLaravelEcho = LaravelEcho.auth();
    console.log('payment-pix-is-pay.' + getters.getCustomerRequest?.id + '.' + getters.getPaymentEfiPayPixCobTxid);
    varLaravelEcho.private('payment-pix-is-pay.' + getters.getCustomerRequest?.id + '.' + getters.getPaymentEfiPayPixCobTxid)
      // .notification((notification) => {
      //   console.log('notification.type',notification.type);
      // })
      .listen('PaymentPixIsPayEvent', async (response) => {
        console.log('PaymentPixIsPayEvent', response);
        if (response.status == 'paid') {
          //await commit('SET_CHECK_PAYMENT_IS_MADE', true);  
          await commit('SET_CHECK_PAYMENT_IS_MADE', true);
          await commit('SET_CUSTUMER_REQUEST', response);
          console.log('getters.getCustomerRequest', getters.getCustomerRequest);
          return
        }
      });
  },
}

// mutations
const mutations = {

  ///
  SET_VARS: (state, v) => {
    state.getCustomerRequest = v;
  },

  ///
  SET_CUSTUMER_REQUEST: (state, v) => {
    state.getCustomerRequest = v;
  },

  ///
  SET_CUSTUMER_REQUEST_PAYMENT_METHOD_SELECTED: (state, v) => {
    state.getCustomerRequestPaymentMethodSelected = v;
  },

  ///
  SET_ALL_CUSTUMER_REQUEST: (state, v) => {
    state.getAllCustomerRequest = v;
  },

  ///
  SET_CHECK_PAYMENT_IS_MADE: (state, v) => {
    state.getCheckPaymentIsMade = v;
  },

}

/// fim
export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}