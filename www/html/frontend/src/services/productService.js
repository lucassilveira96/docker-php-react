import axios from 'axios';

const API_URL = 'http://localhost:8080/products';

export const fetchProducts = () => {
    return axios.get(API_URL);
};

export const getProductById = (id) => {
    return axios.get(`${API_URL}/${id}`);
};


export const createProduct = productData => {
    return axios.post(API_URL, productData);
};

export const updateProduct = (id, productData) => {
    return axios.put(`${API_URL}/${id}`, productData);
};

export const deleteProduct = id => {
    
    return axios.delete(`${API_URL}/${id}`);
};
