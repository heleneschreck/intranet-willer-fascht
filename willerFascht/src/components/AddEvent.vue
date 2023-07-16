<script>
import { ref, reactive } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import DayGridPlugin from "@fullcalendar/daygrid";
import TimeGridPlugin from "@fullcalendar/timegrid";
import InteractionPlugin from "@fullcalendar/interaction";
import ListPlugin from "@fullcalendar/list";
import TimeZonePlugin from "@fullcalendar/moment-timezone";
import MomentPlugin from "@fullcalendar/moment";

const id = ref(10);
export default {
  components: {
    FullCalendar, // make the <FullCalendar> tag available
  },
  data() {
    return {
      mode: false,
      mode: "nodisplay",
      user: [],
      projects: [],
      statuts: [],
      taches: [],
      membres: [],
      evenements: [],
      result: [],
      response: [],
      //
      calendarOptions: {
        plugins: [
          MomentPlugin,
          DayGridPlugin,
          InteractionPlugin,
          ListPlugin,
          TimeGridPlugin,
          TimeZonePlugin,
        ],
        initialView: "dayGridMonth",
        locale: "fr",
        headerToolbar: {
          left: "prev next today",
          center: "title",
          right: "dayGridMonth timeGridWeek timeGridDay list",
        },
        buttonText: {
          today: "Aujourd'hui",
          month: "Mois",
          week: "Semaine",
          day: "Jour",
          list: "Liste",
        },
        editable: true,
        selectable: true,
        dateClick: this.handleDateClick,

        nowIndicator: true,
        select: (arg) => {
          id.value = id.value + 1;
          const cal = arg.view.calendar;
          cal.unselect();
          cal.addEvent({
            id: `${id.value}`,
            title: title,
            start: arg.start,
            end: arg.end,
            allDay: true,
          });
        },
        eventClick: (arg) => {
          console.log(arg.event.title);
        },
      },
    };
  },

  async created() {
    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
    );
    this.evenements = await fetched_evenements.json();

    console.table(this.evenements);
    this.calendarOptions.events = this.evenements;
  },

  methods: {
    addEvent() {
      var myHeaders = new Headers();
      myHeaders.append(
        "Cookie",
        "XSRF-TOKEN=eyJpdiI6IklsT1E2dWhEL3UzYXMzamRIbjRJNGc9PSIsInZhbHVlIjoiT3JYTCs1aVlUNzZKV29WRXc3OFBhaDNVaE5QOWEwVHhzcFlYQVdkUVF4cUNnOFEwbGR4ZHNheVlFTDNPc1hrcndwcS9lNk5ReFVJZE9obHBDRStZOG9JNGVzYlpkR3ZmMnppNFV0NWZaUEtsRGdyTU9NY2RBbFozZURBWFIrZ2giLCJtYWMiOiJkMzdkNjY3OWQ5MTRlOGRlOWUzZTJiMzcxNmQ0YWY4NTY0M2UwMDgzOWU3ODEwOWVhODYwZjMwODYzZTg5ZDQ2IiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6Imx6dFJNVW9zMUpBV1ZtY3ZoaUM5OWc9PSIsInZhbHVlIjoiT2lCWlBRRTJBVnU0WjdlK3JlRS9QaC8va2NwYy9SWTRGOEk4Y3YrYUJFOFNoYXk1aDBtWFUyL1g4R3VkWkdPK1kra2YrUERKeHlvbkhZWWJYVDdWckw5NmlCUlN2TFVpWFJUWENQSXpkMFV1MDhhV2hRSERndWtGQ1RENjVNblMiLCJtYWMiOiIyOTRmMGRhODJjOWU2OThhMGZlZTcxNWFmNDJhNWE1N2ZhMWM2OGRkZDY1MGFlMDdiNTBhNmM2ZTA4YmQwMWY2IiwidGFnIjoiIn0%3D"
      );

      var urlencoded = new URLSearchParams();
      urlencoded.append("start", this.start);
      urlencoded.append("end", this.end);
      urlencoded.append("title", this.title);
      urlencoded.append("description", this.description);
      urlencoded.append("backgroundcolor", this.backgroundcolor);
      var requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/rendezvous";
      fetch(url, requestOptions)
        .then((response) => {
          let test= response.json();
          console.log(test);
          return test;
        })
 
    .then((result) => console.log(result.id))
        .catch((error) => console.log("error", error));
        setTimeout(() => {
        this.$router.push({ path: "/liste"});
      }, "2000");
 
   
    },

  },
};
</script>
<template>

  <h1>Nouveau rendez-vous :</h1>
  <div class="créationevent">
    <div class="input">
      <label for="start" style="margin-top: 30px">Début:</label>
      <input
        style="margin-top: 30px"
        v-model="start"
        type="datetime-local"
        id="start"
        name="start"
      />
    </div>
    <div class="input">
      <label for="end">Fin:</label>
      <input v-model="end" type="datetime-local" id="end" name="end" />
    </div>
    <div class="input">
      <label for="title">Evenement:</label>
      <input v-model="title" class="input" type="text" />
    </div>
    <div class="input">
      <label for="description">Contenus:</label>
      <textarea v-model="description" class="input" type="text" />
    </div>
    <div class="input">
      <label for="backgroundcolor">Type de rendez-vous :</label>
      <select
        v-model="backgroundcolor"
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
        <button @click="addEvent()" class="button rounded-lg button-disabled">
          Nouveau rendez-vous
        </button>
      </div>
    </div>
  </div>
</template>
<style>
.input {
  display: flex;
  justify-content: space-evenly;
  margin-bottom: 9px;
  width: 52% !important;
  margin-left: 30%;
}
.addevent {
  margin-top: 8% !important;
  width: 90%;
  margin: auto;
  border: 1px solid;
  border-radius: 5px;
}
.créationevent {
  text-align: center;

  padding: 5px;
}

.createinscriptionmethod {
  margin-left: 0%;
  margin-top: -12px;
  margin-bottom: 10px;
  /* display: none; */
}
/* .add{
  margin-top: -86px;
} */
.input {
  display: flex;
  justify-content: space-evenly;
  margin-bottom: 9px;
  width: 50%;
  /* margin-left: 30%; */
}
</style>
