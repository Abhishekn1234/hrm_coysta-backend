import React, { useState } from 'react';
import RequisitionsTab from './Tabs/PurchaseRequisitionsTab';
import VendorsTab from './Tabs/VendorsMgmtTab';
import OrdersTab from './Tabs/PurchaseOrdersTab';
import CriticalInventory from './Tabs/CriticalInventory';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { 
  faFileInvoice,
  faPeopleCarry,
  faClipboardList,
  faExclamationTriangle
} from '@fortawesome/free-solid-svg-icons';

const ProcurementTabs = () => {
  const [activeTab, setActiveTab] = useState('requisitions');

  return (
    <>
      {/* <style>
        {`
          .nav-tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 25px;
          }
          
          .nav-tabs .nav-link {
            padding: 15px 25px;
           
            color: #555;
            border: none;
            border-radius: 8px 8px 0 0;
            background: #f1f5f9;
            margin-right: 5px;
          }
          
          .nav-tabs .nav-link.active {
            background: white;
            border-bottom: 3px solid var(--primary);
            color: var(--primary);
          }
          
          .tab-pane {
            padding: 20px 0;
          }
          
          @media (max-width: 768px) {
            .nav-tabs .nav-link {
              padding: 12px 15px;
              font-size: 0.95rem;
            }
          }
        `}
      </style> */}
      
      <ul className="nav nav-tabs" id="procurementTabs" role="tablist">
        <li className="nav-item" role="presentation">
          <button 
            className={`nav-link ${activeTab === 'requisitions' ? 'active' : ''}`}
            onClick={() => setActiveTab('requisitions')}
            type="button"
          >
            <FontAwesomeIcon icon={faFileInvoice} className="me-2" /> Purchase Requisitions
          </button>
        </li>
        <li className="nav-item" role="presentation">
          <button 
            className={`nav-link ${activeTab === 'vendors' ? 'active' : ''}`}
            onClick={() => setActiveTab('vendors')}
            type="button"
          >
            <FontAwesomeIcon icon={faPeopleCarry} className="me-2" /> Vendor Management
          </button>
        </li>
        <li className="nav-item" role="presentation">
          <button 
            className={`nav-link ${activeTab === 'orders' ? 'active' : ''}`}
            onClick={() => setActiveTab('orders')}
            type="button"
          >
            <FontAwesomeIcon icon={faClipboardList} className="me-2" /> Purchase Orders
          </button>
        </li>
        <li className="nav-item" role="presentation">
          <button 
            className={`nav-link ${activeTab === 'inventory' ? 'active' : ''}`}
            onClick={() => setActiveTab('inventory')}
            type="button"
          >
            <FontAwesomeIcon icon={faExclamationTriangle} className="me-2" /> Critical Inventory
          </button>
        </li>
      </ul>
      
      <div className="tab-content" id="procurementTabsContent">
        <div className={`tab-pane fade ${activeTab === 'requisitions' ? 'show active' : ''}`}>
          {activeTab === 'requisitions' && <RequisitionsTab />}
        </div>
        <div className={`tab-pane fade ${activeTab === 'vendors' ? 'show active' : ''}`}>
          {activeTab === 'vendors' && <VendorsTab />}
        </div>
        <div className={`tab-pane fade ${activeTab === 'orders' ? 'show active' : ''}`}>
          {activeTab === 'orders' && <OrdersTab />}
        </div>
        <div className={`tab-pane fade ${activeTab === 'inventory' ? 'show active' : ''}`}>
          {activeTab === 'inventory' && <CriticalInventory />}
        </div>
      </div>
    </>
  );
};

export default ProcurementTabs;