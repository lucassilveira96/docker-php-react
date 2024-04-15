// Importe useState para usar estados no componente
import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTrash, faFileAlt } from '@fortawesome/free-solid-svg-icons';
import { faDollarSign, faPercent } from '@fortawesome/free-solid-svg-icons';
import ReactPaginate from 'react-paginate';
import './css/ListSale.css';
import { deleteSale, fetchSales } from '../../services/saleService';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import ViewSale from './ViewSale';

const ListSale = () => {
  const [sales, setSales] = useState([]);
  const [search, setSearch] = useState('');
  const [currentPage, setCurrentPage] = useState(0);
  const salesPerPage = 5;
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [saleToDelete, setSaleToDelete] = useState(null);
  const [filteredSales, setFilteredSales] = useState([]);
  const [viewModalOpen, setViewModalOpen] = useState(false); // Estado para controlar a abertura e fechamento do modal
  const [selectedSale, setSelectedSale] = useState(null); // Estado para armazenar a venda selecionada para visualização

  useEffect(() => {
    fetchSales()
      .then(response => {
        setSales(response.data.data);
        setFilteredSales(response.data.data);
      })
      .catch(error => console.error('Error fetching sales:', error));
  }, [selectedSale]);

  const handleSearchChange = (e) => {
    setSearch(e.target.value);
  };

  const handlePageClick = (data) => {
    setCurrentPage(data.selected);
  };

  const handleDeleteSale = (sale) => {
    setSaleToDelete(sale);
    setShowDeleteModal(true);
  };

  const openViewModal = (sale) => {
    setSelectedSale(sale);
    setViewModalOpen(true);
    console.log(sale,selectedSale)
  };

  const closeViewModal = () => {
    setViewModalOpen(false);
    setSelectedSale(null);
  };

  const confirmDelete = () => {
    if (saleToDelete) {
      deleteSale(saleToDelete.id)
        .then(() => {
          const updatedSales = sales.filter(p => p.id !== saleToDelete.id);
          setSales(updatedSales);
          setFilteredSales(updatedSales);
          //closeDeleteModal();
          toast.success("Tipo de Produto deletado com sucesso");
        })
        .catch(error => console.error('Failed to delete sale:', error));
    }
  };

  const pageCount = Math.ceil(filteredSales.length / salesPerPage);
  const currentItems = filteredSales.slice(
    currentPage * salesPerPage,
    (currentPage + 1) * salesPerPage
  );

  // Calcular a soma total de vendas e impostos
  const totalSales = sales.reduce((total, sale) => total + sale.total_amount, 0);
  const totalTaxes = sales.reduce((total, sale) => total + sale.total_tax, 0);

  return (
    <div className="container mt-5">
      <h3>Vendas</h3>
      <div className="row mb-3">
        <div className="col-md-6">
          <div className="card text-white bg-success mb-3 opacity-50">
            <div className="card-body">
              <h5 className="card-title"><FontAwesomeIcon icon={faDollarSign} /> Total de Vendas</h5>
              <p className="card-text">R$ {totalSales.toFixed(2)}</p>
            </div>
          </div>
        </div>
        <div className="col-md-6">
          <div className="card text-white bg-danger mb-3 opacity-50">
            <div className="card-body">
              <h5 className="card-title"><FontAwesomeIcon icon={faPercent} /> Total de Impostos</h5>
              <p className="card-text">R$ {totalTaxes.toFixed(2)}</p>
            </div>
          </div>
        </div>
      </div>
      <div className="input-group mb-3">
        <div className="col-4">
          <input
            type="text"
            className="form-control"
            placeholder="Buscar Cliente"
            value={search}
            onChange={handleSearchChange}
          />
        </div>
        <div className="col-8 d-flex justify-content-end">
          <Link to="/sales/new" className="btn btn-success">Adicionar</Link>
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
            {currentItems.map(sale => (
              <tr key={sale.id}>
                <td>{sale.id}</td>
                <td>{sale.id}</td>
                <td>{sale.id}</td>
                <td>R$ {sale.total_amount}</td>
                <td>R$ {sale.total_tax}</td>
                <td>{new Date(sale.created_at).toLocaleDateString()}</td>
                <td>
                  <button
                    className="btn btn-danger mr-2"
                    onClick={() => handleDeleteSale(sale)}
                  >
                    <FontAwesomeIcon icon={faTrash} />
                  </button>
                  {/* Botão para abrir o modal */}
                  <Link to={`/sales/${sale.id}/view`} className="btn btn-primary mr-2">
                    <FontAwesomeIcon icon={faFileAlt} />
                  </Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      {filteredSales.length === 0 && (
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
              <p>Deseja Excluir o produto: {saleToDelete.description}?</p>
            </center>
            <button className="btn btn-danger" onClick={confirmDelete}>
              Deletar
            </button>
            <button id='btn-close' className="btn btn-secondary" >
              Cancelar
            </button>
          </div>
        </div>
      )}
      {/* Modal para visualizar a nota */}
      {viewModalOpen && (
        <div className="modal-backdrop">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title">Visualizar Nota</h5>
              <button type="button" className="btn-close" aria-label="Close" onClick={closeViewModal}></button>
            </div>
            <div className="modal-body">
            <ViewSale saleId={selectedSale} />
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default ListSale;
