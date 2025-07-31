import React, { useState } from "react";
import CreateRequisitionModal from "../Modal/RequisitionModal";
import CreatePOModal from "../Modal/CreatePOModal";
import AddVendorModal from "../Modal/AddVendorModal";
import InventoryCheckModal from "../Modal/InventoryCheckModal";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { 
  faBolt, 
  faFileMedical, 
  faFileInvoice, 
  faUserPlus, 
  faBoxes 
} from '@fortawesome/free-solid-svg-icons';

const QuickActionsDashboard = () => {
  const [showRequisitionModal, setShowRequisitionModal] = useState(false);
  const [showPOModal, setShowPOModal] = useState(false);
  const [showVendorModal, setShowVendorModal] = useState(false);
  const [showInventoryModal, setShowInventoryModal] = useState(false);

  return (
    <>
      <style>
        {`
          .dashboard-section-title {
              font-size: 1.4rem;
              font-weight: 700;
              color: #333;
              margin-bottom: 25px;
              padding-bottom: 12px;
              border-bottom: 2px solid #eee;
              display: flex;
              align-items: center;
              gap: 12px;
          }
          
          .quick-actions {
              display: flex;
              gap: 20px;
              margin-bottom: 30px;
              flex-wrap: wrap;
          }
          
          .quick-action-btn {
              flex: 1;
              min-width: 200px;
              padding: 20px;
              border-radius: 10px;
              background: white;
              border: none;
              text-align: center;
              box-shadow: 0 4px 12px rgba(0,0,0,0.06);
              transition: all 0.3s;
              display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: center;
              color: #444;
          }
          
          .quick-action-btn:hover {
              background: #f8f9fa;
              transform: translateY(-3px);
              box-shadow: 0 8px 16px rgba(0,0,0,0.1);
              color: var(--primary);
          }
          
          .quick-action-icon {
              font-size: 2.5rem;
              margin-bottom: 15px;
              color: var(--primary);
              background: rgba(60, 141, 188, 0.1);
              width: 70px;
              height: 70px;
              border-radius: 50%;
              display: flex;
              align-items: center;
              justify-content: center;
          }
        `}
      </style>

      {/* Quick Actions Section */}
      <div className="dashboard-section-title">
        <FontAwesomeIcon icon={faBolt} /> Quick Actions
      </div>

      <div className="quick-actions">
        <button
          onClick={() => setShowRequisitionModal(true)}
          className="quick-action-btn"
          data-bs-toggle="modal"
          data-bs-target="#createRequisitionModal"
        >
          <div className="quick-action-icon">
            <FontAwesomeIcon icon={faFileMedical} />
          </div>
          <span>Create Requisition</span>
        </button>

        <button
          onClick={() => setShowPOModal(true)}
          className="quick-action-btn"
          data-bs-toggle="modal"
          data-bs-target="#createPOModal"
        >
          <div className="quick-action-icon">
            <FontAwesomeIcon icon={faFileInvoice} />
          </div>
          <span>Generate PO</span>
        </button>

        <button
          className="quick-action-btn"
          data-bs-toggle="modal"
          data-bs-target="#addVendorModal"
          onClick={() => setShowVendorModal(true)}
        >
          <div className="quick-action-icon">
            <FontAwesomeIcon icon={faUserPlus} />
          </div>
          <span>Add Vendor</span>
        </button>

        <button
          onClick={() => setShowInventoryModal(true)}
          className="quick-action-btn"
          data-bs-toggle="modal"
          data-bs-target="#inventoryCheckModal"
        >
          <div className="quick-action-icon">
            <FontAwesomeIcon icon={faBoxes} />
          </div>
          <span>Inventory Check</span>
        </button>
      </div>
      
      <CreateRequisitionModal
        show={showRequisitionModal}
        onClose={() => setShowRequisitionModal(false)}
      />
      <CreatePOModal show={showPOModal} onClose={() => setShowPOModal(false)} />
      <AddVendorModal 
        show={showVendorModal} 
        onClose={() => setShowVendorModal(false)} 
      />
      <InventoryCheckModal 
        show={showInventoryModal} 
        onClose={() => setShowInventoryModal(false)} 
      />
    </>
  );
};

export default QuickActionsDashboard;