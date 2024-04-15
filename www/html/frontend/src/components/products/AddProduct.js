import React, { useState, useEffect } from 'react';
import { fetchProductTypes } from '../../services/productTypeService';
import { createProduct } from '../../services/productService';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { useNavigate } from 'react-router-dom';

const AddProduct = () => {
  const [product, setProduct] = useState({
    description: '',
    price: 0,
    ean: '',
    purchase_price: 0,
    sales_margin: 0,
    quantity: 0,
    minimum_quantity: 0,
    product_type: {
      id: 0
    }
  });

  const navigate = useNavigate();

  const [productTypes, setProductTypes] = useState([]);

  useEffect(() => {
    fetchProductTypes()
      .then(response => {
        setProductTypes(response.data.data);
      })
      .catch(error => {
        toast.error('Erro ao capturar os tipos de produtos:', error);
      });
  }, []);

  const handleChange = e => {
    const { name, value } = e.target;

    let updatedValue = value;

    if (name === 'ean') {
      updatedValue = value.toUpperCase();
    } else if (name === 'purchase_price' || name === 'price') {
      updatedValue = parseFloat(value);
      const updatedProduct = { ...product, [name]: updatedValue };
      const purchasePrice = parseFloat(updatedProduct.purchase_price);
      const salesPrice = parseFloat(updatedProduct.price);

      if (!isNaN(purchasePrice) && !isNaN(salesPrice) && purchasePrice > 0 && salesPrice !== purchasePrice) {
        updatedProduct.sales_margin = (((salesPrice - purchasePrice) / purchasePrice) * 100).toFixed(2);
      } else {
        updatedProduct.sales_margin = 0;
      }
      setProduct(updatedProduct);
      return;
    } else if (name === 'sales_margin') {
      updatedValue = parseFloat(value);
      const salesMargin = parseFloat(value);
      const purchasePrice = parseFloat(product.purchase_price);

      if (!isNaN(salesMargin) && !isNaN(purchasePrice)) {
        product.price = (purchasePrice * (1 + salesMargin / 100)).toFixed(2);
      }
    } else if (name === 'product_type') {
      setProduct(prevProduct => ({
        ...prevProduct,
        product_type: {
          ...prevProduct.product_type,
          id: parseInt(value)
        }
      }));
      return;
    }

    setProduct({ ...product, [name]: updatedValue });
  };

  const handleSubmit = e => {
    e.preventDefault();
    if (product.purchase_price <= 0 || product.price <= 0 || product.sales_margin <= 0 || product.quantity <= 0) {
      toast.warning('Por favor, preencha todos os campos com valores maiores que zero.');
      return;
    }

    createProduct(product)
      .then(() => {
        toast.success('Produto adicionado com sucesso!');
        navigate('/products');
      })
      .catch(error => {
        toast.error('Error adding product:', error);
      });
  };

  return (
    <div className="container mt-5">
      <h2>Cadastro de Produtos</h2>
      <form onSubmit={handleSubmit}>
        <div className="row">
          <div className="col">
            <div className="form-group">
              <label htmlFor="description">Descrição:</label>
              <input
                type="text"
                id="description"
                className="form-control"
                name="description"
                value={product.description}
                onChange={handleChange}
                required
              />
            </div>
          </div>
        </div>
        <div className="row">
          <div className="col">
            <div className="form-group">
              <label htmlFor="productType">Tipo do Produto:</label>
              <select
                id="productType"
                className="form-control"
                name="product_type"
                value={product.product_type.id}
                onChange={handleChange}
                required
              >
                <option value="">selecione um tipo de produto</option>
                {productTypes.map(type => (
                  <option key={type.id} value={type.id}>
                    {type.description}
                  </option>
                ))}
              </select>
            </div>
          </div>
          <div className="col">
            <div className="form-group">
              <label htmlFor="ean">Código de Barras:</label>
              <input
                type="text"
                id="ean"
                className="form-control"
                name="ean"
                value={product.ean}
                onChange={handleChange}
                maxLength={13}
                required
              />
            </div>
          </div>
        </div>
        <div className="row">
          <div className="col">
            <div className="form-group">
              <label htmlFor="purchase_price">Preço de Compra:</label>
              <input
                type="number"
                id="purchase_price"
                className="form-control"
                name="purchase_price"
                value={product.purchase_price}
                onChange={handleChange}
                required
              />
            </div>
          </div>
          <div className="col">
            <div className="form-group">
              <label htmlFor="price">Preço de Venda:</label>
              <input
                type="number"
                id="price"
                className="form-control"
                name="price"
                value={product.price}
                onChange={handleChange}
                required
              />
            </div>
          </div>
          <div className="col">
            <div className="form-group">
              <label htmlFor="sales_margin">Margem de Venda:</label>
              <input
                type="number"
                id="sales_margin"
                className="form-control"
                name="sales_margin"
                value={product.sales_margin}
                onChange={handleChange}
                required
              />
            </div>
          </div>
        </div>
        <div className="row">
          <div className="col">
            <div className="form-group">
              <label htmlFor="quantity">Quantidade:</label>
              <input
                type="number"
                id="quantity"
                className="form-control"
                name="quantity"
                value={product.quantity}
                onChange={handleChange}
                required
              />
            </div>
          </div>
          <div className="col">
            <div className="form-group">
              <label htmlFor="minimum_quantity">Quantidade Mínima:</label>
              <input
                type="number"
                id="minimum_quantity"
                className="form-control"
                name="minimum_quantity"
                value={product.minimum_quantity}
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

export default AddProduct;
