import React, { useState, useEffect } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTrash } from '@fortawesome/free-solid-svg-icons';
import { fetchProducts } from '../../services/productService';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css'
import { createSale } from '../../services/saleService';

const AddSale = () => {
  const [cart, setCart] = useState([]);
  const [quantity, setQuantity] = useState(1);
  const [barcode, setBarcode] = useState('');
  const [valueReceived, setValueReceived] = useState('');
  const [change, setChange] = useState(0);
  const [productSale, setProductSale] = useState([]);

  useEffect(() => {
    fetchProducts()
      .then(response => {
        setProductSale(response.data.data);
      })
      .catch(error => console.error('Error fetching products:', error));
  }, []);

  const handleAddToCart = (product) => {
    const itemIndex = cart.findIndex(item => item.product_id === product.id);
    if (itemIndex !== -1) {
      const updatedCart = [...cart];
      updatedCart[itemIndex].quantity += 1;
      setCart(updatedCart);
    } else {
      const item = {
        quantity: parseInt(quantity),
        product_id: product.id
      };
      setCart([...cart, item]);
    }
    setQuantity(1);
  };

  const clearScreen = () => {
    setCart([]);
    setQuantity(1);
    setBarcode('');
    setValueReceived('');
    setChange(0);
  };

  const handleRemoveFromCart = (itemId) => {
    const updatedCart = cart.map(item => {
      if (item.product_id === itemId) {
        return {
          ...item,
          quantity: item.quantity - 1
        };
      }
      return item;
    }).filter(item => item.quantity > 0);
    setCart(updatedCart);
  };

  const handleKeyPress = (e) => {
  if (e.key === 'Enter') {
    const product = productSale.find(product => product.ean === barcode);
    if (product) {
      const itemIndex = cart.findIndex(item => item.product_id === product.id);
      if (itemIndex !== -1) {
        const updatedCart = [...cart];
        updatedCart[itemIndex].quantity += parseInt(quantity); // Usando o valor de quantity
        setCart(updatedCart);
      } else {
        const item = {
          quantity: parseInt(quantity), // Usando o valor de quantity
          product_id: product.id
        };
        setCart([...cart, item]);
      }
      setBarcode('');
      setQuantity(1);
    }
  }
};


  const getTotalPrice = () => {
    return cart.reduce((total, item) => {
      const product = productSale.find(p => p.id === item.product_id);
      return total + product.price * item.quantity;
    }, 0);
  };

  const getTotalTax = () => {
    return cart.reduce((total, item) => {
      const product = productSale.find(p => p.id === item.product_id);
      return total + product.price * item.quantity * product.product_type.tax / 100;
    }, 0);
  };

  const handleFinalizeSale = () => {
    const totalPrice = getTotalPrice();
    const received = parseFloat(valueReceived);
    if (received >= totalPrice) {
      const changeValue = received - totalPrice;
      setChange(changeValue.toFixed(2));
      const productsObject = { products: cart };
      createSale(productsObject) 
      .then(() => {
        toast.success("Venda Salva com sucesso");
        clearScreen();
      })
      .catch(error => console.error('Error fetching products:', error));
    } else {
      console.log(productSale)
      toast.warning("Valor insuficiente")
    }
  };

  const handleValueReceivedChange = (e) => {
    setValueReceived(e.target.value);
  };

  const handleQuantityChange = (value) => {
    if (quantity > 0) {
      setQuantity(quantity + value);
    } else if (value > 0) {
      setQuantity(quantity + value);
    }
  };

  return (
    <div className="container">
      <div className="row">
        <div className="col-md-4 border p-3 rounded">
          <h3>Produtos</h3>
          <input
            type="text"
            className="form-control mb-3"
            placeholder="Scan barcode..."
            value={barcode}
            onChange={(e) => setBarcode(e.target.value)}
            onKeyPress={handleKeyPress}
          />
          <div className="mb-3">
            <label htmlFor="quantity" className="form-label">Quantidade:</label>
            <div className="input-group">
              <button className="btn btn-outline-secondary" type="button" onClick={() => handleQuantityChange(-1)}>-</button>
              <input
                type="number"
                className="form-control"
                id="quantity"
                min="1"
                value={quantity}
                onChange={(e) => setQuantity(e.target.value)}
              />
              <button className="btn btn-outline-secondary" type="button" onClick={() => handleQuantityChange(1)}>+</button>
            </div>
          </div>
        </div>
        <div className='col-md-8 border p-3 rounded'>
          <h3>Carrinho</h3>
          <table className="table table-striped table-hover">
            <thead className="thead-dark">
              <tr>
                <th scope="col">Produto</th>
                <th scope="col">Preço</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Impostos (%)</th>
                <th scope="col">Total Impostos</th>
                <th scope="col">Total</th>
                <th scope="col">Ação</th>
              </tr>
            </thead>
            <tbody>
              {cart.length > 0 ? (
                cart.map(item => {
                  const product = productSale.find(p => p.id === item.product_id);
                  return (
                    <tr key={item.product_id}>
                      <td>{product.description}</td>
                      <td>R$ {product.price.toFixed(2)}</td>
                      <td>{item.quantity}</td>
                      <td>{product.product_type.tax}%</td>
                      <td>R$ {(product.price * item.quantity * product.product_type.tax / 100).toFixed(2)}</td>
                      <td>{(product.price * item.quantity).toFixed(2)}</td>
                      <td>
                        <button className="btn btn-danger btn-sm" onClick={() => handleRemoveFromCart(item.product_id)}>
                          <FontAwesomeIcon icon={faTrash} />
                        </button>
                      </td>
                    </tr>
                  );
                })
              ) : (
                <tr>
                  <td colSpan="7">Nenhum produto no carrinho.</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
        <div className='col-md-4 border p-3 rounded'>
          <h3>Totais</h3>
          <div className="row">
            <div className="col-6 mb-2">
              <label htmlFor="totalPrice" className="form-label">Valor Total:</label>
              <input
                type="text"
                className="form-control"
                id="totalPrice"
                value={`R$ ${getTotalPrice().toFixed(2)}`}
                disabled
              />
            </div>
            <div className="col-6 mb-2">
              <label htmlFor="totalTax" className="form-label">Impostos:</label>
              <input
                type="text"
                className="form-control"
                id="totalTax"
                value={`R$ ${getTotalTax().toFixed(2)}`}
                disabled
              />
            </div>
          </div>
          <div className="mb-3">
            <label htmlFor="valueReceived" className="form-label">Valor Recebido:</label>
            <input
              type="text"
              className="form-control me-2"
              id="valueReceived"
              value={valueReceived}
              onChange={handleValueReceivedChange}
              disabled={getTotalPrice() <= 0}
            />
            <label htmlFor="change" className="form-label">Troco:</label>
            <input
              type="text"
              className="form-control mb-2"
              id="change"
              value={change}
              disabled
            />
          </div>
          <div className="d-grid">
            <button className="btn btn-success" onClick={handleFinalizeSale} disabled={getTotalPrice() <= 0}>Finalizar Venda</button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AddSale;
