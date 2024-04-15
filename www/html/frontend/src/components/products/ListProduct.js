import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPencilAlt, faTrash } from '@fortawesome/free-solid-svg-icons';
import ReactPaginate from 'react-paginate';
import './css/ListProduct.css';
import { deleteProduct, fetchProducts } from '../../services/productService';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const ListProduct = () => {
  const [products, setProducts] = useState([]);
  const [search, setSearch] = useState('');
  const [currentPage, setCurrentPage] = useState(0);
  const productsPerPage = 5;
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [productToDelete, setProductToDelete] = useState(null);
  const [filteredProducts, setFilteredProducts] = useState([]);

  useEffect(() => {
    fetchProducts()
      .then(response => {
        setProducts(response.data.data);
        setFilteredProducts(response.data.data);
      })
      .catch(error => console.error('Error fetching products:', error));
  }, []);

  useEffect(() => {
    const lowercasedValue = search.toLowerCase();
    const filtered = products.filter(product =>
      product.description.toLowerCase().includes(lowercasedValue)
    );
    setFilteredProducts(filtered);
    setCurrentPage(0);
  }, [search, products]);

  const handleSearchChange = (e) => {
    setSearch(e.target.value);
  };

  const handlePageClick = (data) => {
    setCurrentPage(data.selected);
  };

  const handleDeleteProduct = (product) => {
    setProductToDelete(product);
    setShowDeleteModal(true);
  };

  const closeDeleteModal = () => {
    setShowDeleteModal(false);
    setProductToDelete(null);
  };

  const confirmDelete = () => {
    if (productToDelete) {
      deleteProduct(productToDelete.id)
        .then(() => {
          const updatedProducts = products.filter(p => p.id !== productToDelete.id);
          setProducts(updatedProducts);
          setFilteredProducts(updatedProducts);
          closeDeleteModal();
          toast.success("Tipo de Produto deletado com sucesso");
        })
        .catch(error => console.error('Failed to delete product:', error));
    }
  };

  const pageCount = Math.ceil(filteredProducts.length / productsPerPage);
  const currentItems = filteredProducts.slice(
    currentPage * productsPerPage,
    (currentPage + 1) * productsPerPage
  );

  return (
    <div className="container mt-5">
      <div className="d-flex justify-content-between align-items-center mb-3">
        <center><h2>Produtos</h2></center>
      </div>
      <div className="input-group mb-3">
        <div className="col-4">
          <input
            type="text"
            className="form-control"
            placeholder="Buscar Produto"
            value={search}
            onChange={handleSearchChange}
          />
        </div>
        <div className="col-8 d-flex justify-content-end">
          <Link to="/products/new" className="btn btn-success">Adicionar</Link>
        </div>
      </div>
      <div className="table-responsive">
        <table className="table table-striped table-hover">
          <thead className="thead-dark">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Descrição</th>
              <th scope="col">Tipo do Produto</th>
              <th scope="col">Preço</th>
              <th scope="col">Impostos</th>
              <th scope="col">Data Criação</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>
          <tbody>
            {currentItems.map(product => (
              <tr key={product.id}>
                <td>{product.id}</td>
                <td>{product.description}</td>
                <td>{product.product_type.description}</td>
                <td>R${product.price}</td>
                <td>{product.product_type.tax}%</td>
                <td>{new Date(product.created_at).toLocaleDateString()}</td>
                <td>
                  <Link to={`/products/${product.id}/edit`} className="btn btn-primary mr-2">
                    <FontAwesomeIcon icon={faPencilAlt} />
                  </Link>
                  <button
                    className="btn btn-danger"
                    onClick={() => handleDeleteProduct(product)}
                  >
                    <FontAwesomeIcon icon={faTrash} />
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      {filteredProducts.length === 0 && (
        <div className="alert alert-info">Nenhum produto encontrado.</div>
      )}
      <ReactPaginate
        previousLabel={'<'}
        nextLabel={'>'}
        pageCount={pageCount}
        onPageChange={handlePageClick}
        containerClassName={'pagination justify-content-center'}
        pageClassName={'page-item'}
        pageLinkClassName={'page-link'}
        previousClassName={'page-item'}
        previousLinkClassName={'page-link'}
        nextClassName={'page-item'}
        nextLinkClassName={'page-link'}
        activeClassName={'active'}
      />
      {showDeleteModal && (
        <div className="modal-backdrop">
          <div className="modal-content">
            <center>
              <h5>Confirmação de Exclusão</h5>
            </center>
            <center>
              <p>Deseja Excluir o produto: {productToDelete.description}?</p>
            </center>
            <button className="btn btn-danger" onClick={confirmDelete}>
              Deletar
            </button>
            <button id='btn-close' className="btn btn-secondary" onClick={closeDeleteModal}>
              Cancelar
            </button>
          </div>
        </div>
      )}
    </div>
  );
};

export default ListProduct;
