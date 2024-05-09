<template>
    <div class="modal" v-if="visible">
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>
            <h2>Add Event</h2>
            <form @submit.prevent="submitEvent">
                <!-- Backend error message display -->
                <div v-if="backendErrorMessage" class="backend-error">
                    {{ backendErrorMessage }}
                </div>
                <div>
                    <label for="start-date">Start Date:</label>
                    <input
                        id="start-date"
                        type="date"
                        v-model="startDate"
                        required
                    />
                    <span class="error" v-if="formErrors.startDate">{{ formErrors.startDate }}</span>
                </div>
                <div>
                    <label for="end-date">End Date:</label>
                    <input
                        id="end-date"
                        type="date"
                        v-model="endDate"
                        required
                    />
                    <span class="error" v-if="formErrors.endDate">{{ formErrors.endDate }}</span>
                </div>
                <div>
                    <label for="event-type">Event Type:</label>
                    <select id="event-type" v-model="eventType" required>
                        <option disabled value="">Please select one</option>
                        <option
                            v-for="absence in absenceTypes"
                            :key="absence.id"
                            :value="absence.id"
                        >
                            {{ absence.title }}
                        </option>
                    </select>
                    <span class="error" v-if="formErrors.eventType">{{ formErrors.eventType }}</span>
                </div>
                <button type="submit" class="add-button">Add Event</button>
            </form>
        </div>
    </div>
</template>

<script>
import { getAllAbsenceTypes } from "../Services/absenceService";
import { createNewEvent } from "../Services/calendarService";

export default {
    props: {
        visible: Boolean,
    },
    data() {
        return {
            startDate: "",
            endDate: "",
            eventType: "",
            absenceTypes: [],
            formErrors: {},
            backendErrorMessage: ""
        };
    },
    methods: {
        closeModal() {
            this.$emit("close-modal", false);
        },
        validateForm() {
            this.formErrors = {}; // Reset previous errors
            if (!this.startDate) {
                this.formErrors.startDate = "Start date is required.";
            }
            if (!this.endDate) {
                this.formErrors.endDate = "End date is required.";
            }
            if (!this.eventType) {
                this.formErrors.eventType = "Event type is required.";
            }
            return Object.keys(this.formErrors).length === 0;
        },
        submitEvent() {
            if (this.validateForm()) {
                let payload = {
                    type_of_absence_id: this.eventType,
                    start_date: this.startDate,
                    end_date: this.endDate,
                };

                createNewEvent(payload)
                    .then((response) => {
                        this.$emit("add-event");
                        this.closeModal();
                    })
                    .catch((error) => {
                        this.backendErrorMessage = error.response.data.message || "Error creating event.";
                        console.error("Error creating absence:", error);
                    });
            }
        },
        fetchAbsences() {
            getAllAbsenceTypes()
                .then((absencesResponce) => {
                    this.absenceTypes = absencesResponce.data;
                })
                .catch((error) => {
                    console.error("Error fetching absences:", error);
                });
        },
    },
    mounted() {
        this.fetchAbsences();
    },
};
</script>

<style scoped>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    width: 300px;
}

.add-button {
    background-color: #3498db;
    color: #fff;
    border: 0px;
    outline: 0px;
    padding: 5px;
    border-radius: 3px;
    width: 100%;
    text-align: center;
}

.close {
    float: right;
    cursor: pointer;
}

form div {
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
}

.error {
    color: #e74c3c;
    font-size: 0.8em;
    display: block;
}

.backend-error {
    color: #e74c3c; 
    font-size: 0.9em; 
    margin-bottom: 10px; 
    text-align: center; 
}
</style>
