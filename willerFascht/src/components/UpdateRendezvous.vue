<script>
import { mapState } from "vuex";
import { useRoute } from "vue-router";
const route = useRoute();

export default {
 
  data() {
    return {
      mode: false,

      user: [],
      projects: [],
      statuts: [],
      taches: [],
      membres: [],
      evenements: [],

      //
    };
  },

  async beforeMount() {
    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
    );
    this.evenements = await fetched_evenements.json();
    console.table(this.evenements);
  },

  methods: {
updateEvent(){
  var requestOptions = {
  method: 'PUT',
  redirect: 'follow'
};

fetch("http://127.0.0.1:8000/api/rendezvous/74?title=test modifié&description=est ce que ca fonctionne ?", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
}
  },
};
</script>
<template>
    <router-link :to="`/liste`">
    <button class="button rounded-lg">Retour</button>
  </router-link>
  <div v-for="evenement in evenements">
    <div v-if="evenement.id == $route.params.evenement">
      <h1>Modification du rendez-vous <br>
        "{{evenement.title}}" </h1>

        <div class="créationevent">
    <div class="input">
      <label for="start" style="margin-top: 30px">Début:</label>
      <input
        style="margin-top: 30px"
        v-model="evenement.start"
        
        type="datetime-local"
        id="start"
        name="start"
      />
    </div>
    <div class="input">
      <label for="end">Fin:</label>
      <input v-model="evenement.end" type="datetime-local" id="end" name="end" />
    </div>
    <div class="input">
      <label for="title">Evenement:</label>
      <input v-model="evenement.title" class="input" type="text" />
    </div>
    <div class="input">
      <label for="description">Contenus:</label>
      <textarea v-model="evenement.description" class="input" type="text" />
    </div>
    <div class="input">
      <label for="backgroundcolor">Type de rendez-vous :</label>
      <select
        v-model="evenement.backgroundcolor"
        name="backgroundcolor"
        id="backgroundcolor"
      >
        <option value="#F9E9BD">Réunion</option>
        <option value="#EB380D">Evenèment</option>
        <option value="#0DEBC5">Préparation</option>
        <option value="#6A0BAF">
          Action pour l'assofetchciation (quête, vente de ticket de tombola )
        </option>
        <option value="#F2BF0F">Halloween</option>
        <option value="#16AF0B">Carnaval</option>
      </select>
    </div>
    <br />
    <div>
   
      <div class="add">
        <button @click="updateEvent()" class="button rounded-lg button-disabled">
         Modification du rendez-vous
        </button>
      </div>
    </div>
  </div>
      </div>
      </div>


     
    
</template>
<style>
.updaterendezvous {
  margin-top: 8% !important;
  width: 90%;
  margin: auto;
}

</style>