import React from 'react';
import {
  CDBSidebar,
  CDBSidebarContent,
  CDBSidebarHeader,
  CDBSidebarMenu,
  CDBSidebarMenuItem,
} from 'cdbreact';
import { NavLink } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faBox, faClipboard } from '@fortawesome/free-solid-svg-icons';
import { faShoppingCart } from '@fortawesome/free-solid-svg-icons';

const Sidebar = () => {
  const setNavLinkClass = ({ isActive }) => isActive ? 'activeClicked' : '';

  return (
    <div style={{ display: 'flex', height: '100vh', overflow: 'scroll initial' }}>
      <CDBSidebar textColor="#fff" backgroundColor="#333">
        <CDBSidebarHeader prefix={<i className="fa fa-bars fa-large"></i>}>
          <NavLink 
            to="/products" 
            className={({ isActive }) => isActive ? 'text-decoration-none activeClicked' : 'text-decoration-none'}
            style={{ color: 'inherit' }}
          >
            Sistema de Vendas
          </NavLink>
        </CDBSidebarHeader>

        <CDBSidebarContent className="sidebar-content">
          <CDBSidebarMenu>
            <NavLink to="/product-types" className={setNavLinkClass}>
              <CDBSidebarMenuItem icon="sticky-note">
                 Tipos de Produtos
              </CDBSidebarMenuItem>
            </NavLink>
            <NavLink to="/products" className={setNavLinkClass}>
              <CDBSidebarMenuItem icon="box">
                 Produtos
              </CDBSidebarMenuItem>
            </NavLink>
            <NavLink to="/sales" className={setNavLinkClass}>
              <CDBSidebarMenuItem icon="credit-card">
                 Vendas
              </CDBSidebarMenuItem>
            </NavLink>
          </CDBSidebarMenu>
        </CDBSidebarContent>
      </CDBSidebar>
    </div>
  );
};

export default Sidebar;
