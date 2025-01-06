export default {
  namespaced: true,

  state: () => ({
    timeToCountDown: 0, //60 * 60 * 1000, // 60 min
  }),

  getters: {
    timeToCountDown(state){
      return state.timeToCountDown
    },
  },

  //actions: {},

  mutations: {
    
    /**
     * esta sendo usado em API/funcD1D2 para set value
     */
    SET_TIME_TO_COUNT_DOWN(state, val) {
      state.timeToCountDown = val;
    },
    
  },
};