import Api from "@/services/Api"

// initial state
const state = () => ({
  getMediaForTennisCourtId: null,
})

// getters
const getters = {
  getMediaForTennisCourtId: state => state.getMediaForTennisCourtId,
}

// actions
const actions = {

  ///
  async getMediaForTennisCourtId({ commit }, tennisCourtId) {
    await Api.get('tennis-court-media/list-images-to-item/' + tennisCourtId).then((r) => {

      if (r?.data?.success) {
        delete r.data.success;
        delete r.data.listVars;
        delete r.data.campos;
      }
      return commit('GET_MEDIA_FOR_TENNIS_COURT_ID', r);
    })
  },

  ///
  async updateItem({ dispatch }, data) {
    await Api.update('tennis-court-media/' + data.id, data).then(async () => {
      return await dispatch('getMediaForTennisCourtId', data.tennis_court_id);
    })
  },

  ///
  async deleteItem({ dispatch }, data) {
    await Api.delete('tennis-court-media/' + data.id).then(async () => {
      return await dispatch('getMediaForTennisCourtId', data.tennis_court_id);
    })
  },
}

// mutations
const mutations = {
  GET_MEDIA_FOR_TENNIS_COURT_ID: (state, v) => {
    state.getMediaForTennisCourtId = v
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