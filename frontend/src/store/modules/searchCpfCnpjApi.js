import BrasilApi from '@/services/BrasilApi';

const state = () => ({
  myState: null,
  myActions: null,
  myCnpjData:{},
  myCpfData:{},
})

const actions = {
  async cnpj({ commit }) {
    try {
      const response = await BrasilApi.cnpj(this.register.cpf);
      commit('SET_CNPJ', response.data);
      console.log(response.data);
    } catch (error) {
      console.error('Error action cnpj:', error);
    }
  },
}

const mutations = {
  SET_CNPJ(state, data) {
    state.myCnpjData = data;
  },
  SET_CPF(state, data) {
    state.myCpfData = data;
  },
}

const getters = {
  myState: state => state.myState,
  myActions: state => state.myActions,
  myCnpjData:state => state.myCnpjData,
  myCpfData:state => state.myCpfData,
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}

