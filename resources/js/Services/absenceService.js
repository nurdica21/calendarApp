import axios from "axios";

const API_URL = "http://127.0.0.1:8000/api/absences";

export const getAllAbsenceTypes = () => {
    return axios
        .get(API_URL)
        .then((response) => response.data)
        .catch((error) => console.error("Error fetching posts:", error));
};
