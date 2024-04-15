import axios from 'axios';

const API_URL = 'http://localhost:8080/sales';

export const fetchSales = () => {
    return axios.get(API_URL);
};

export const getSaleById = (id) => {
    return axios.get(`${API_URL}/${id}`);
};

export const createSale = saleData => {
    return axios.post(API_URL, saleData);
};

export const updateSales = (id, saleData) => {
    return axios.put(`${API_URL}/${id}`, saleData);
};

export const deleteSale = id => {
    return axios.delete(`${API_URL}/${id}`);
};
