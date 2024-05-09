<template>
    <div class="container">
        <button @click="showModal = true" class="add-button">Add Event</button>
    </div>
    <event-modal
        :visible.sync="showModal"
        @close-modal="showModal = false"
        @add-event="handleAddEvent"
    ></event-modal>

    <div class="calendar-container">
        <div class="calendar">
            <div class="week" v-for="(week, index) in weeks" :key="index">
                <div
                    class="day"
                    v-for="day in week"
                    :key="day.date"
                    :class="{
                        weekend: day.short == 'Sun' || day.short == 'Sat',
                    }"
                >
                    <div class="date">
                        {{ day.short + "-" + day.date.substring(0, 2) }}
                    </div>
                    <div
                        v-for="absence in day.absences"
                        :key="absence.id"
                        class="event"
                    >
                        {{ absence.reason.title }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { createNewEvent, getCurrentMonth } from "../Services/calendarService";
import EventModal from "./EventModal.vue";
import { useToast } from "vue-toastification";

export default {
    data() {
        const toast = useToast();
        return {
            showModal: false,
            days: [],
            weeks: [],
            toast: toast,
        };
    },
    methods: {
        organizeWeeks(days) {
            let week = [];
            days.forEach((day, index) => {
                week.push(day);
                if ((index + 1) % 7 === 0 || index === days.length - 1) {
                    this.weeks.push(week);
                    week = [];
                }
            });
        },
        handleAddEvent() {
            this.weeks = [];
            this.populateCalendar();
            this.toast.success("Successfully updated your calendar !", {
                timeout: 2000
            });
        },
        populateCalendar() {
            getCurrentMonth()
                .then((daysResponse) => {
                    this.days = daysResponse;
                    this.organizeWeeks(this.days);
                })
                .catch((error) => {
                    console.error("Error fetching current month:", error);
                });
        },
    },
    mounted() {
        this.populateCalendar();
    },
};
</script>

<style>
.container {
    display: flex;
    max-width: 980px;
    justify-content: end;
    position: absolute;
    left: 50%;
    transform: translate(-50%, 0%);
    top: 10px;
}

.add-button {
    background-color: #3498db;
    color: #fff;
    border: 0px;
    outline: 0px;
    padding: 5px;
    border-radius: 3px;
}

.calendar-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.calendar {
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 980px;
}

.week {
    display: flex;
    justify-content: start;
}

.day {
    width: calc(100% / 7);
    border: 1px solid #ccc;
    padding: 10px;
    margin: 2px;
    box-sizing: border-box;
    text-align: center;
    max-width: 136px;
    height: 100px;
}

.event {
    background-color: #f0f0f0;
    margin-top: 5px;
    padding: 2px;
}

.date {
    border-bottom: 1px solid #ccc;
}

.weekend {
    background-color: rgb(236, 236, 236);
}
</style>
