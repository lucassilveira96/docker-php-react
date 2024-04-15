import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { getProductTypeById, updateProductType } from '../../services/productTypeService';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const EditProductType = () => {
    const { id } = useParams(); // Captura o ID do produto da URL
    const [productType, setProduct] = useState({
        description: '',
        tax: 0,
    });

    const navigate = useNavigate();

    useEffect(() => {
        getProductTypeById(id)
            .then(response => {
                if (response.data.data === null) {
                    navigate('/product-types');
                }
                setProduct(response.data.data);
            })
            .catch(error => {
                console.error('Erro ao buscar os detalhes do produto:', error);
            });
    }, [id]);

    const handleChange = e => {
        const { name, value } = e.target;

        let updatedValue = value;

        setProduct({ ...productType, [name]: updatedValue });
    };


    // Função para lidar com o envio do formulário de edição
    const handleSubmit = e => {
        e.preventDefault();
        if (productType.tax <= 0) {
            toast.warning('Por favor, preencha todos os campos com valores maiores que zero.');
            return;
        }
        updateProductType(id, productType)
            .then(() => {
                toast.success('Tipo de Produto atualizado com sucesso!');
                navigate('/product-types');
            })
            .catch(error => {
                toast.error('Erro ao atualizar o produto:', error);
            });
    };

    return (
        <div className="container mt-5">
            <h2>Edição do Tipo Produto</h2>
            <form onSubmit={handleSubmit}>
                <div className="row">
                    <div className="col-8">
                        <div className="form-group">
                            <label htmlFor="description">Descrição:</label>
                            <input
                                type="text"
                                id="description"
                                className="form-control"
                                name="description"
                                value={productType.description}
                                onChange={handleChange}
                                required
                            />
                        </div>
                    </div>
                    <div className="col-4">
                        <div className="form-group">
                            <label htmlFor="tax">Impostos:</label>
                            <input
                                type="number"
                                id="tax"
                                className="form-control"
                                name="tax"
                                value={productType.tax}
                                onChange={handleChange}
                                required
                            />
                        </div>
                    </div>
                </div>
                <button type="submit" className="btn btn-primary">Salvar</button>
            </form>
        </div>
    );
};

export default EditProductType;
