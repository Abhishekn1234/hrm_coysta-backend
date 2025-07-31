import React, { useEffect } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faFileInvoice, faTimes } from "@fortawesome/free-solid-svg-icons";
const CreatePOModal = ({ show, onClose }) => {
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

        .btn-danger {
          background-color: var(--danger);
          color: white;
        }

        .btn-danger:hover {
          background-color: #c82333;
        }

        .btn-outline-primary {
          background-color: transparent;
          border: 1px solid var(--primary);
          color: var(--primary);
        }

        .btn-outline-primary:hover {
          background-color: var(--primary);
          color: white;
        }

        .btn-sm {
          padding: 5px 10px;
          font-size: 0.8rem;
        }

        .form-label {
          display: block;
          margin-bottom: 8px;
          font-weight: 500;
        }

        .form-control,
        .form-select {
          width: 100%;
          padding: 10px;
          border: 1px solid #ddd;
          border-radius: 6px;
          font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
          outline: none;
          border-color: var(--primary);
          box-shadow: 0 0 0 3px rgba(60, 141, 188, 0.2);
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

        .table-responsive {
          overflow-x: auto;
        }

        .text-end {
          text-align: right;
        }

        .fw-bold {
          font-weight: 600;
        }

        .mt-2 {
          margin-top: 8px;
        }

        .mb-3 {
          margin-bottom: 16px;
        }

        .row {
          display: flex;
          flex-wrap: wrap;
          margin: 0 -10px;
        }

        .col-md-6 {
          flex: 0 0 50%;
          max-width: 50%;
          padding: 0 10px;
          box-sizing: border-box;
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

        @media (max-width: 768px) {
          .modal-overlay {
            padding: 20px 10px;
          }

          .modal-content {
            width: 95%;
            margin: 20px 0;
          }

          .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
          }
        }
      `}</style>

      <div className="modal-content">
        <div className="modal-header">
          <h5 className="modal-title">
            <FontAwesomeIcon icon={faFileInvoice} className="me-2" />
            Create Purchase Order
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
          <form>
            <div className="row mb-3">
              <div className="col-md-6">
                <label className="form-label">Select Vendor</label>
                <select className="form-select">
                  <option>SteelCo Inc.</option>
                  <option>Electro Supplies</option>
                  <option>Builders Depot</option>
                  <option>Safety Gear Ltd.</option>
                  <option>Concrete Masters</option>
                </select>
              </div>
              <div className="col-md-6">
                <label className="form-label">PO Date</label>
                <input
                  type="date"
                  className="form-control"
                  value="2023-05-30"
                />
              </div>
            </div>

            <div className="mb-3">
              <label className="form-label">Items to Order</label>
              <div className="table-responsive">
                <table className="table table-bordered">
                  <thead>
                    <tr>
                      <th>Item</th>
                      <th>Quantity</th>
                      <th>Unit Price</th>
                      <th>Total</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Steel Beams (50cm)</td>
                      <td>120</td>
                      <td>$180.00</td>
                      <td>$21,600.00</td>
                      <td>
                        <button className="btn btn-sm btn-danger">
                          <i className="fas fa-times"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>Steel Connectors</td>
                      <td>240</td>
                      <td>$12.50</td>
                      <td>$3,000.00</td>
                      <td>
                        <button className="btn btn-sm btn-danger">
                          <i className="fas fa-times"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colSpan="3" className="text-end fw-bold">
                        Subtotal
                      </td>
                      <td className="fw-bold">$24,600.00</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td colSpan="3" className="text-end fw-bold">
                        Tax (8%)
                      </td>
                      <td className="fw-bold">$1,968.00</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td colSpan="3" className="text-end fw-bold">
                        Shipping
                      </td>
                      <td className="fw-bold">$350.00</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td colSpan="3" className="text-end fw-bold">
                        Total
                      </td>
                      <td className="fw-bold">$26,918.00</td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <button className="btn btn-sm btn-outline-primary mt-2">
                <i className="fas fa-plus"></i> Add Item
              </button>
            </div>

            <div className="row mb-3">
              <div className="col-md-6">
                <label className="form-label">Delivery Date</label>
                <input
                  type="date"
                  className="form-control"
                  value="2023-06-15"
                />
              </div>
              <div className="col-md-6">
                <label className="form-label">Payment Terms</label>
                <select className="form-select">
                  <option>Net 30</option>
                  <option>Net 60</option>
                  <option>Due on Receipt</option>
                  <option>50% Advance</option>
                </select>
              </div>
            </div>

            <div className="mb-3">
              <label className="form-label">Special Instructions</label>
              <textarea
                className="form-control"
                rows="2"
                placeholder="Any special delivery or packaging requirements"
              ></textarea>
            </div>
          </form>
        </div>
        <div className="modal-footer">
          <button type="button" className="btn btn-secondary" onClick={onClose}>
            Cancel
          </button>
          <button type="button" className="btn btn-primary">
            Generate Purchase Order
          </button>
        </div>
      </div>
    </div>
  );
};

export default CreatePOModal;
