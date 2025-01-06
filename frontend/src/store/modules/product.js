import Api from "@/services/Api"

// initial state
const state = () => ({
  getProductsItemsToTennisCourtId: null,
})

// getters
const getters = {
  getProductsItemsToTennisCourtId: state => state.getProductsItemsToTennisCourtId,
}

// actions
const actions = {

  ///
  async productsItemsToTennisCourtId({ commit }, tennisCourtId) {
    await Api.get('product/list-items-to-tennis-court/' + tennisCourtId).then( async (r) => {
      return await commit('GET_ITEMS_TO_TENNIS_COURT_ID', r);
    })
  },

  ///
  async updateItem({ dispatch }, data) {
    await Api.update('product/' + data.id, data).then(async () => {
      return await dispatch('getProductsItemsToTennisCourtId', data.tennis_court_id);
    })
  },

  ///
  async deleteItem({ dispatch }, data) {
    await Api.delete('product/' + data.id).then(async () => {
      return await dispatch('getProductsItemsToTennisCourtId', data.tennis_court_id);
    })
  },
}

// mutations
const mutations = {
  GET_ITEMS_TO_TENNIS_COURT_ID: (state, v) => {
    state.getProductsItemsToTennisCourtId = v
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