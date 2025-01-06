import Api from "@/services/Api"

// initial state
const state = () => ({
  getTennisCourtOpeningHourItemsToTennisCourtId: null,
})

// getters
const getters = {
  getTennisCourtOpeningHourItemsToTennisCourtId: state => state.getTennisCourtOpeningHourItemsToTennisCourtId,
}

// actions
const actions = {

  ///
  async tennisCourtOpeningHourItemsToTennisCourtId({ commit }, tennisCourtId) {
    await Api.get('tennis-court-opening-hour/list-items-to-tennis-court-id/' + tennisCourtId).then((r) => {

      if (r?.data?.success) {
        delete r.data.success;
        delete r.data.listVars;
        delete r.data.campos;
      }
      return commit('GET_ITEMS_TO_TENNIS_COURT_ID', r);
    })
  },

  ///
  async updateItem({ dispatch }, data) {
    await Api.update('tennis-court-opening-hour/' + data.id, data).then(async () => {
      return await dispatch('getTennisCourtOpeningHourItemsToTennisCourtId', data.tennis_court_id);
    })
  },

  ///
  async deleteItem({ dispatch }, data) {
    await Api.forceDelete('tennis-court-opening-hour/' + data.id + '/force-delete').then(async () => {
      return await dispatch('getTennisCourtOpeningHourItemsToTennisCourtId', data.tennis_court_id);
    })
  },
}

// mutations
const mutations = {
  GET_ITEMS_TO_TENNIS_COURT_ID: (state, v) => {
    state.getTennisCourtOpeningHourItemsToTennisCourtId = v
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