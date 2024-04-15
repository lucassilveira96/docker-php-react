import React, { useState, useEffect } from 'react';
import './css/ViewSale.css'
import { getSaleById } from '../../services/saleService';
import { useParams, useNavigate } from 'react-router-dom';

const ViewSale = ({ saleId }) => {
  const [viewSaleData, setViewSaleData] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const { id } = useParams();
  const navigate = useNavigate();


  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      try {
        getSaleById(id)
          .then(response => {
            if (response.data.data === null) {
              navigate('/products');
            }
            setViewSaleData(response.data.data);
            console.log(response.data.data)
          })
          .catch(error => {
            console.error('Erro ao buscar os detalhes do produto:', error);
          });
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [saleId]);

  const handlePrint = () => {
    window.print();
  };

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;

  if (!viewSaleData) return null;

  return (
    <div className="ViewSale">
      <div className="header">
        <div className="logo">LUCAS DA SILVA SILVEIRA</div>
        <div className="store-info">
          <p>Loja 1</p>
          <p>Silveira Soluções em Tecnologia da Informação LTDA</p>
          <div className="cnpj">CNPJ: 00.000.000/0000-00</div>
          <p>Três Cachoeiras - RS</p>
        </div>
      </div>
      <div className="body">
        <center><p>-----------------------------------------------------------------------------------</p></center>
        <div className="title">CUPOM FISCAL ELETRÔNICO</div>
        <center><p>-----------------------------------------------------------------------------------</p></center>
        <div className="items">
          {viewSaleData.items.map(item => (
            <React.Fragment key={item.product.id}> 
              <center><p>{item.product.description} --- {item.quantity} x R$ {item.price_per_unit} --- R$ {item.total_price}</p></center>
            </React.Fragment>
          ))}
          <center><p>-----------------------------------------------------------------------------------</p></center>
          <center><p>Valor Total dos Produtos: R$ {viewSaleData.total_amount.toFixed(2)} </p></center>
          <center><p>Impostos totais: R$ {viewSaleData.total_tax.toFixed(2)} </p></center>
          <center><p>-----------------------------------------------------------------------------------</p></center>
        </div>
      </div>
      <div className="footer">
        <p>OBSERVAÇÕES DO CONTRIBUINTE</p>
        <p>Lei Federal 12.741/2012</p>
        <p>{new Date(viewSaleData.created_at).toLocaleString('pt-BR', { dateStyle: 'full', timeStyle: 'full' })} </p>
        <center><p>-----------------------------------------------------------------------------------</p></center>
        <center><p>AGRADECEMOS PELA PREFERENCIA</p></center>
      </div>
      <div className="button-group">
        <button className="btn" onClick={() => navigate('/sales')}>Voltar</button>
        <button className="btn" onClick={handlePrint}>Imprimir</button>
      </div>
    </div>
  );
};

export default ViewSale;
