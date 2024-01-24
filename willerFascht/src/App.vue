<script>
import { mapState } from "vuex";
import { RouterLink, RouterView } from "vue-router";



export default {
  name: "app",
  components: {

  },
  data() {
    return {
      mode: false,
      user: [],
      currentPage: "", // Gardera le nom de la page actuelle
      showMenu: false,
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  watch: {
    "$route.name"(newRouteName) {
      this.currentPage = newRouteName;
      console.log(this.currentPage)
    },
  },
  async beforeMount() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.log(this.membres);


  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
  },
  components: {
    // planning

  },
};
</script>

<template>
 

  <RouterView />
</template>

<style scoped>
header {
  line-height: 1.5;
  max-height: 100vh;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

nav {
  padding-bottom: -1rem;
}
.flex {
  display: flex;
  flex-wrap: nowrap !important;
}

.bg-white {
  text-align: left;
  margin-left: 1rem;
  font-size: 1rem;
  padding: 0rem 0;
  /* margin-top: -1rem; */
  padding-right: 63px;
}

nav a:first-of-type {
  border: 0;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    margin-left: -1rem;
    font-size: 1rem;
    padding: 1rem 0;
    margin-top: 1rem;
  }
 
}
</style>
