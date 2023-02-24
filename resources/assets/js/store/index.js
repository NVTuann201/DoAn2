import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    language: null,
    keySearch: null,
    valueSelect: null,
    keyType: null
  },

  getters: {},

  plugins: [createPersistedState()],

  mutations: {
    setLanguage(state, payload) {
      state.language = payload.amount;
    },
    setKeySearch(state, payload) {
      state.keySearch = payload.amount;
    },
    setValueSelect(state, payload) {
      state.valueSelect = payload.amount;
      state.keyType = payload.amount1
    },
  },

  actions: {},

  modules: {}
});

export default store;
