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

      user: [],
      projects: [],
      statuts: [],
      taches: [],
      membres: [],
      evenements: [],

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
          left: "prev,next,today",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay",
          
        },
        buttonText: {
          today: "Aujourd'hui",
          month: "Mois",
          week: "Semaine",
          day: "Jour",
       
        },
        editable: true,
        selectable: true,
        dateClick: this.handleDateClick,
        eventColor: '#378006',
        
        selectMirror: true, 
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
      this.calendarOptions.editable = true,
      console.log(this.calendarOptions.events);
      let backgroundcolor = this.calendarOptions.events.background
      backgroundcolor = this.evenements.backgroundcolor

      console.log(backgroundcolor);
  },

  methods: {
    handleDateClick: function (arg) {
    },
    editable:function(evenements){
      console.log(evenements);

    }
  },
};
</script>
<template>
  <!-- <h1>{{ evenements }} </h1> -->
  <div class="action">
    <router-link :to="`/addEvent`">
      <button
        href="#"
        class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
      >
        Ajouter un évènement
      </button></router-link
    >

    <router-link :to="`/liste`">
      <button
        href="#"
        class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
      style="padding: 30% !important;">
        Liste
      </button></router-link
    >
  </div>

  <FullCalendar :options="calendarOptions" />
</template>
<style>
.Planning {
  margin-top: 8% !important;
  width: 90%;
  margin: auto;

}
body {
  font-family:Arial, Helvetica, sans-serif!important;
}
.action {
  display: flex;
  justify-content: space-between;
  width: 20%;
  margin-bottom: 1%;
}
</style>
