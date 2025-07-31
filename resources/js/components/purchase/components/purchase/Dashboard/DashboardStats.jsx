import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { 
  faFileInvoice, 
  faClipboardList, 
  faPeopleCarry, 
  faExclamationTriangle 
} from '@fortawesome/free-solid-svg-icons';

const DashboardStats = () => {
  return (
    <>
      <style>
        {`
          .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
          }
          
          .stat-card {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: none;
            height: 100%;
          }
          
          .stat-card:hover {
            transform: translateY(-5px);
          }
          
          .stat-card-header {
            padding: 18px;
            display: flex;
            align-items: center;
            color: white;
          }
          
          .stat-card-body {
            padding: 18px;
            background: white;
          }
          
          .stat-icon {
            font-size: 2.8rem;
            margin-right: 15px;
            opacity: 0.85;
          }
          
          .stat-number {
            font-size: 2rem;
            font-weight: 700;
          }
          
          .stat-title {
            font-size: 1.05rem;
            opacity: 0.95;
          }
       
          .progress-container {
            height: 8px;
            background: #eee;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
          }
          
          .progress-bar {
            height: 100%;
          }
        
          .bg-info {
            background-color: var(--info) !important;
          }
          .bg-success {
            background-color: var(--success) !important;
          }
          .bg-warning {
            background-color: var(--warning) !important;
          }
          .bg-danger {
            background-color: var(--danger) !important;
          }
          .d-flex {
            display: flex !important;
          }
          .justify-content-between {
            justify-content: space-between !important;
          }

          @media (max-width: 768px) {
            .stats-container {
              grid-template-columns: 1fr 1fr;
            }
          }

          @media (max-width: 480px) {
            .stats-container {
              grid-template-columns: 1fr;
            }
          }
        `}
      </style>

      <div className="stats-container">
        <div className="stat-card">
          <div className="stat-card-header bg-info">
            <FontAwesomeIcon icon={faFileInvoice} className="stat-icon" />
            <div>
              <div className="stat-number">24</div>
              <div className="stat-title">Pending Requisitions</div>
            </div>
          </div>
          <div className="stat-card-body">
            <div className="d-flex justify-content-between">
              <span>Approved: 18</span>
              <span>High: 8</span>
            </div>
            <div className="progress-container">
              <div className="progress-bar bg-info" style={{width: '75%'}}></div>
            </div>
          </div>
        </div>
        
        <div className="stat-card">
          <div className="stat-card-header bg-success">
            <FontAwesomeIcon icon={faClipboardList} className="stat-icon" />
            <div>
              <div className="stat-number">42</div>
              <div className="stat-title">Active POs</div>
            </div>
          </div>
          <div className="stat-card-body">
            <div className="d-flex justify-content-between">
              <span>Delivered: 32</span>
              <span>Late: 3</span>
            </div>
            <div className="progress-container">
              <div className="progress-bar bg-success" style={{width: '85%'}}></div>
            </div>
          </div>
        </div>
        
        <div className="stat-card">
          <div className="stat-card-header bg-warning">
            <FontAwesomeIcon icon={faPeopleCarry} className="stat-icon" />
            <div>
              <div className="stat-number">18</div>
              <div className="stat-title">Vendors</div>
            </div>
          </div>
          <div className="stat-card-body">
            <div className="d-flex justify-content-between">
              <span>Active: 15</span>
              <span>New: 3</span>
            </div>
            <div className="progress-container">
              <div className="progress-bar bg-warning" style={{width: '90%'}}></div>
            </div>
          </div>
        </div>
        
        <div className="stat-card">
          <div className="stat-card-header bg-danger">
            <FontAwesomeIcon icon={faExclamationTriangle} className="stat-icon" />
            <div>
              <div className="stat-number">7</div>
              <div className="stat-title">Critical Inventory</div>
            </div>
          </div>
          <div className="stat-card-body">
            <div className="d-flex justify-content-between">
              <span>Materials: 5</span>
              <span>Equipment: 2</span>
            </div>
            <div className="progress-container">
              <div className="progress-bar bg-danger" style={{width: '30%'}}></div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default DashboardStats;