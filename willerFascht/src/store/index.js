import { createStore } from "vuex";
import "es6-promise/auto";
import axios from "axios";



//connexion
const get = async function (url){
let response = await fetch(url, {
  credentials: 'same-origin',
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  }
  })
  debugger
}
const instance = axios.create({
  baseURL: "http://127.0.0.1:8000",
});
let user = localStorage.getItem('user');
if(!user) {
  user= {
      id: -1,
      token: "",
  };
}else {
  try {
  user = JSON.parse(user);
  instance.defaults.headers.common['Authorization'] = user.token;
} catch (ex) {
  user= {
    id: -1,
    token: "",
}; 
}
}
const store = createStore({
  state: {
    // appName: "Vue3 Vuex",
    conversations: {},
    status: "",
    user: user,
    userInfos: {
      name: "",
      prenom: "",
      telephone: "",
      email: "",
      password: "",
      niveau:""
    },
  },
  mutations: {
    setStatus: function (state, status) {
      state.status = status;
    },
    logUser: function (state, user) {
      instance.defaults.headers.common["Authorization"] = user.token;
      localStorage.setItem("user", JSON.stringify(user));
      state.user = user;
    },
    userInfos: function (state, userInfos) {
      state.userInfos = userInfos;
    },
    logout: function(state){
      state.user = {
        userId: -1,
        token:'',
      }
      localStorage.removeItem('user');
    }
  },
  actions: {
    loadConversation:async function(context){
await get('/api/conversations')
    },
    connect: ({ commit }, userInfos) => {
      commit("setStatus", "loading");
      return new Promise((resolve, reject) => {
        instance
          .post("/api/auth/login", userInfos)
          .then(function (response) {
            commit("setStatus", "");
            commit("logUser", response.data);
            resolve(response);
          })
          .catch(function (error) {
            commit("setStatus", "error_login");
            reject(error);
          });
      });
    },
    createAccount: ({ commit }, userInfos) => {
      commit("setStatus", "loading");
      return new Promise((resolve, reject) => {
        commit;
        instance
          .post("/api/auth/register", userInfos)
          .then(function (response) {
            commit("setStatus", "created");
            resolve(response);
          })
          .catch(function (error) {
            commit("setStatus", "error_create");
            reject(error);
          });
      });
    },
    getUserInfos: ({ commit }) => {
      instance
        .post("/api/auth/login")
        .then(function (response) {
          commit("userInfos", user);
          console.log(user);
        })
        .catch(function () {});
    },
  },
});

export default store;
