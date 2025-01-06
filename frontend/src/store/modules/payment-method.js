import Api from "@/services/Api"

// initial state
const state = () => ({
  getPaymentMethodsEnable: null,
})

// getters
const getters = {
  getPaymentMethodsEnable: state => state.getPaymentMethodsEnable,
}

// actions
const actions = {

  ///
  async functionGetPaymentMethodsEnable({ commit }) {
    await Api.get('payment-method').then(async (r) => {
      return await commit('SET_VARS', r);
    })
  },
}

// mutations
const mutations = {
  SET_VARS: (state, v) => {
    state.getPaymentMethodsEnable = v
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