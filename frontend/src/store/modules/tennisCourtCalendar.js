import Api from "@/services/Api";
import MyAlert from "@/services/MyAlert";

// initial state
const state = () => ({
  tennisCourtId: null,
  itemsForTennisCourtId: null,
  numberOfHoursPerDay: null,
  tennisCourtCalendarForTennisCourtId: null,
  bgColerHourUnavailable:'#ff3236',
})

// getters
const getters = {
  tennisCourtId: state => state.tennisCourtId,
  itemsForTennisCourtId: state => state.tennisCourtId?.id,
  tennisCourtCalendarForTennisCourtId: state => state.tennisCourtCalendarForTennisCourtId,
  numberOfHoursPerDay: state => state.numberOfHoursPerDay,
  bgColerHourUnavailable:state => state.bgColerHourUnavailable,
}

// actions
const actions = {

  ///
  async getItemsForTennisCourtId({ commit }, tennisCourtId) {
    await Api.get('tennis-court-calendar/list-items-to-tennis-court-id/' + tennisCourtId).then((r) => {
      return commit('SET_ITEMS_FOR_TENNIS_COURT_ID', r);
    })
  },

  async numberOfHoursPerDay({ commit }, tennisCourtId) {
    await Api.get('tennis-court-opening-hour/number-of-hours-per-day/' + tennisCourtId).then((r) => {
      return commit('SET_NUMBER_OF_HOURS_PER_DAY', r);
    })
  },

  async deleteItemOfCartConfirm(data, title = 'Cofirme', text = 'Excluir o item do seu carrinho?') {
    console.log('deleteItemOfCartConfirm', data)
    return await MyAlert.alertConfirm(title, text)
        .then((result) => {
            if (result) {

              //return dispatch('getItemsForTennisCourtId', getters.itemsForTennisCourtId);
            }
            return false;
        })
},
}

// mutations
const mutations = {

  ///
  SET_TENNIS_COURT_ID: (state, tennisCourtId) => {
    state.tennisCourtId = tennisCourtId
  },

  ///
  SET_ITEMS_FOR_TENNIS_COURT_ID: (state, items) => {
    state.itemsForTennisCourtId = items
    state.tennisCourtCalendarForTennisCourtId = items
  },

  ///
  SET_NUMBER_OF_HOURS_PER_DAY: (state, items) => {
    state.numberOfHoursPerDay = items
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