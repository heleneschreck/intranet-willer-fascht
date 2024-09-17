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
        height: 600,
        
        editable: true,
        selectable: true,
        dateClick: this.handleDateClick,
        eventColor: '#378006',
        eventClick: this.handleEventClick,
        selectMirror: true, 
        nowIndicator: true,
       
        select: (arg) => {
         
          this.$router.push({ name: "addEvent" });
        },
   
      },
    };
  },
  
  async created() {
    let fetched_evenements = await fetch(
      "http://127.0.0.1:8000/api/rendezvous"
      );
      this.evenements = await fetched_evenements.json();
      
      // console.table(this.evenements);
      this.calendarOptions.events = this.evenements;
      this.calendarOptions.editable = true; 
  
    
  },

  methods: {
getBackgroundColor(evenement) {
      return evenement.backgroundcolor;
    },
   
    
    
    handleEventClick(info) {
      
      const eventId = info.event.id; // ou tout autre identifiant unique de l'évènement
      
      this.$router.push({ name: "rendezvous", params: { evenement: eventId } });
    },
    async editable(event) {
      console.log('coucou');
    // Assuming the event object has an 'id' property for identification
    const eventId = event.id;
    console.log(eventId);
    // Modify the event data as needed
    event.title = "Updated Title";
    event.start = "2023-07-30"; // Change to the new start date

    try {
      // Make a PUT request to your API to update the event
      await fetch(`http://127.0.0.1:8000/api/rendezvous/${eventId}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(event),
      });

      // Event successfully updated, you may want to display a message or perform any additional actions
      console.log("Event updated successfully!");
    } catch (error) {
      console.error("Error updating event:", error);
      // Handle the error appropriately (e.g., show an error message to the user)
    }
  },
  },
};
</script>
<template>

  <div class="action">
 
    <router-link :to="`/liste`">
      <button
        href="#"
        class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700 retourliste"
     >
        Liste
      </button></router-link
    >
  </div>


    <FullCalendar :options="calendarOptions" />
  
</template>

<style scoped >
.Planning {

  margin: auto;
  


}
.retourliste{
  margin-left: 10px;
  /* margin-bottom: 35px !important; */
  border-radius: 20px;
  width: 200px;
  border: 5px solid rgb(70, 137, 226);
  background-color: rgb(70, 137, 226);
  color: white ;
  font-weight: bold;
  font-size: 20px;
  height: 70px !important;

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