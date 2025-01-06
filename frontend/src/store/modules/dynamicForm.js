import Api from "@/services/Api";

export default {
  namespaced: true,

  state: () => ({
    mySchema: 0,
    myProps: [],
    dynamicDataRegistration: {}
  }),
  getters:{
    myModel(state) {
      if (!state.myProps) {
        return false
      }
      return state.myProps.myModel
    },
    myUriBase(state) {
      if (!state.myProps.myUri) {
        return false
      }
      return (state.myProps.myUri.split('/'))[0];
    }
  },
  actions: {
    async update({ state }, query) {
      let uriToSubmit = (state.myProps.myUri.split('/'))[0] + '/' + (state.myProps.myUri.split('/'))[1];
      return await Api.update(uriToSubmit, query)
      /* .then(res => {
        commit('setItemDetail', res.data.data);
        commit('setItemIsLoading', false);
      }).catch(err => {
        console.log('error', err);
        commit('setItemIsLoading', false);
      }); */
    }

  },
  mutations: {
    SET_MY_SCHEMA(state, val) {
      state.mySchema = val;
    },
    SET_MY_PROPS(state, val) {
      state.myProps = val;
    },
    UPDATE_FORM_DATA(state, value) {
      console.log(value.data)
      state.dynamicDataRegistration = value.data
    }
  },
};