import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faShoppingCart, faUser } from '@fortawesome/free-solid-svg-icons';

const DashboardHeader = () => {
  return (
    <div className="dashboard-header">
      <style>
        {`
          .dashboard-header {
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            color: white;
            padding: 25px 30px;
            border-radius: 0 0 10px 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
          }
          
          .dashboard-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
          }
          
          .dashboard-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
          }
          
          .user-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
          }
        `}
      </style>
      <div className="d-flex justify-content-between align-items-center">
        <div>
          <h1 className="dashboard-title">
            <FontAwesomeIcon icon={faShoppingCart} className="me-3" /> 
            Procurement Management
          </h1>
          <p className="dashboard-subtitle mb-0">Manage vendors, purchase requisitions, and orders in one place</p>
        </div>
        <div className="text-end">
          <div className="d-flex align-items-center gap-3">
            <div className="text-end">
              <h5 className="mb-0">John Anderson</h5>
              <p className="mb-0">Procurement Manager</p>
            </div>
            <div className="user-avatar">
              <FontAwesomeIcon icon={faUser} className="text-white" />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DashboardHeader;