import React, { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus } from "@fortawesome/free-solid-svg-icons";
import CreateRequisitionModal from "../../Modal/RequisitionModal";
import CreatePOModal from "../../Modal/CreatePOModal";
const RequisitionsTab = () => {
  const [showRequisitionModal, setShowRequisitionModal] = useState(false);
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
          
          .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
          }
          
          .badge-pending {
            background-color: #fce9cd;
            color: #e67e22;
          }
          
          .badge-approved {
            background-color: #d4f8e8;
            color: #27ae60;
          }
          
          .badge-rejected {
            background-color: #fadbd8;
            color: #c0392b;
          }
          
          .priority-high {
            color: #e74c3c;
            font-weight: 600;
          }
          
          .priority-medium {
            color: #f39c12;
            font-weight: 600;
          }
          
          .priority-low {
            color: #27ae60;
            font-weight: 600;
          }
        `}
      </style>

      <div className="card-header">
        <span>Purchase Requisitions</span>
        <button
          onClick={() => setShowRequisitionModal(true)}
          className="btn btn-sm btn-primary action-btn"
          data-bs-toggle="modal"
          data-bs-target="#createRequisitionModal"
        >
          <FontAwesomeIcon icon={faPlus} /> New
        </button>
      </div>
      <div className="card-body">
        <div className="table-responsive">
          <table className="table table-hover">
            <thead>
              <tr>
                <th>Req #</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Priority</th>
                <th>Department</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>PR-2023-045</td>
                <td>Steel Beams</td>
                <td>120 units</td>
                <td className="priority-high">High</td>
                <td>Construction</td>
                <td>
                  <span className="status-badge badge-pending">Pending</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-primary">
                    Approve
                  </button>
                  <button className="btn btn-sm btn-outline-secondary">
                    View
                  </button>
                </td>
              </tr>
              <tr>
                <td>PR-2023-046</td>
                <td>Electrical Wiring</td>
                <td>500 meters</td>
                <td className="priority-medium">Medium</td>
                <td>Electrical</td>
                <td>
                  <span className="status-badge badge-approved">Approved</span>
                </td>
                <td>
                  <button
                            onClick={() => setShowPOModal(true)}

                    className="btn btn-sm btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#createPOModal"
                  >
                    Create PO
                  </button>
                </td>
              </tr>
              <tr>
                <td>PR-2023-047</td>
                <td>Safety Helmets</td>
                <td>50 units</td>
                <td className="priority-low">Low</td>
                <td>Safety</td>
                <td>
                  <span className="status-badge badge-rejected">Rejected</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-danger">
                    Delete
                  </button>
                </td>
              </tr>
              <tr>
                <td>PR-2023-048</td>
                <td>Concrete Mix</td>
                <td>20 tons</td>
                <td className="priority-high">High</td>
                <td>Construction</td>
                <td>
                  <span className="status-badge badge-approved">Approved</span>
                </td>
                <td>
                  <button
                            onClick={() => setShowPOModal(true)}

                    className="btn btn-sm btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#createPOModal"
                  >
                    Create PO
                  </button>
                </td>
              </tr>
              <tr>
                <td>PR-2023-049</td>
                <td>Industrial Pumps</td>
                <td>8 units</td>
                <td className="priority-medium">Medium</td>
                <td>Mechanical</td>
                <td>
                  <span className="status-badge badge-pending">Pending</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-primary">
                    Approve
                  </button>
                  <button className="btn btn-sm btn-outline-secondary">
                    View
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
       
      <CreateRequisitionModal
        show={showRequisitionModal}
        onClose={() => setShowRequisitionModal(false)}
      />
            <CreatePOModal show={showPOModal} onClose={() => setShowPOModal(false)} />

    </div>
  );
};

export default RequisitionsTab;
