import React, { useState } from 'react';
import SaleTab from './tabs/SaleTab';
import PurchaseTab from './tabs/PurchaseTab';
import IncomeTab from './tabs/IncomeTab';
import ExpenseTab from './tabs/ExpenseTab';
import CashTab from './tabs/CashTab';
import LedgerTab from './tabs/LedgerTab';
import ReportsTab from './tabs/ReportsTab';

const Main = () => {
  const [activeTab, setActiveTab] = useState('sale');

  const renderTab = () => {
    switch (activeTab) {
      case 'sale':
        return <SaleTab />;
      case 'purchase':
        return <PurchaseTab />;
      case 'income':
        return <IncomeTab />;
      case 'expense':
        return <ExpenseTab />;
      case 'cash':
        return <CashTab />;
      case 'ledger':
        return <LedgerTab />;
      case 'reports':
        return <ReportsTab />;
      default:
        return <SaleTab />;
    }
  };

  return (
    <div className="main-container">
      <ul className="nav nav-tabs mb-3">
        <li className="nav-item">
          <button className={`nav-link ${activeTab === 'sale' ? 'active' : ''}`} onClick={() => setActiveTab('sale')}>
            Sale
          </button>
        </li>
        <li className="nav-item">
          <button className={`nav-link ${activeTab === 'purchase' ? 'active' : ''}`} onClick={() => setActiveTab('purchase')}>
            Purchase
          </button>
        </li>
        <li className="nav-item">
          <button className={`nav-link ${activeTab === 'income' ? 'active' : ''}`} onClick={() => setActiveTab('income')}>
            Income
          </button>
        </li>
        <li className="nav-item">
          <button className={`nav-link ${activeTab === 'expense' ? 'active' : ''}`} onClick={() => setActiveTab('expense')}>
            Expense
          </button>
        </li>
        <li className="nav-item">
          <button className={`nav-link ${activeTab === 'cash' ? 'active' : ''}`} onClick={() => setActiveTab('cash')}>
            Cash
          </button>
        </li>
        <li className="nav-item">
          <button className={`nav-link ${activeTab === 'ledger' ? 'active' : ''}`} onClick={() => setActiveTab('ledger')}>
            Ledger
          </button>
        </li>
        <li className="nav-item">
          <button className={`nav-link ${activeTab === 'reports' ? 'active' : ''}`} onClick={() => setActiveTab('reports')}>
            Reports
          </button>
        </li>
      </ul>

      <div className="tab-content">
        {renderTab()}
      </div>
    </div>
  );
};

export default Main;
