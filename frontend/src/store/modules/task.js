import Api from "@/services/Api"

// initial state
const state = () => ({
  getTasksToMe: null,
})

// getters
const getters = {
  getTasksToMe: state => state.getTasksToMe,
}

// actions
const actions = {

  ///
  async tasksItemsToMe({ commit }) {
    await Api.get('task/list-items-to-user-id').then( async (r) => {
      //console.log(r);      
      return await commit('GET_TASKS_TO_ME', r);
    })
  },

  ///
  async updateItem({ dispatch }, data) {
    await Api.update('task/' + data.id, data).then(async () => {
      return await dispatch('getTasksToMe', data.tennis_court_id);
    })
  },

  ///
  async deleteItem({ dispatch }, data) {
    await Api.delete('task/' + data.id).then(async () => {
      return await dispatch('getTasksToMe', data.tennis_court_id);
    })
  },
}

// mutations
const mutations = {
  GET_TASKS_TO_ME: (state, v) => {
    state.getTasksToMe = v
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