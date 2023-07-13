<script>
import { mapState } from "vuex";
export default {

  data() {
    return {
      mode: false,
      member: Number,
      user: [],
      membres: [],
    }; 
    
  },   
mounted() {
this.$store.dispatch('loadConversations')
},
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    delete_membres: function (membre) {
      console.log(membre.id);

      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/users/" + membre.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
  },
};
</script>
<template>
 
<div class="row">
    <div class="col-nd-3">
        <div class="list-group">
            <a class="list-group-item d-flex justify-content-between align-items-center">
            {{user.prenom}} {{user.id}}
            <span class="badge badge-pill badge-primary">0</span>
            </a>
        </div>
    </div>
</div>
        
    
</template>
<style>
h1 {
  font-size: large;
}
nav {
  margin-bottom: 1%;
}
.row {
  margin-top: 7%;
  margin-left: 1.5%;
  margin-right: 1%;
}


</style>