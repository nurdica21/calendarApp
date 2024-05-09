import axios from "axios";

const API_URL = "http://127.0.0.1:8000/api/calendar";

export const getCurrentMonth = () => {
    return axios
        .get(API_URL)
        .then((response) => response.data)
        .catch((error) => console.error("Error fetching posts:", error));
};

export const createNewEvent = (payload) => {
    return axios
        .post(API_URL, payload)
        .then((response) => {
            return response;
        })
        .catch((error) => {
            console.error("Error:", error);
            throw error;
        });
};
