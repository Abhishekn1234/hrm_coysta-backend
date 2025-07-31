import React, { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus } from "@fortawesome/free-solid-svg-icons";
import CreatePOModal from "../../Modal/CreatePOModal";

const OrdersTab = () => {
  const [showPOModal, setShowPOModal] = useState(false);

  return (
    <div className="card">
      <style>
        {`
         .card {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            margin-bottom: 25px;
            border: none;
          }
          
          .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 18px 25px;
            font-weight: 600;
            font-size: 1.2rem;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px 10px 0 0 !important;
          }
          
          .action-btn {
            padding: 6px 15px;
            border-radius: 6px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
          }
          
          .table-responsive {
            border-radius: 8px;
            overflow: hidden;
          }
          
          .table th {
            background-color: #f8f9fa;
            color: #444;
            font-weight: 600;
            padding: 14px 18px;
          }
          
          .table td {
            padding: 14px 18px;
            vertical-align: middle;
          }
          .badge-completed {
            background-color: #d6eaf8;
            color: #2980b9;
          }
        `}
      </style>

      <div className="card-header">
        <span>Purchase Orders</span>
        <button
          onClick={() => setShowPOModal(true)}
          className="btn btn-sm btn-primary action-btn"
          data-bs-toggle="modal"
          data-bs-target="#createPOModal"
        >
          <FontAwesomeIcon icon={faPlus} /> New PO
        </button>
      </div>
      <div className="card-body">
        <div className="table-responsive">
          <table className="table table-hover">
            <thead>
              <tr>
                <th>PO #</th>
                <th>Vendor</th>
                <th>Amount</th>
                <th>Delivery Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>PO-2023-102</td>
                <td>SteelCo Inc.</td>
                <td>$24,500</td>
                <td>2023-06-15</td>
                <td>
                  <span className="status-badge badge-approved">Approved</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-primary">
                    Track
                  </button>
                </td>
              </tr>
              <tr>
                <td>PO-2023-103</td>
                <td>Electro Supplies</td>
                <td>$8,750</td>
                <td>2023-06-10</td>
                <td>
                  <span className="status-badge badge-completed">
                    Delivered
                  </span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-success">
                    Receive
                  </button>
                </td>
              </tr>
              <tr>
                <td>PO-2023-104</td>
                <td>Builders Depot</td>
                <td>$15,200</td>
                <td>2023-06-20</td>
                <td>
                  <span className="status-badge badge-pending">Pending</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-warning">
                    Edit
                  </button>
                </td>
              </tr>
              <tr>
                <td>PO-2023-105</td>
                <td>Safety Gear Ltd.</td>
                <td>$3,450</td>
                <td>2023-06-05</td>
                <td>
                  <span className="status-badge badge-completed">
                    Delivered
                  </span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-success">
                    Receive
                  </button>
                </td>
              </tr>
              <tr>
                <td>PO-2023-106</td>
                <td>Concrete Masters</td>
                <td>$12,800</td>
                <td>2023-06-18</td>
                <td>
                  <span className="status-badge badge-approved">Shipped</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-primary">
                    Track
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <CreatePOModal show={showPOModal} onClose={() => setShowPOModal(false)} />
    </div>
  );
};

export default OrdersTab;
