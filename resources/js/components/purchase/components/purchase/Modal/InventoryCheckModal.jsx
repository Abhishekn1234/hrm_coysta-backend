import React, { useEffect } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBoxes, faTimes } from "@fortawesome/free-solid-svg-icons";

const InventoryCheckModal = ({ show, onClose }) => {
  // Prevent background scrolling when modal is open
  useEffect(() => {
    if (show) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "auto";
    }

    return () => {
      document.body.style.overflow = "auto";
    };
  }, [show]);

  if (!show) {
    return null;
  }

  return (
    <div className="modal-overlay">
      <style jsx>{`
        .modal-overlay {
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: rgba(0, 0, 0, 0.5);
          display: flex;
          justify-content: center;
          align-items: flex-start;
          z-index: 1000;
          padding: 40px 20px;
          overflow-y: auto;
          opacity: 0;
          animation: fadeIn 0.3s ease-out forwards;
        }

        .modal-content {
          background: white;
          border-radius: 12px;
          width: 90%;
          max-width: 900px;
          margin: 0 0;
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
          position: relative;
          transform: translateY(-50px);
          animation: slideDown 0.3s ease-out forwards;
        }

        @keyframes fadeIn {
          from {
            opacity: 0;
          }
          to {
            opacity: 1;
          }
        }

        @keyframes slideDown {
          from {
            transform: translateY(-50px);
          }
          to {
            transform: translateY(0);
          }
        }

        .modal-header {
          background: linear-gradient(120deg, var(--primary), var(--secondary));
          color: white;
          padding: 15px 20px;
          display: flex;
          justify-content: space-between;
          align-items: center;
          border-radius: 12px 12px 0 0;
        }

        .modal-title {
          margin: 0;
          font-size: 1.25rem;
          display: flex;
          align-items: center;
        }

        .modal-body {
          padding: 20px;
        }

        .modal-footer {
          padding: 15px 20px;
          background: #f8f9fa;
          border-top: 1px solid #eee;
          display: flex;
          justify-content: flex-end;
          gap: 10px;
          border-radius: 0 0 12px 12px;
        }

        .btn {
          padding: 8px 16px;
          border-radius: 6px;
          border: none;
          cursor: pointer;
          font-size: 0.9rem;
          transition: all 0.2s;
        }

        .btn-secondary {
          background-color: #6c757d;
          color: white;
        }

        .btn-secondary:hover {
          background-color: #5a6268;
        }

        .btn-primary {
          background-color: var(--primary);
          color: white;
        }

        .btn-primary:hover {
          background-color: var(--secondary);
        }

        .btn-close {
          background: none;
          border: none;
          color: white;
          font-size: 1.2rem;
          cursor: pointer;
          opacity: 0.8;
          padding: 5px;
        }

        .btn-close:hover {
          opacity: 1;
        }

        .table-responsive {
          overflow-x: auto;
        }

        .table {
          width: 100%;
          border-collapse: collapse;
        }

        .table th,
        .table td {
          padding: 12px 15px;
          text-align: left;
          border: 1px solid #ddd;
        }

        .table th {
          background-color: #f8f9fa;
          font-weight: 600;
        }

        .table-hover tbody tr:hover {
          background-color: rgba(0, 0, 0, 0.075);
        }

        .priority-high {
          color: #dc3545;
          font-weight: bold;
        }

        .priority-medium {
          color: #ffc107;
          font-weight: bold;
        }

        .priority-low {
          color: #28a745;
          font-weight: bold;
        }

        .alert {
          position: relative;
          padding: 12px 20px;
          margin-bottom: 16px;
          border: 1px solid transparent;
          border-radius: 6px;
        }

        .alert-warning {
          color: #856404;
          background-color: #fff3cd;
          border-color: #ffeeba;
        }

        .mt-3 {
          margin-top: 16px !important;
        }

        @media (max-width: 768px) {
          .modal-overlay {
            padding: 20px 10px;
          }

          .modal-content {
            width: 95%;
          }
        }
      `}</style>

      <div className="modal-content">
        <div className="modal-header">
          <h5 className="modal-title">
            <FontAwesomeIcon icon={faBoxes} className="me-2" />
            Inventory Status
          </h5>
          <button
            type="button"
            className="btn-close"
            onClick={onClose}
            aria-label="Close"
          >
            <FontAwesomeIcon icon={faTimes} />
          </button>
        </div>
        <div className="modal-body">
          <div className="table-responsive">
            <table className="table table-hover">
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Category</th>
                  <th>Current</th>
                  <th>Min Level</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Steel Beams (50cm)</td>
                  <td>Materials</td>
                  <td>42</td>
                  <td>100</td>
                  <td className="priority-high">Critical</td>
                </tr>
                <tr>
                  <td>Electrical Wiring</td>
                  <td>Electrical</td>
                  <td>120m</td>
                  <td>300m</td>
                  <td className="priority-high">Critical</td>
                </tr>
                <tr>
                  <td>Concrete Mix</td>
                  <td>Materials</td>
                  <td>8 tons</td>
                  <td>15 tons</td>
                  <td className="priority-medium">Low</td>
                </tr>
                <tr>
                  <td>Safety Helmets</td>
                  <td>PPE</td>
                  <td>15</td>
                  <td>40</td>
                  <td className="priority-medium">Low</td>
                </tr>
                <tr>
                  <td>Industrial Pumps</td>
                  <td>Equipment</td>
                  <td>2</td>
                  <td>5</td>
                  <td className="priority-low">OK</td>
                </tr>
                <tr>
                  <td>Nails (5cm)</td>
                  <td>Materials</td>
                  <td>2500</td>
                  <td>1000</td>
                  <td className="priority-low">OK</td>
                </tr>
                <tr>
                  <td>PVC Pipes</td>
                  <td>Plumbing</td>
                  <td>85</td>
                  <td>50</td>
                  <td className="priority-low">OK</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div className="alert alert-warning mt-3">
            <i className="fas fa-exclamation-triangle me-2"></i>
            <strong>2 Critical Items</strong> need immediate reordering to avoid
            project delays.
          </div>
        </div>
        <div className="modal-footer">
          <button type="button" className="btn btn-secondary" onClick={onClose}>
            Close
          </button>
          <button type="button" className="btn btn-primary">
            Generate Reorder List
          </button>
        </div>
      </div>
    </div>
  );
};

export default InventoryCheckModal;
