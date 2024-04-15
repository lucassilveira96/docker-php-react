import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Sidebar from './components/common/Sidebar';
import ListProduct from './components/products/ListProduct';
import AddProduct from './components/products/AddProduct';
import ListSale from './components/sales/ListSale';
import 'bootstrap/dist/css/bootstrap.min.css';
import ListProductType from './components/product-types/ListProductType';
import 'react-toastify/dist/ReactToastify.css';
import { ToastContainer } from 'react-toastify';
import EditProduct from './components/products/EditProduct';
import AddProductType from './components/product-types/AddProductType';
import EditProductType from './components/product-types/EditProductType';
import AddSale from './components/sales/AddSale';
import ViewSale from './components/sales/ViewSale';


function App() {
  return (
    <Router>
      <div style={{ display: 'flex' }}>
        <Sidebar />
        <div style={{ flex: 1, padding: '10px' }}>
          <Routes>
            <Route path="/product-types" element={<ListProductType />} />  
            <Route path="/product-types/new" element={<AddProductType/>} />
            <Route path="/product-types/:id/edit" element={<EditProductType/>} />
            <Route path="/products" element={<ListProduct />} />
            <Route path="/products/new" element={<AddProduct/>} />
            <Route path="/products/:id/edit" element={<EditProduct/>} />
            <Route path="/sales" element={<ListSale />} />
            <Route path="/sales/new" element={<AddSale />} />
            <Route path="/sales/:id/view" element={<ViewSale />} />
          </Routes>
          <ToastContainer />
        </div>
      </div>
    </Router>
  );
}

export default App;
