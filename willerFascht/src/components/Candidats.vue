<script>
import { mapState } from "vuex";

export default {
  data() {
    return {
      mode: false,
      user: [],
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async beforeMount() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    if (localStorage.getItem("user") != null) {
      this.mode = true;
    }
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    
  },

 
};
</script>
<template>
  <h1>Candidats</h1>
</template>
