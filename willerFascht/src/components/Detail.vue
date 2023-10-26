<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
import moment from "moment";
import { VueDraggableNext } from "vue-draggable-next";
const route = useRoute();

export default {
  data() {
    return {
      enabled: true,
      dragging: false,
      mode: false,
      route: route,
      user: [],
      projects: [],
      statuts: [],
      taches: [],
      mode: "nodisplay",
      tache: "",
      compterendus: [],
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.moment = moment;

    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    // if (localStorage.getItem("user") != null) {
    //   this.mode = true;
    // }
    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);

    let fetched_compterendus = await fetch(
      "http://127.0.0.1:8000/api/compterendu"
    );
    this.compterendus = await fetched_compterendus.json();
    console.table(this.compterendus);

    // Vue.filter("formatDate", function (taches) {
    //   const dateObj = new Date(taches.end);
    //   const formattedDate =`${dateObj.getDate()}/${dateObj.getMonth() + 1}/${dateObj.getFullYear()}`;
    //   return formattedDate;
    // });
  },
};
</script>
<template>
     <router-link :to="`/compterendu`">
    <button class="button rounded-lg retourcompterendu">Retour</button>
  </router-link>
  <div v-for="compterendu in compterendus">
    <div v-if="$route.params.compterendu == compterendu.id">
      <h1>Réunion du {{ moment(compterendu.date).format("DD/MM/YYYY") }}</h1>
      <div class="pointstraite">
        <div class="titrepointtraite">Points traités :</div>
        <div class="contenupointtraite">
          <li class="list">{{ compterendu.contenus }}</li>
        </div>
      </div>

      <br />
      <div class="participantsreunions">
        <div class="membresreunion">Membres présents à la réunion :</div>
<br>
        <div class="contenusmembresreunion">
          <li class="list">{{ compterendu.participants }}</li>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.detail {
  /* margin-top: 8% !important; */
  margin-left: 3%;
  width: 95%;
}
.pointstraite {
  margin-top: 25px;
}
.contenupointtraite {
  margin-top: -36px;
}
.contenusmembresreunion{
    margin-top: -61px;

}
</style>
