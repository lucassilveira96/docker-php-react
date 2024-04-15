import React, { useState, useEffect } from 'react';
import { createProductType } from '../../services/productTypeService';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { useNavigate } from 'react-router-dom';

const AddProductType = () => {
  const [productType, setProductType] = useState({
    description: '',
    tax: 0,
  });

  const navigate = useNavigate();

  useEffect(() => {
  }, []);

  const handleChange = e => {
    const { name, value } = e.target;

    let updatedValue = value;

    setProductType({ ...productType, [name]: updatedValue });
  };

  const handleSubmit = e => {
    e.preventDefault();
    if (productType.tax <= 0 ) {
      toast.warning('Por favor, preencha todos os campos com valores maiores que zero.');
      return;
    }

    createProductType(productType)
      .then(() => {
        toast.success('Tipo de Produto adicionado com sucesso!');
        navigate('/product-types');
      })
      .catch(error => {
        toast.error('Error adding productType:', error);
      });
  };

  return (
    <div className="container mt-5">
      <h2>Cadastro de Produtos</h2>
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

export default AddProductType;
