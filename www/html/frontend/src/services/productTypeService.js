import axios from 'axios';

const API_URL = 'http://localhost:8080/product-types';

export const fetchProductTypes = () => {
    return axios.get(API_URL);
};

export const getProductTypeById = (id) => {
    return axios.get(`${API_URL}/${id}`);
};

export const createProductType = productData => {
    return axios.post(API_URL, productData);
};

export const updateProductType = (id, productData) => {
    return axios.put(`${API_URL}/${id}`, productData);
};

export const deleteProductType = id => {
    
    return axios.delete(`${API_URL}/${id}`);
};
